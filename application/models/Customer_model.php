<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer Model
 * Handles Customer CRUD operations, search, pagination, and role isolation
 */
class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get paginated list of customers
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param string|null $status
     * @return array
     */
    public function get_customers($limit = 10, $offset = 0, $search = null, $status = null) {
        try {
            $this->db->select('id, name, email, phone, address, role, avatar, status, created_at, updated_at');
            $this->db->from('users');
            $this->db->where('role', 'customer');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('name', $search);
                $this->db->or_like('email', $search);
                $this->db->or_like('phone', $search);
                $this->db->or_like('address', $search);
                $this->db->group_end();
            }

            if (!empty($status)) {
                $this->db->where('status', $status);
            }

            $this->db->order_by('id', 'DESC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Customer_model get_customers error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total customers for pagination
     *
     * @param string|null $search
     * @param string|null $status
     * @return int
     */
    public function count_customers($search = null, $status = null) {
        try {
            $this->db->from('users');
            $this->db->where('role', 'customer');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('name', $search);
                $this->db->or_like('email', $search);
                $this->db->or_like('phone', $search);
                $this->db->or_like('address', $search);
                $this->db->group_end();
            }

            if (!empty($status)) {
                $this->db->where('status', $status);
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Customer_model count_customers error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get single customer by ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_customer_by_id($id) {
        try {
            $this->db->where('id', (int) $id);
            $this->db->where('role', 'customer');
            $query = $this->db->get('users');
            return ($query && $query->num_rows() === 1) ? $query->row() : NULL;
        } catch (Exception $e) {
            log_message('error', 'Customer_model get_customer_by_id error: ' . $e->getMessage());
            return NULL;
        }
    }

    /**
     * Update customer details
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_customer($id, $data) {
        try {
            $this->db->where('id', (int) $id);
            $this->db->where('role', 'customer');
            return $this->db->update('users', $data);
        } catch (Exception $e) {
            log_message('error', 'Customer_model update_customer error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Delete customer
     *
     * @param int $id
     * @return bool
     */
    public function delete_customer($id) {
        try {
            $this->db->where('id', (int) $id);
            $this->db->where('role', 'customer');
            return $this->db->delete('users');
        } catch (Exception $e) {
            log_message('error', 'Customer_model delete_customer error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Check if email is unique across users
     *
     * @param string $email
     * @param int|null $exclude_id
     * @return bool
     */
    public function is_email_unique($email, $exclude_id = null) {
        try {
            $this->db->where('email', trim($email));
            if ($exclude_id) {
                $this->db->where('id !=', (int) $exclude_id);
            }
            return ($this->db->count_all_results('users') === 0);
        } catch (Exception $e) {
            return TRUE;
        }
    }

    /**
     * Create / Register a new customer
     *
     * @param array $data
     * @return int|bool Insert ID on success, FALSE on failure
     */
    public function create_customer($data) {
        try {
            $data['role'] = 'customer';
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            if (empty($data['password'])) {
                $data['password'] = password_hash('Customer@123', PASSWORD_BCRYPT);
            }
            if ($this->db->insert('users', $data)) {
                return $this->db->insert_id();
            }
            return FALSE;
        } catch (Exception $e) {
            log_message('error', 'Customer_model create_customer error: ' . $e->getMessage());
            return FALSE;
        }
    }
}

