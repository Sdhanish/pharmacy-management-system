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
}
