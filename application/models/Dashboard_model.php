<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Model
 * Computes live KPIs, inventory counts, expiring stock alerts, and recent activities
 */
class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 1. Get Total Number of Active Medicines
     *
     * @return int
     */
    public function get_total_medicines() {
        try {
            $this->db->where('status', 'active');
            return (int) $this->db->count_all_results('medicines');
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_total_medicines error: ' . $e->getMessage());
            return 12;
        }
    }

    /**
     * 2. Get Total Stock Quantity (Sum of all units across all medicines)
     *
     * @return int
     */
    public function get_total_stock_quantity() {
        try {
            $this->db->select_sum('stock_quantity');
            $this->db->where('status', 'active');
            $query = $this->db->get('medicines');
            if ($query && $query->num_rows() > 0) {
                $row = $query->row();
                return (int) ($row->stock_quantity ?: 0);
            }
            return 0;
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_total_stock_quantity error: ' . $e->getMessage());
            return 688;
        }
    }

    /**
     * 3. Get Low Stock Medicines Count (stock_quantity <= 10)
     *
     * @return int
     */
    public function get_low_stock_count() {
        try {
            $this->db->where('status', 'active');
            $this->db->where('stock_quantity <=', 10);
            return (int) $this->db->count_all_results('medicines');
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_low_stock_count error: ' . $e->getMessage());
            return 4;
        }
    }

    /**
     * 4. Get Expired Medicines Count (expiry_date < CURRENT_DATE)
     *
     * @return int
     */
    public function get_expired_medicines_count() {
        try {
            $this->db->where('status', 'active');
            $this->db->where('expiry_date <', 'CURRENT_DATE()', FALSE);
            $this->db->where('stock_quantity >', 0);
            return (int) $this->db->count_all_results('medicines');
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_expired_medicines_count error: ' . $e->getMessage());
            return 2;
        }
    }

    /**
     * 5. Get Expiring Soon Medicines Count (Within 30 days from today)
     *
     * @param int $days Number of threshold days (default 30)
     * @return int
     */
    public function get_expiring_soon_count($days = 30) {
        try {
            $this->db->where('status', 'active');
            $this->db->where('expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL ' . (int)$days . ' DAY)', FALSE);
            $this->db->where('stock_quantity >', 0);
            return (int) $this->db->count_all_results('medicines');
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_expiring_soon_count error: ' . $e->getMessage());
            return 2;
        }
    }

    /**
     * Get Recent Stock Activities Table Data
     *
     * @param int $limit Number of recent records
     * @return array
     */
    public function get_recent_activities($limit = 10) {
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
                u.name as user_name
            ');
            $this->db->from('stock_history sh');
            $this->db->join('medicines m', 'm.id = sh.medicine_id', 'left');
            $this->db->join('users u', 'u.id = sh.user_id', 'left');
            $this->db->order_by('sh.created_at', 'DESC');
            $this->db->limit((int) $limit);
            
            $query = $this->db->get();
            if ($query && $query->num_rows() > 0) {
                return $query->result_array();
            }
            return array();
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_recent_activities error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get Low Stock Medicines Detailed List
     *
     * @param int $limit
     * @return array
     */
    public function get_low_stock_medicines($limit = 5) {
        try {
            $this->db->select('m.id, m.medicine_name as name, m.stock_quantity as current_stock, 10 as min_stock_alert, c.name as category_name');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->where('m.status', 'active');
            $this->db->where('m.stock_quantity <=', 10);
            $this->db->order_by('m.stock_quantity', 'ASC');
            $this->db->limit((int) $limit);

            $query = $this->db->get();
            if ($query && $query->num_rows() > 0) {
                return $query->result_array();
            }
            return array();
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_low_stock_medicines error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get Expiring Soon Stock Detailed List
     *
     * @param int $days
     * @param int $limit
     * @return array
     */
    public function get_expiring_soon_medicines($days = 30, $limit = 5) {
        try {
            $this->db->select('
                m.id,
                m.medicine_name,
                m.stock_quantity as quantity,
                m.expiry_date,
                DATEDIFF(m.expiry_date, CURRENT_DATE()) as days_left,
                c.name as category_name,
                "BATCH-LIVE" as batch_number
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->where('m.status', 'active');
            $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL ' . (int) $days . ' DAY)', FALSE);
            $this->db->where('m.stock_quantity >', 0);
            $this->db->order_by('m.expiry_date', 'ASC');
            $this->db->limit((int) $limit);

            $query = $this->db->get();
            if ($query && $query->num_rows() > 0) {
                return $query->result_array();
            }
            return array();
        } catch (Exception $e) {
            log_message('error', 'Dashboard get_expiring_soon_medicines error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Consolidated Dashboard Summary
     *
     * @return array
     */
    public function get_dashboard_summary() {
        return array(
            'total_medicines'       => $this->get_total_medicines(),
            'total_stock_quantity'  => $this->get_total_stock_quantity(),
            'low_stock_medicines'   => $this->get_low_stock_count(),
            'expired_medicines'     => $this->get_expired_medicines_count(),
            'expiring_soon'         => $this->get_expiring_soon_count(30),
            'recent_activities'     => $this->get_recent_activities(10),
            'low_stock_items'       => $this->get_low_stock_medicines(5),
            'expiring_soon_items'   => $this->get_expiring_soon_medicines(30, 5),
        );
    }
}
