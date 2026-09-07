<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stock Model
 * Handles Stock Purchases, Auto Stock Increments, Auto Stock Reversals, and Audit History
 */
class Stock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get paginated list of stock purchases with joined medicine details and current stock
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param int|null $supplier_id
     * @return array
     */
    public function get_purchases($limit = 10, $offset = 0, $search = null, $supplier_id = null) {
        try {
            $this->db->select('
                sp.id,
                sp.medicine_id,
                sp.supplier_id,
                sp.quantity,
                sp.purchase_price,
                sp.purchase_date,
                sp.notes,
                sp.created_at,
                (sp.quantity * sp.purchase_price) as total_amount,
                m.medicine_name,
                m.stock_quantity as current_stock,
                m.image_url,
                m.price as sell_price,
                s.name as supplier_name
            ');
            $this->db->from('stock_purchases sp');
            $this->db->join('medicines m', 'm.id = sp.medicine_id', 'left');
            $this->db->join('suppliers s', 's.id = sp.supplier_id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->or_like('sp.notes', $search);
                $this->db->group_end();
            }

            if (!empty($supplier_id)) {
                $this->db->where('sp.supplier_id', (int) $supplier_id);
            }

            $this->db->order_by('sp.id', 'DESC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Stock_model get_purchases error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total purchases for pagination
     *
     * @param string|null $search
     * @param int|null $supplier_id
     * @return int
     */
    public function count_purchases($search = null, $supplier_id = null) {
        try {
            $this->db->from('stock_purchases sp');
            $this->db->join('medicines m', 'm.id = sp.medicine_id', 'left');
            $this->db->join('suppliers s', 's.id = sp.supplier_id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->or_like('sp.notes', $search);
                $this->db->group_end();
            }

            if (!empty($supplier_id)) {
                $this->db->where('sp.supplier_id', (int) $supplier_id);
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Stock_model count_purchases error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get single purchase record by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_purchase_by_id($id) {
        try {
            $this->db->select('
                sp.*,
                (sp.quantity * sp.purchase_price) as total_amount,
                m.medicine_name,
                m.stock_quantity as current_stock,
                s.name as supplier_name
            ');
            $this->db->from('stock_purchases sp');
            $this->db->join('medicines m', 'm.id = sp.medicine_id', 'left');
            $this->db->join('suppliers s', 's.id = sp.supplier_id', 'left');
            $this->db->where('sp.id', (int) $id);

            $query = $this->db->get();
            return ($query && $query->num_rows() === 1) ? $query->row() : NULL;
        } catch (Exception $e) {
            log_message('error', 'Stock_model get_purchase_by_id error: ' . $e->getMessage());
            return NULL;
        }
    }

    /**
     * Add Stock Purchase and AUTOMATICALLY INCREASE medicine stock
     *
     * @param array $data
     * @return int Inserted Purchase ID
     */
    public function add_purchase($data) {
        $this->db->trans_start();

        // 1. Insert into stock_purchases
        $this->db->insert('stock_purchases', $data);
        $purchase_id = $this->db->insert_id();

        // 2. Automatically INCREASE medicine stock in medicines table
        $qty = (int) $data['quantity'];
        $med_id = (int) $data['medicine_id'];

        $this->db->set('stock_quantity', 'stock_quantity + ' . $qty, FALSE);
        $this->db->where('id', $med_id);
        $this->db->update('medicines');

        // Fetch updated stock for ledger
        $updated_med = $this->db->select('stock_quantity')->get_where('medicines', array('id' => $med_id))->row();
        $balance_after = $updated_med ? (int)$updated_med->stock_quantity : $qty;

        // 3. Log to stock_history
        $history_data = array(
            'medicine_id'      => $med_id,
            'transaction_type' => 'PURCHASE',
            'quantity'         => $qty,
            'balance_after'    => $balance_after,
            'reference_no'     => 'PO-' . str_pad($purchase_id, 5, '0', STR_PAD_LEFT),
            'notes'            => !empty($data['notes']) ? $data['notes'] : 'Stock purchase consignment received',
            'user_id'          => $this->session->userdata('user_id') ?: 1,
            'created_at'       => date('Y-m-d H:i:s')
        );
        $this->db->insert('stock_history', $history_data);

        $this->db->trans_complete();

        return $this->db->trans_status() ? $purchase_id : 0;
    }

    /**
     * Edit Stock Purchase and AUTOMATICALLY ADJUST medicine stock by the difference
     *
     * @param int $id
     * @param array $new_data
     * @return bool
     */
    public function update_purchase($id, $new_data) {
        $this->db->trans_start();

        // Fetch old purchase record
        $old_purchase = $this->db->get_where('stock_purchases', array('id' => (int) $id))->row();
        if (!$old_purchase) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $old_qty = (int) $old_purchase->quantity;
        $old_med_id = (int) $old_purchase->medicine_id;
        $new_qty = (int) $new_data['quantity'];
        $new_med_id = (int) $new_data['medicine_id'];

        if ($old_med_id === $new_med_id) {
            // Same medicine: adjust difference
            $diff = $new_qty - $old_qty;
            if ($diff !== 0) {
                $this->db->set('stock_quantity', 'GREATEST(0, stock_quantity + ' . $diff . ')', FALSE);
                $this->db->where('id', $new_med_id);
                $this->db->update('medicines');
            }
        } else {
            // Changed medicine: reverse old, add to new
            $this->db->set('stock_quantity', 'GREATEST(0, stock_quantity - ' . $old_qty . ')', FALSE);
            $this->db->where('id', $old_med_id);
            $this->db->update('medicines');

            $this->db->set('stock_quantity', 'stock_quantity + ' . $new_qty, FALSE);
            $this->db->where('id', $new_med_id);
            $this->db->update('medicines');
        }

        // Update purchase record
        $this->db->where('id', (int) $id);
        $this->db->update('stock_purchases', $new_data);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Delete Stock Purchase and AUTOMATICALLY REVERSE medicine stock
     *
     * @param int $id
     * @return bool
     */
    public function delete_purchase($id) {
        $this->db->trans_start();

        $purchase = $this->db->get_where('stock_purchases', array('id' => (int) $id))->row();
        if (!$purchase) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $qty = (int) $purchase->quantity;
        $med_id = (int) $purchase->medicine_id;

        // 1. AUTOMATICALLY REVERSE medicine stock in medicines table
        $this->db->set('stock_quantity', 'GREATEST(0, stock_quantity - ' . $qty . ')', FALSE);
        $this->db->where('id', $med_id);
        $this->db->update('medicines');

        // Fetch balance for audit
        $updated_med = $this->db->select('stock_quantity')->get_where('medicines', array('id' => $med_id))->row();
        $balance_after = $updated_med ? (int)$updated_med->stock_quantity : 0;

        // 2. Delete purchase record
        $this->db->where('id', (int) $id);
        $this->db->delete('stock_purchases');

        // 3. Log reversal in stock_history
        $history_data = array(
            'medicine_id'      => $med_id,
            'transaction_type' => 'RETURN',
            'quantity'         => -1 * $qty,
            'balance_after'    => $balance_after,
            'reference_no'     => 'REV-PO-' . str_pad($id, 5, '0', STR_PAD_LEFT),
            'notes'            => 'Purchase record #' . $id . ' deleted; stock reversed',
            'user_id'          => $this->session->userdata('user_id') ?: 1,
            'created_at'       => date('Y-m-d H:i:s')
        );
        $this->db->insert('stock_history', $history_data);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Get Complete Stock History Ledger
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @return array
     */
    public function get_stock_history($limit = 15, $offset = 0, $search = null) {
        try {
            $this->db->select('
                sh.*,
                m.medicine_name,
                m.stock_quantity as current_stock,
                u.name as user_name
            ');
            $this->db->from('stock_history sh');
            $this->db->join('medicines m', 'm.id = sh.medicine_id', 'left');
            $this->db->join('users u', 'u.id = sh.user_id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('sh.reference_no', $search);
                $this->db->or_like('sh.notes', $search);
                $this->db->group_end();
            }

            $this->db->order_by('sh.created_at', 'DESC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Stock_model get_stock_history error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total stock history records for pagination
     *
     * @param string|null $search
     * @return int
     */
    public function count_stock_history($search = null) {
        try {
            $this->db->from('stock_history sh');
            $this->db->join('medicines m', 'm.id = sh.medicine_id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('sh.reference_no', $search);
                $this->db->or_like('sh.notes', $search);
                $this->db->group_end();
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Get ALL active medicines with current stock and latest purchase info.
     * This ensures every medicine appears in Stock Management even without a purchase order.
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @return array
     */
    public function get_medicine_inventory_overview($limit = 15, $offset = 0, $search = null) {
        try {
            $this->db->select('
                m.id,
                m.medicine_name,
                m.stock_quantity as current_stock,
                m.price as sell_price,
                m.image_url,
                m.status,
                c.name as category_name,
                MAX(sp.purchase_date) as last_purchase_date,
                SUM(sp.quantity) as total_purchased,
                s.name as last_supplier_name,
                sp.purchase_price as last_unit_price
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('stock_purchases sp', 'sp.medicine_id = m.id', 'left');
            $this->db->join('suppliers s', 's.id = sp.supplier_id', 'left');
            $this->db->where('m.status', 'active');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            $this->db->group_by('m.id, m.medicine_name, m.stock_quantity, m.price, m.image_url, m.status, c.name, sp.purchase_price');
            $this->db->order_by('m.stock_quantity', 'ASC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Stock_model get_medicine_inventory_overview error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total active medicines for inventory overview pagination
     *
     * @param string|null $search
     * @return int
     */
    public function count_medicine_inventory($search = null) {
        try {
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = (SELECT supplier_id FROM stock_purchases WHERE medicine_id = m.id ORDER BY id DESC LIMIT 1)', 'left');
            $this->db->where('m.status', 'active');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            return 0;
        }
    }
}
