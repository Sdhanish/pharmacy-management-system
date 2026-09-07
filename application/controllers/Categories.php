<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Category Management Controller
 * Handles Category CRUD operations, Unique Name Validation, Search, and Pagination
 *
 * @property Category_model $Category_model
 * @property CI_Form_validation $form_validation
 * @property CI_Pagination $pagination
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Categories extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library(array('form_validation', 'pagination'));
        $this->load->helper(array('url', 'form', 'text'));

        $this->active_menu = 'categories';
        $this->page_title = 'Medicine Categories';
    }

    /**
     * Display Category List with Search & Pagination
     */
    public function index() {
        $search = $this->input->get('search', TRUE);

        $per_page = 10;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Category_model->count_categories($search);

        // Pagination Configuration
        $config['base_url']             = base_url('categories');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;
        $config['reuse_query_string']   = TRUE;

        // Modern Bootstrap / Tailwind Pagination Styling
        $config['full_tag_open']   = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm mb-0 gap-1 justify-content-center justify-content-md-end">';
        $config['full_tag_close']  = '</ul></nav>';
        $config['first_link']      = '&laquo; First';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link']       = 'Last &raquo;';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $config['next_link']       = 'Next &rsaquo;';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['prev_link']       = '&lsaquo; Prev';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active" aria-current="page"><span class="page-link bg-emerald-600 border-emerald-600 text-white font-bold rounded-lg">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = array('class' => 'page-link rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 border-slate-200');

        $this->pagination->initialize($config);

        $categories = $this->Category_model->get_categories($per_page, $offset, $search);
        $summary = $this->Category_model->get_categories_summary();

        $data = array(
            'page_title'       => 'Medicine Categories',
            'active_menu'      => 'categories',
            'breadcrumbs'      => array('Categories' => ''),
            'categories'       => $categories,
            'summary'          => $summary,
            'search'           => $search,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('categories/index', $data);
    }

    /**
     * Show Add Category Form
     */
    public function create() {
        $data = array(
            'page_title'   => 'Add New Category',
            'active_menu'  => 'categories',
            'breadcrumbs'  => array(
                'Categories' => 'categories',
                'Add Category' => ''
            ),
            'category'     => NULL
        );

        $this->render_view('categories/create', $data);
    }

    /**
     * Process Category Creation
     */
    public function store() {
        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        $category_data = array(
            'name'        => trim($this->input->post('name', TRUE)),
            'description' => trim($this->input->post('description', TRUE)),
            'status'      => $this->input->post('status') ? $this->input->post('status') : 'active',
            'created_at'  => date('Y-m-d H:i:s')
        );

        $insert_id = $this->Category_model->insert_category($category_data);

        if ($insert_id) {
            $this->session->set_flashdata('success', 'Category "' . html_escape($category_data['name']) . '" created successfully!');
            redirect('categories');
        } else {
            $this->session->set_flashdata('error', 'Failed to create category. Please try again.');
            $this->create();
        }
    }

    /**
     * Show Edit Category Form
     *
     * @param int $id
     */
    public function edit($id) {
        $category = $this->Category_model->get_category_by_id($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('categories');
            return;
        }

        $data = array(
            'page_title'   => 'Edit Category: ' . $category->name,
            'active_menu'  => 'categories',
            'breadcrumbs'  => array(
                'Categories' => 'categories',
                'Edit ' . $category->name => ''
            ),
            'category'     => $category
        );

        $this->render_view('categories/edit', $data);
    }

    /**
     * Process Category Update
     *
     * @param int $id
     */
    public function update($id) {
        $category = $this->Category_model->get_category_by_id($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('categories');
            return;
        }

        $this->_set_validation_rules($id);

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $category_data = array(
            'name'        => trim($this->input->post('name', TRUE)),
            'description' => trim($this->input->post('description', TRUE)),
            'status'      => $this->input->post('status') ? $this->input->post('status') : 'active',
            'updated_at'  => date('Y-m-d H:i:s')
        );

        $updated = $this->Category_model->update_category($id, $category_data);

        if ($updated) {
            $this->session->set_flashdata('success', 'Category "' . html_escape($category_data['name']) . '" updated successfully!');
            redirect('categories');
        } else {
            $this->session->set_flashdata('error', 'Failed to update category.');
            $this->edit($id);
        }
    }

    /**
     * Delete Category
     *
     * @param int $id
     */
    public function delete($id) {
        $category = $this->Category_model->get_category_by_id($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found or already deleted.');
            redirect('categories');
            return;
        }

        // Safety check: Prevent deletion if medicines are linked
        $linked_count = $this->Category_model->count_medicines_in_category($id);
        if ($linked_count > 0) {
            $this->session->set_flashdata('error', 'Cannot delete category "' . html_escape($category->name) . '" because ' . $linked_count . ' medicine(s) are currently assigned to it. Please reassign or delete the medicines first.');
            redirect('categories');
            return;
        }

        $deleted = $this->Category_model->delete_category($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Category "' . html_escape($category->name) . '" deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete category.');
        }

        redirect('categories');
    }

    /**
     * Form Validation Rules
     *
     * @param int|null $exclude_id
     */
    private function _set_validation_rules($exclude_id = null) {
        $this->form_validation->set_rules(
            'name', 
            'Category Name', 
            'trim|required|min_length[2]|max_length[100]|callback_validate_name_unique[' . $exclude_id . ']',
            array(
                'required'   => 'Please enter the %s.',
                'min_length' => '%s must be at least 2 characters long.',
                'max_length' => '%s cannot exceed 100 characters.'
            )
        );

        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[1000]');
        $this->form_validation->set_rules('status', 'Status', 'trim|in_list[active,inactive]');
    }

    /**
     * Custom Validation Callback: Unique Category Name
     *
     * @param string $name
     * @param int|null $exclude_id
     * @return bool
     */
    public function validate_name_unique($name, $exclude_id = null) {
        if (empty($name)) return TRUE;

        $is_unique = $this->Category_model->is_name_unique($name, $exclude_id);
        if (!$is_unique) {
            $this->form_validation->set_message('validate_name_unique', 'The Category Name "' . html_escape($name) . '" already exists. Category name must be unique.');
            return FALSE;
        }
        return TRUE;
    }
}

