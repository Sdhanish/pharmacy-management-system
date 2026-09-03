<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Medicine Model
 * Implements Query Builder CRUD operations, search, category filtering, and relational lookups
 */
class Medicine_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get paginated list of medicines with search & category filters
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param int|null $category_id
     * @return array
     */
    public function get_medicines($limit = 10, $offset = 0, $search = null, $category_id = null) {
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
                m.updated_at,
                c.name as category_name,
                s.name as supplier_name
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            // Apply search
            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('m.description', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            // Apply category filter
            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            $this->db->order_by('m.id', 'DESC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Medicine_model get_medicines error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total filtered medicines for pagination
     *
     * @param string|null $search
     * @param int|null $category_id
     * @return int
     */
    public function count_medicines($search = null, $category_id = null) {
        try {
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('m.medicine_name', $search);
                $this->db->or_like('m.description', $search);
                $this->db->or_like('c.name', $search);
                $this->db->or_like('s.name', $search);
                $this->db->group_end();
            }

            if (!empty($category_id)) {
                $this->db->where('m.category_id', (int) $category_id);
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Medicine_model count_medicines error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get single medicine by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_medicine_by_id($id) {
        try {
            $this->db->select('
                m.*,
                c.name as category_name,
                s.name as supplier_name,
                s.contact_person as supplier_contact,
                s.phone as supplier_phone,
                s.email as supplier_email
            ');
            $this->db->from('medicines m');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->join('suppliers s', 's.id = m.supplier_id', 'left');
            $this->db->where('m.id', (int) $id);

            $query = $this->db->get();
            return ($query && $query->num_rows() === 1) ? $query->row() : NULL;
        } catch (Exception $e) {
            log_message('error', 'Medicine_model get_medicine_by_id error: ' . $e->getMessage());
            return NULL;
        }
    }

    /**
     * Insert new medicine record
     *
     * @param array $data
     * @return int
     */
    public function insert_medicine($data) {
        try {
            $this->db->insert('medicines', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Medicine_model insert_medicine error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update medicine record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_medicine($id, $data) {
        try {
            $this->db->where('id', (int) $id);
            return $this->db->update('medicines', $data);
        } catch (Exception $e) {
            log_message('error', 'Medicine_model update_medicine error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Delete medicine record
     *
     * @param int $id
     * @return bool
     */
    public function delete_medicine($id) {
        try {
            $this->db->where('id', (int) $id);
            return $this->db->delete('medicines');
        } catch (Exception $e) {
            log_message('error', 'Medicine_model delete_medicine error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Check if category exists
     *
     * @param int $category_id
     * @return bool
     */
    public function category_exists($category_id) {
        try {
            $this->db->where('id', (int) $category_id);
            return ($this->db->count_all_results('categories') > 0);
        } catch (Exception $e) {
            return TRUE;
        }
    }

    /**
     * Check if supplier exists
     *
     * @param int $supplier_id
     * @return bool
     */
    public function supplier_exists($supplier_id) {
        try {
            if (empty($supplier_id)) return TRUE;
            $this->db->where('id', (int) $supplier_id);
            return ($this->db->count_all_results('suppliers') > 0);
        } catch (Exception $e) {
            return TRUE;
        }
    }
}
