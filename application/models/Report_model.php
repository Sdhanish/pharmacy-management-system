<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Report Model
 * Implements report queries for Available Stock, Low Stock, Expired Medicines, Expiring Soon, and Stock Activity
 */
class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 1. Available Medicines Report Query
     *
     * @param int|null $category_id
     * @param string|null $search
     * @param string|null $start_date
     * @param string|null $end_date
     * @return array
     */
    public function get_available_medicines_report($category_id = null, $search = null, $start_date = null, $end_date = null) {
        try {
            $this->db->select('
                m.id,
                m.medicine_name,
                m.category_id,
                m.supplier_id,
                m.description,
                m.price,
                m.stock_quantity,
                m.expiry_date,
                m.image_url,
                m.status,
                m.created_at,
                (m.price * m.stock_quantity) as total_valuation,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            $this->db->where('m.status', 'active');
            $this->db->where('m.stock_quantity >', 0);

            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            if (!empty($start_date)) {
                $this->db->where('DATE(m.created_at) >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('DATE(m.created_at) <=', $end_date);
            }

            $this->db->order_by('m.stock_quantity', 'DESC');
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Report_model get_available_medicines_report error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * 2. Low Stock Report Query (stock_quantity <= threshold)
     *
     * @param int|null $category_id
     * @param string|null $search
     * @param int $threshold
     * @return array
     */
    public function get_low_stock_report($category_id = null, $search = null, $threshold = 10) {
        try {
            $threshold = (int) ($threshold ?: 10);
            $this->db->select('
                m.id,
                m.medicine_name,
                m.category_id,
                m.supplier_id,
                m.price,
                m.stock_quantity,
                m.expiry_date,
                m.image_url,
                m.status,
                ' . $threshold . ' as min_alert_threshold,
                GREATEST(0, ' . $threshold . ' - m.stock_quantity) as units_to_reorder,
                (GREATEST(0, ' . $threshold . ' - m.stock_quantity) * m.price) as estimated_reorder_cost,
                c.name as category_name,
                s.name as supplier_name,
                s.contact_person as supplier_contact,
                s.phone as supplier_phone
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            $this->db->where('m.status', 'active');
            $this->db->where('m.stock_quantity <=', $threshold);

            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            $this->db->order_by('m.stock_quantity', 'ASC');
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Report_model get_low_stock_report error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * 3. Expired Medicines Report Query
     *
     * @param int|null $category_id
     * @param string|null $search
     * @param string|null $start_date
     * @param string|null $end_date
     * @return array
     */
    public function get_expired_medicines_report($category_id = null, $search = null, $start_date = null, $end_date = null) {
        try {
            $this->db->select('
                m.id,
                m.medicine_name,
                m.category_id,
                m.supplier_id,
                m.price,
                m.stock_quantity,
                m.expiry_date,
                m.image_url,
                m.status,
                (m.price * m.stock_quantity) as financial_loss,
                DATEDIFF(CURRENT_DATE(), m.expiry_date) as days_overdue,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            $this->db->where('m.expiry_date <', 'CURRENT_DATE()', FALSE);

            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            if (!empty($start_date)) {
                $this->db->where('m.expiry_date >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('m.expiry_date <=', $end_date);
            }

            $this->db->order_by('m.expiry_date', 'ASC');
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Report_model get_expired_medicines_report error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * 4. Expiring Soon Medicines Report Query
     *
     * @param int|null $category_id
     * @param string|null $search
     * @param int $days Number of threshold days (default 30)
     * @param string|null $start_date
     * @param string|null $end_date
     * @return array
     */
    public function get_expiring_soon_report($category_id = null, $search = null, $days = 30, $start_date = null, $end_date = null) {
        try {
            $days = (int) ($days ?: 30);
            $this->db->select('
                m.id,
                m.medicine_name,
                m.category_id,
                m.supplier_id,
                m.price,
                m.stock_quantity,
                m.expiry_date,
                m.image_url,
                m.status,
                (m.price * m.stock_quantity) as value_at_risk,
                DATEDIFF(m.expiry_date, CURRENT_DATE()) as days_left,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);

            if (!empty($start_date) && !empty($end_date)) {
                $this->db->where('m.expiry_date >=', $start_date);
                $this->db->where('m.expiry_date <=', $end_date);
            } else {
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL ' . $days . ' DAY)', FALSE);
            }

            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            $this->db->order_by('m.expiry_date', 'ASC');
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Report_model get_expiring_soon_report error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * 5. Stock Activity & Audit Ledger Report Query
     *
     * @param int|null $category_id
     * @param string|null $search
     * @param string|null $start_date
     * @param string|null $end_date
     * @param string|null $transaction_type
     * @return array
     */
    public function get_stock_activity_report($category_id = null, $search = null, $start_date = null, $end_date = null, $transaction_type = null) {
        try {
            $this->db->select('
                sh.id,
                sh.medicine_id,
                sh.transaction_type,
                sh.quantity,
                sh.balance_after,
                sh.reference_no,
                sh.notes,
                sh.created_at,
                m.medicine_name,
                m.price as unit_price,
                (ABS(sh.quantity) * m.price) as transaction_valuation,
                c.name as category_name,
                u.name as user_name,
                u.role as user_role
            ');
            $this->db->from('stock_history sh');
            $this->db->join('medicines m', 'm.id = sh.medicine_id', 'left');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('users u', 'u.id = sh.user_id', 'left');

            if (!empty($transaction_type) && strtoupper($transaction_type) !== 'ALL') {
                $this->db->where('sh.transaction_type', strtoupper($transaction_type));
            }

            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('sh.reference_no', $search);
                $this->db->or_like('sh.notes', $search);
                $this->db->or_like('u.name', $search);
                $this->db->group_end();
            }

            if (!empty($start_date)) {
                $this->db->where('DATE(sh.created_at) >=', $start_date);
            }

            if (!empty($end_date)) {
                $this->db->where('DATE(sh.created_at) <=', $end_date);
            }

            $this->db->order_by('sh.created_at', 'DESC');
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Report_model get_stock_activity_report error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get High-Level Overview Metrics for Reports Hub
     *
     * @return array
     */
    public function get_reports_overview_summary() {
        try {
            // 1. Available Stock & Total Valuation
            $this->db->select('COUNT(id) as total_products, SUM(stock_quantity) as total_units, SUM(price * stock_quantity) as total_valuation');
            $this->db->where('status', 'active');
            $this->db->where('stock_quantity >', 0);
            $available = $this->db->get('medicines')->row();

            // 2. Low Stock Count
            $this->db->where('status', 'active');
            $this->db->where('stock_quantity <=', 10);
            $low_stock_count = $this->db->count_all_results('medicines');

            // 3. Expired Count & Loss
            $this->db->select('COUNT(id) as total_expired, SUM(stock_quantity * price) as total_loss');
            $this->db->where('expiry_date <', 'CURRENT_DATE()', FALSE);
            $expired = $this->db->get('medicines')->row();

            // 4. Expiring in 30 Days Count
            $this->db->where('expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
            $expiring_soon_count = $this->db->count_all_results('medicines');

            // 5. Total Stock Movements
            $total_activities = $this->db->count_all_results('stock_history');

            return array(
                'available_products'   => $available ? (int)$available->total_products : 0,
                'available_units'      => $available ? (int)$available->total_units : 0,
                'available_valuation'  => $available ? (float)$available->total_valuation : 0.00,
                'low_stock_count'      => (int)$low_stock_count,
                'expired_count'        => $expired ? (int)$expired->total_expired : 0,
                'expired_loss'         => $expired ? (float)$expired->total_loss : 0.00,
                'expiring_soon_count'  => (int)$expiring_soon_count,
                'total_activities'     => (int)$total_activities
            );
        } catch (Exception $e) {
            log_message('error', 'Report_model get_reports_overview_summary error: ' . $e->getMessage());
            return array(
                'available_products'   => 0,
                'available_units'      => 0,
                'available_valuation'  => 0.00,
                'low_stock_count'      => 0,
                'expired_count'        => 0,
                'expired_loss'         => 0.00,
                'expiring_soon_count'  => 0,
                'total_activities'     => 0
            );
        }
    }
}
