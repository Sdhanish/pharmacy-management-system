<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sale Model
 * Handles Customer Purchases / Sales Dispensing, Inventory Deductions, and Stock History Logging
 */
class Sale_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Generate Unique Invoice Number (e.g., INV-20260907-0001)
     *
     * @return string
     */
    public function generate_invoice_no() {
        $prefix = 'INV-' . date('Ymd') . '-';
        $this->db->like('invoice_no', $prefix, 'after');
        $count = $this->db->count_all_results('sales');
        $next_seq = $count + 1;
        return $prefix . str_pad($next_seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get Paginated List of Sales Transactions
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param int|null $customer_id
     * @param string|null $payment_status
     * @param string|null $start_date
     * @param string|null $end_date
     * @return array
     */
    public function get_sales($limit = 10, $offset = 0, $search = null, $customer_id = null, $payment_status = null, $start_date = null, $end_date = null) {
        try {
            $this->db->select('
                s.*,
                u.name as registered_customer_name,
                u.email as customer_email,
                admin_u.name as billed_by_name,
                (SELECT COUNT(*) FROM sale_items si WHERE si.sale_id = s.id) as total_items_count
            ');
            $this->db->from('sales s');
            $this->db->join('users u', 'u.id = s.customer_id', 'left');
            $this->db->join('users admin_u', 'admin_u.id = s.created_by', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('s.invoice_no', $search);
                $this->db->or_like('s.customer_name', $search);
                $this->db->or_like('s.customer_phone', $search);
                $this->db->group_end();
            }

            if (!empty($customer_id)) {
                $this->db->where('s.customer_id', (int) $customer_id);
            }

            if (!empty($payment_status)) {
                $this->db->where('s.payment_status', $payment_status);
            }

            if (!empty($start_date)) {
                $this->db->where('s.sale_date >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('s.sale_date <=', $end_date);
            }

            $this->db->order_by('s.id', 'DESC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Sale_model get_sales error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count Total Sales for Pagination
     */
    public function count_sales($search = null, $customer_id = null, $payment_status = null, $start_date = null, $end_date = null) {
        try {
            $this->db->from('sales s');
            $this->db->join('users u', 'u.id = s.customer_id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('s.invoice_no', $search);
                $this->db->or_like('s.customer_name', $search);
                $this->db->or_like('s.customer_phone', $search);
                $this->db->group_end();
            }

            if (!empty($customer_id)) {
                $this->db->where('s.customer_id', (int) $customer_id);
            }

            if (!empty($payment_status)) {
                $this->db->where('s.payment_status', $payment_status);
            }

            if (!empty($start_date)) {
                $this->db->where('s.sale_date >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('s.sale_date <=', $end_date);
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Get Single Sale by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_sale_by_id($id) {
        try {
            $this->db->select('
                s.*,
                u.name as registered_customer_name,
                u.email as customer_email,
                u.address as customer_registered_address,
                admin_u.name as billed_by_name,
                admin_u.role as billed_by_role
            ');
            $this->db->from('sales s');
            $this->db->join('users u', 'u.id = s.customer_id', 'left');
            $this->db->join('users admin_u', 'admin_u.id = s.created_by', 'left');
            $this->db->where('s.id', (int) $id);

            $query = $this->db->get();
            return ($query && $query->num_rows() === 1) ? $query->row() : NULL;
        } catch (Exception $e) {
            log_message('error', 'Sale_model get_sale_by_id error: ' . $e->getMessage());
            return NULL;
        }
    }

    /**
     * Get Line Items for a Sale
     *
     * @param int $sale_id
     * @return array
     */
    public function get_sale_items($sale_id) {
        try {
            $this->db->select('
                si.*,
                m.stock_quantity as current_medicine_stock,
                m.expiry_date as medicine_expiry,
                c.name as category_name
            ');
            $this->db->from('sale_items si');
            $this->db->join('medicines m', 'm.id = si.medicine_id', 'left');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->where('si.sale_id', (int) $sale_id);
            $this->db->order_by('si.id', 'ASC');

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Create Customer Sale with Multi-Item Bulk Selection,
     * Automatic Stock Deduction, and Stock History Audit Ledger.
     *
     * @param array $sale_data
     * @param array $items Array of items [ ['medicine_id' => 1, 'quantity' => 2, 'unit_price' => 15.00], ... ]
     * @return array ['status' => bool, 'message' => string, 'sale_id' => int|null]
     */
    public function create_sale($sale_data, $items) {
        if (empty($items)) {
            return array('status' => FALSE, 'message' => 'Please add at least one medicine to record customer purchase.');
        }

        // 1. Pre-validation: Verify stock for all medicines
        foreach ($items as $item) {
            $med_id = (int) $item['medicine_id'];
            $req_qty = (int) $item['quantity'];

            if ($req_qty <= 0) {
                return array('status' => FALSE, 'message' => 'Quantity must be at least 1 for all items.');
            }

            $med = $this->db->get_where('medicines', array('id' => $med_id))->row();
            if (!$med) {
                return array('status' => FALSE, 'message' => 'Selected medicine (ID #' . $med_id . ') does not exist.');
            }

            if ((int)$med->stock_quantity < $req_qty) {
                return array(
                    'status' => FALSE, 
                    'message' => 'Insufficient stock for "' . html_escape($med->medicine_name) . '". Available: ' . $med->stock_quantity . ', Requested: ' . $req_qty
                );
            }
        }

        // 2. Begin Transaction
        $this->db->trans_start();

        if (empty($sale_data['invoice_no'])) {
            $sale_data['invoice_no'] = $this->generate_invoice_no();
        }
        $sale_data['created_at'] = date('Y-m-d H:i:s');
        $sale_data['updated_at'] = date('Y-m-d H:i:s');

        // Insert into sales table
        $this->db->insert('sales', $sale_data);
        $sale_id = $this->db->insert_id();

        $admin_user_id = !empty($sale_data['created_by']) ? $sale_data['created_by'] : ($this->session->userdata('user_id') ?: 1);

        // 3. Process each line item
        foreach ($items as $item) {
            $med_id = (int) $item['medicine_id'];
            $qty = (int) $item['quantity'];
            $unit_price = (float) $item['unit_price'];
            $total_price = $qty * $unit_price;

            $med = $this->db->get_where('medicines', array('id' => $med_id))->row();
            $med_name = $med ? $med->medicine_name : ('Medicine #' . $med_id);

            // Insert into sale_items
            $item_data = array(
                'sale_id'       => $sale_id,
                'medicine_id'   => $med_id,
                'medicine_name' => $med_name,
                'quantity'      => $qty,
                'unit_price'    => $unit_price,
                'total_price'   => $total_price,
                'created_at'    => date('Y-m-d H:i:s')
            );
            $this->db->insert('sale_items', $item_data);

            // Deduct stock from medicines table
            $this->db->set('stock_quantity', 'GREATEST(0, stock_quantity - ' . $qty . ')', FALSE);
            $this->db->where('id', $med_id);
            $this->db->update('medicines');

            // Fetch balance for ledger
            $updated_med = $this->db->select('stock_quantity')->get_where('medicines', array('id' => $med_id))->row();
            $balance_after = $updated_med ? (int)$updated_med->stock_quantity : 0;

            // Log SALE into stock_history
            $history_data = array(
                'medicine_id'      => $med_id,
                'transaction_type' => 'SALE',
                'quantity'         => -1 * $qty,
                'balance_after'    => $balance_after,
                'reference_no'     => $sale_data['invoice_no'],
                'notes'            => 'Customer Sale: ' . $sale_data['customer_name'] . ' (' . $sale_data['invoice_no'] . ')',
                'user_id'          => $admin_user_id,
                'created_at'       => date('Y-m-d H:i:s')
            );
            $this->db->insert('stock_history', $history_data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => FALSE, 'message' => 'Database transaction failed while recording sale.');
        }

        return array(
            'status'     => TRUE, 
            'message'    => 'Customer purchase recorded successfully with invoice ' . $sale_data['invoice_no'] . '!',
            'sale_id'    => $sale_id,
            'invoice_no' => $sale_data['invoice_no']
        );
    }

    /**
     * Delete / Cancel Sale and REVERSE Stock Back to Inventory
     *
     * @param int $id
     * @return bool
     */
    public function delete_sale($id) {
        $this->db->trans_start();

        $sale = $this->get_sale_by_id($id);
        if (!$sale) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $items = $this->get_sale_items($id);

        // Reverse stock for all items
        foreach ($items as $item) {
            $med_id = (int) $item['medicine_id'];
            $qty = (int) $item['quantity'];

            // Increase medicine stock back
            $this->db->set('stock_quantity', 'stock_quantity + ' . $qty, FALSE);
            $this->db->where('id', $med_id);
            $this->db->update('medicines');

            // Fetch balance
            $updated_med = $this->db->select('stock_quantity')->get_where('medicines', array('id' => $med_id))->row();
            $balance_after = $updated_med ? (int)$updated_med->stock_quantity : $qty;

            // Log RETURN into stock_history
            $history_data = array(
                'medicine_id'      => $med_id,
                'transaction_type' => 'RETURN',
                'quantity'         => $qty,
                'balance_after'    => $balance_after,
                'reference_no'     => 'CANCEL-' . $sale->invoice_no,
                'notes'            => 'Sale cancelled/refunded for Invoice #' . $sale->invoice_no,
                'user_id'          => $this->session->userdata('user_id') ?: 1,
                'created_at'       => date('Y-m-d H:i:s')
            );
            $this->db->insert('stock_history', $history_data);
        }

        // Delete sale items and sale record
        $this->db->where('sale_id', (int) $id)->delete('sale_items');
        $this->db->where('id', (int) $id)->delete('sales');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Fetch all Active Medicines with Stock for Sale Form Dropdown
     *
     * @return array
     */
    public function get_medicines_for_sale() {
        try {
            $this->db->select('m.id, m.medicine_name, m.price, m.stock_quantity, m.expiry_date, c.name as category_name');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->where('m.status', 'active');
            $this->db->where('m.stock_quantity >', 0);
            $this->db->where('m.expiry_date >=', date('Y-m-d'));
            $this->db->order_by('m.medicine_name', 'ASC');

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Fetch all Active Customers for Sale Dropdown
     *
     * @return array
     */
    public function get_active_customers() {
        try {
            $this->db->select('id, name, email, phone, address');
            $this->db->from('users');
            $this->db->where('role', 'customer');
            $this->db->where('status', 'active');
            $this->db->order_by('name', 'ASC');

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Get Sales Summary Metrics (Total sales, Total Revenue, Today's Sales)
     *
     * @return array
     */
    public function get_sales_metrics() {
        try {
            $today = date('Y-m-d');
            
            // Total revenue & sales count
            $this->db->select('COUNT(*) as total_sales, SUM(total_amount) as total_revenue');
            $row_all = $this->db->get('sales')->row();

            // Today's revenue & sales count
            $this->db->select('COUNT(*) as today_sales, SUM(total_amount) as today_revenue');
            $this->db->where('sale_date', $today);
            $row_today = $this->db->get('sales')->row();

            return array(
                'total_sales'   => (int) ($row_all->total_sales ?? 0),
                'total_revenue' => (float) ($row_all->total_revenue ?? 0.00),
                'today_sales'   => (int) ($row_today->today_sales ?? 0),
                'today_revenue' => (float) ($row_today->today_revenue ?? 0.00)
            );
        } catch (Exception $e) {
            return array('total_sales' => 0, 'total_revenue' => 0.00, 'today_sales' => 0, 'today_revenue' => 0.00);
        }
    }
}
