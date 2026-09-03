<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Category Model
 */
class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all active categories
     *
     * @return array
     */
    public function get_active_categories() {
        try {
            $this->db->where('status', 'active');
            $this->db->order_by('name', 'ASC');
            $query = $this->db->get('categories');
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Category_model get_active_categories error: ' . $e->getMessage());
            return array();
        }
    }
}
