<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Expiry Model
 * Handles database queries, MySQL date comparisons, status classifications, and analytics for expired & expiring stock
 */
class Expiry_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get paginated list of EXPIRED medicines (expiry_date < CURRENT_DATE)
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @return array
     */
    public function get_expired_medicines($limit = 10, $offset = 0, $search = null) {
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
                (m.price * m.stock_quantity) as total_loss_value,
                DATEDIFF(CURRENT_DATE(), m.expiry_date) as days_overdue,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            // MySQL Date comparison: Expired
            $this->db->where('m.expiry_date <', 'CURRENT_DATE()', FALSE);

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            $this->db->order_by('m.expiry_date', 'ASC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Expiry_model get_expired_medicines error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total expired medicines for pagination
     *
     * @param string|null $search
     * @return int
     */
    public function count_expired_medicines($search = null) {
        try {
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');
            $this->db->where('m.expiry_date <', 'CURRENT_DATE()', FALSE);

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
            log_message('error', 'Expiry_model count_expired_medicines error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get paginated list of EXPIRING medicines within X days (e.g. 7 or 30 days)
     *
     * @param int $days Number of threshold days
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @return array
     */
    public function get_expiring_medicines($days = 30, $limit = 10, $offset = 0, $search = null) {
        try {
            $days = (int) $days;
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
                (m.price * m.stock_quantity) as total_at_risk_value,
                DATEDIFF(m.expiry_date, CURRENT_DATE()) as days_left,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            // MySQL Date comparison: Within range [today, today + days]
            $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL ' . $days . ' DAY)', FALSE);

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            $this->db->order_by('m.expiry_date', 'ASC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Expiry_model get_expiring_medicines error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total expiring medicines within X days for pagination
     *
     * @param int $days
     * @param string|null $search
     * @return int
     */
    public function count_expiring_medicines($days = 30, $search = null) {
        try {
            $days = (int) $days;
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL ' . $days . ' DAY)', FALSE);

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
            log_message('error', 'Expiry_model count_expiring_medicines error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get all expiry alert medicines (Expired + Expiring <= 30 Days) with category & supplier details
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param string $filter 'all' | 'expired' | '7days' | '30days'
     * @return array
     */
    public function get_all_expiry_medicines($limit = 10, $offset = 0, $search = null, $filter = 'all') {
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
                (m.price * m.stock_quantity) as total_value,
                DATEDIFF(m.expiry_date, CURRENT_DATE()) as days_left,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            // Apply filter
            if ($filter === 'expired') {
                $this->db->where('m.expiry_date <', 'CURRENT_DATE()', FALSE);
            } elseif ($filter === '7days') {
                $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)', FALSE);
            } elseif ($filter === '30days') {
                $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
            } else {
                // All alerts: expired OR expiring within 30 days
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
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
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Expiry_model get_all_expiry_medicines error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total for all expiry alerts matching filter
     *
     * @param string|null $search
     * @param string $filter
     * @return int
     */
    public function count_all_expiry_medicines($search = null, $filter = 'all') {
        try {
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            if ($filter === 'expired') {
                $this->db->where('m.expiry_date <', 'CURRENT_DATE()', FALSE);
            } elseif ($filter === '7days') {
                $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)', FALSE);
            } elseif ($filter === '30days') {
                $this->db->where('m.expiry_date >=', 'CURRENT_DATE()', FALSE);
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
            } else {
                $this->db->where('m.expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
            }

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
            log_message('error', 'Expiry_model count_all_expiry_medicines error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get Consolidated Expiry Summary KPIs
     *
     * @return array
     */
    public function get_expiry_kpis() {
        try {
            // 1. Expired Count
            $total_expired = (int) $this->db->where('expiry_date <', 'CURRENT_DATE()', FALSE)->count_all_results('medicines');

            // 2. Expired Loss Value & Units
            $this->db->select('SUM(stock_quantity) as total_units, SUM(price * stock_quantity) as total_value');
            $this->db->where('expiry_date <', 'CURRENT_DATE()', FALSE);
            $expired_query = $this->db->get('medicines')->row();
            $expired_units = $expired_query ? (int)$expired_query->total_units : 0;
            $expired_value = $expired_query ? (float)$expired_query->total_value : 0.00;

            // 3. Expiring in 7 Days Count
            $this->db->where('expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)', FALSE);
            $expiring_7_days = (int) $this->db->count_all_results('medicines');

            // 4. Expiring in 30 Days Count
            $this->db->where('expiry_date >=', 'CURRENT_DATE()', FALSE);
            $this->db->where('expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
            $expiring_30_days = (int) $this->db->count_all_results('medicines');

            // 5. Total at Risk Value (Expired + Expiring <= 30 Days)
            $this->db->select('SUM(price * stock_quantity) as total_risk_value, SUM(stock_quantity) as total_risk_units');
            $this->db->where('expiry_date <=', 'DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)', FALSE);
            $risk_query = $this->db->get('medicines')->row();
            $total_risk_value = $risk_query ? (float)$risk_query->total_risk_value : 0.00;
            $total_risk_units = $risk_query ? (int)$risk_query->total_risk_units : 0;

            return array(
                'total_expired'     => $total_expired,
                'expired_units'     => $expired_units,
                'expired_value'     => $expired_value,
                'expiring_7_days'   => $expiring_7_days,
                'expiring_30_days'  => $expiring_30_days,
                'total_risk_value'  => $total_risk_value,
                'total_risk_units'  => $total_risk_units
            );
        } catch (Exception $e) {
            log_message('error', 'Expiry_model get_expiry_kpis error: ' . $e->getMessage());
            return array(
                'total_expired'     => 0,
                'expired_units'     => 0,
                'expired_value'     => 0.00,
                'expiring_7_days'   => 0,
                'expiring_30_days'  => 0,
                'total_risk_value'  => 0.00,
                'total_risk_units'  => 0
            );
        }
    }
}
