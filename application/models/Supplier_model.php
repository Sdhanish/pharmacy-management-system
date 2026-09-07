<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Supplier / Brand Model
 */
class Supplier_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all active suppliers/brands
     *
     * @return array
     */
    public function get_active_suppliers() {
        try {
            $this->db->where('status', 'active');
            $this->db->order_by('name', 'ASC');
            $query = $this->db->get('suppliers');
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Supplier_model get_active_suppliers error: ' . $e->getMessage());
            return array();
        }
    }

    /**
 * Get paginated supplier list
 *
 * @param int $limit
 * @param int $offset
 * @param string|null $search
 * @return array
 */
public function get_suppliers($limit = 10, $offset = 0, $search = null)
{
    try {
        $this->db->select('*');
        $this->db->from('suppliers');

        if (!empty($search)) {
            $search = trim($search);

$this->db->group_start();
$this->db->like('name', $search);
$this->db->or_like('contact_person', $search);
$this->db->or_like('email', $search);
$this->db->or_like('phone', $search);
$this->db->group_end();
        }

        $this->db->order_by('id', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);

        return $this->db->get()->result_array();

    } catch (Exception $e) {
        log_message('error', 'Supplier_model get_suppliers: ' . $e->getMessage());
        return [];
    }
}

/**
 * Count suppliers
 *
 * @param string|null $search
 * @return int
 */
public function count_suppliers($search = null)
{
    try {
        $this->db->from('suppliers');

        if (!empty($search)) {
            $search = trim($search);

$this->db->group_start();
$this->db->like('name', $search);
$this->db->or_like('contact_person', $search);
$this->db->or_like('email', $search);
$this->db->or_like('phone', $search);
$this->db->group_end();
        }

        return (int)$this->db->count_all_results();

    } catch (Exception $e) {
        log_message('error', 'Supplier_model count_suppliers: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Get supplier by ID
 *
 * @param int $id
 * @return object|null
 */
public function get_supplier_by_id($id)
{
    try {
        return $this->db
            ->where('id', (int)$id)
            ->get('suppliers')
            ->row();

    } catch (Exception $e) {
        log_message('error', 'Supplier_model get_supplier_by_id: ' . $e->getMessage());
        return null;
    }
}

/**
 * Create supplier
 *
 * @param array $data
 * @return bool
 */
public function create_supplier($data)
{
    try {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->insert('suppliers', $data);

    } catch (Exception $e) {
        log_message('error', 'Supplier_model create_supplier: ' . $e->getMessage());
        return false;
    }
}

/**
 * Update supplier
 *
 * @param int $id
 * @param array $data
 * @return bool
 */
public function update_supplier($id, $data)
{
    try {
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db
            ->where('id', (int)$id)
            ->update('suppliers', $data);

    } catch (Exception $e) {
        log_message('error', 'Supplier_model update_supplier: ' . $e->getMessage());
        return false;
    }
}

/**
 * Delete supplier
 *
 * @param int $id
 * @return bool
 */
public function delete_supplier($id)
{
    try {
        return $this->db
            ->where('id', (int)$id)
            ->delete('suppliers');

    } catch (Exception $e) {
        log_message('error', 'Supplier_model delete_supplier: ' . $e->getMessage());
        return false;
    }
}

/**
 * Check email uniqueness
 *
 * @param string $email
 * @param int|null $exclude_id
 * @return bool
 */
public function is_email_unique($email, $exclude_id = null)
{
    try {
        $this->db->where('email', trim($email));

        if (!empty($exclude_id)) {
            $this->db->where('id !=', (int)$exclude_id);
        }

        return ($this->db->count_all_results('suppliers') === 0);

    } catch (Exception $e) {
        log_message('error', 'Supplier_model is_email_unique: ' . $e->getMessage());
        return true;
    }
}


}
