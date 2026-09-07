<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Category Model
 * Handles Category CRUD operations, name uniqueness validation, and medicine count relations
 */
class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get paginated list of categories with associated medicine counts
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @return array
     */
    public function get_categories($limit = 10, $offset = 0, $search = null) {
        try {
            $this->db->select('
                c.id,
                c.name,
                c.slug,
                c.description,
                c.status,
                c.created_at,
                c.updated_at,
                COUNT(m.id) as total_medicines
            ');
            $this->db->from('categories c');
            $this->db->join('medicines m', 'm.category_id = c.id', 'left');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('c.name', $search);
                $this->db->or_like('c.description', $search);
                $this->db->or_like('c.slug', $search);
                $this->db->group_end();
            }

            $this->db->group_by('c.id');
            $this->db->order_by('c.name', 'ASC');
            $this->db->limit((int) $limit, (int) $offset);

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Category_model get_categories error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Count total categories matching search filter for pagination
     *
     * @param string|null $search
     * @return int
     */
    public function count_categories($search = null) {
        try {
            $this->db->from('categories c');

            if (!empty($search)) {
                $search = trim($search);
                $this->db->group_start();
                $this->db->like('c.name', $search);
                $this->db->or_like('c.description', $search);
                $this->db->or_like('c.slug', $search);
                $this->db->group_end();
            }

            return (int) $this->db->count_all_results();
        } catch (Exception $e) {
            log_message('error', 'Category_model count_categories error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get single category by ID with medicine count
     *
     * @param int $id
     * @return object|null
     */
    public function get_category_by_id($id) {
        try {
            $this->db->select('
                c.*,
                COUNT(m.id) as total_medicines
            ');
            $this->db->from('categories c');
            $this->db->join('medicines m', 'm.category_id = c.id', 'left');
            $this->db->where('c.id', (int) $id);
            $this->db->group_by('c.id');

            $query = $this->db->get();
            return ($query && $query->num_rows() === 1) ? $query->row() : NULL;
        } catch (Exception $e) {
            log_message('error', 'Category_model get_category_by_id error: ' . $e->getMessage());
            return NULL;
        }
    }

    /**
     * Get all active categories for dropdowns (e.g. in Medicines form)
     *
     * @return array
     */
    public function get_active_categories() {
        try {
            $this->db->select('id, name, slug, description, status');
            $this->db->from('categories');
            $this->db->where('status', 'active');
            $this->db->order_by('name', 'ASC');
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Category_model get_active_categories error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Insert new category record
     *
     * @param array $data
     * @return int Inserted ID
     */
    public function insert_category($data) {
        try {
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->generate_unique_slug($data['name']);
            }
            $this->db->insert('categories', $data);
            return (int) $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Category_model insert_category error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update existing category record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_category($id, $data) {
        try {
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->generate_unique_slug($data['name'], $id);
            }
            $this->db->where('id', (int) $id);
            return $this->db->update('categories', $data);
        } catch (Exception $e) {
            log_message('error', 'Category_model update_category error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Delete category by ID
     *
     * @param int $id
     * @return bool
     */
    public function delete_category($id) {
        try {
            $this->db->where('id', (int) $id);
            return $this->db->delete('categories');
        } catch (Exception $e) {
            log_message('error', 'Category_model delete_category error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Count how many medicines are linked to this category
     *
     * @param int $category_id
     * @return int
     */
    public function count_medicines_in_category($category_id) {
        try {
            $this->db->where('category_id', (int) $category_id);
            return (int) $this->db->count_all_results('medicines');
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Check if category name is unique (case-insensitive)
     *
     * @param string $name
     * @param int|null $exclude_id
     * @return bool
     */
    public function is_name_unique($name, $exclude_id = null) {
        try {
            $this->db->where('LOWER(name)', strtolower(trim($name)));
            if ($exclude_id) {
                $this->db->where('id !=', (int) $exclude_id);
            }
            return ($this->db->count_all_results('categories') === 0);
        } catch (Exception $e) {
            return TRUE;
        }
    }

    /**
     * Helper to generate a clean, unique slug
     *
     * @param string $name
     * @param int|null $exclude_id
     * @return string
     */
    public function generate_unique_slug($name, $exclude_id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        if (empty($slug)) {
            $slug = 'category-' . time();
        }

        $base_slug = $slug;
        $counter = 1;

        while (TRUE) {
            $this->db->where('slug', $slug);
            if ($exclude_id) {
                $this->db->where('id !=', (int) $exclude_id);
            }
            $count = $this->db->count_all_results('categories');
            if ($count === 0) {
                break;
            }
            $slug = $base_slug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get summary metrics for Category Management header
     *
     * @return array
     */
    public function get_categories_summary() {
        try {
            $total_categories = $this->db->count_all_results('categories');
            $active_categories = $this->db->where('status', 'active')->count_all_results('categories');
            $total_medicines = $this->db->count_all_results('medicines');

            return array(
                'total_categories'  => $total_categories,
                'active_categories' => $active_categories,
                'total_medicines'   => $total_medicines
            );
        } catch (Exception $e) {
            return array(
                'total_categories'  => 0,
                'active_categories' => 0,
                'total_medicines'   => 0
            );
        }
    }
}

