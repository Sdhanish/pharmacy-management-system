<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Medicine Management Controller
 * Handles Complete CRUD Operations, Search, Category Filter, Pagination, and Business Rules
 *
 * @property Medicine_model $Medicine_model
 * @property Category_model $Category_model
 * @property Supplier_model $Supplier_model
 * @property CI_Form_validation $form_validation
 * @property CI_Pagination $pagination
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Medicines extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Medicine_model', 'Category_model', 'Supplier_model'));
        $this->load->library(array('form_validation', 'pagination'));
        $this->load->helper(array('url', 'form'));
        
        $this->active_menu = 'medicines';
        $this->page_title = 'Medicine Management';
    }

    /**
     * Read Medicine List (Index) with Search, Category Filter, and Pagination
     */
    public function index() {
        $search = $this->input->get('search', TRUE);
        $category_id = $this->input->get('category_id', TRUE);

        $per_page = 8;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        // Total count for pagination
        $total_rows = $this->Medicine_model->count_medicines($search, $category_id);

        // Pagination Configuration
        $config['base_url']             = base_url('medicines');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;
        $config['reuse_query_string']   = TRUE;

        // Bootstrap 5 Pagination Markup
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

        $medicines = $this->Medicine_model->get_medicines($per_page, $offset, $search, $category_id);
        $categories = $this->Category_model->get_active_categories();

        $data = array(
            'page_title'       => 'Medicine Management',
            'active_menu'      => 'medicines',
            'breadcrumbs'      => array('Medicines' => ''),
            'medicines'        => $medicines,
            'categories'       => $categories,
            'search'           => $search,
            'category_id'      => $category_id,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('medicines/index', $data);
    }

    /**
     * Create Medicine (Show Form)
     */
    public function create() {
        $data = array(
            'page_title'   => 'Add New Medicine',
            'active_menu'  => 'medicines',
            'breadcrumbs'  => array(
                'Medicines' => 'medicines',
                'Create' => ''
            ),
            'categories'   => $this->Category_model->get_active_categories(),
            'suppliers'    => $this->Supplier_model->get_active_suppliers(),
            'medicine'     => NULL
        );

        $this->render_view('medicines/create', $data);
    }

    /**
     * Store Medicine (Process Create Form with Business Rules Validation)
     */
    public function store() {
        $this->_set_validation_rules(TRUE);

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        $medicine_data = array(
            'medicine_name'  => trim($this->input->post('medicine_name', TRUE)),
            'category_id'    => (int) $this->input->post('category_id', TRUE),
            'supplier_id'    => !empty($this->input->post('supplier_id')) ? (int) $this->input->post('supplier_id') : NULL,
            'description'    => trim($this->input->post('description', TRUE)),
            'price'          => (float) $this->input->post('price', TRUE),
            'stock_quantity' => (int) $this->input->post('stock_quantity', TRUE),
            'expiry_date'    => trim($this->input->post('expiry_date', TRUE)),
            'image_url'      => trim($this->input->post('image_url', TRUE)) ?: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&auto=format&fit=crop&q=80',
            'status'         => $this->input->post('status') ? $this->input->post('status') : 'active',
            'created_at'     => date('Y-m-d H:i:s')
        );

        $insert_id = $this->Medicine_model->insert_medicine($medicine_data);

        if ($insert_id) {
            $this->session->set_flashdata('success', 'Medicine "' . html_escape($medicine_data['medicine_name']) . '" created successfully!');
            redirect('medicines');
        } else {
            $this->session->set_flashdata('error', 'Failed to create medicine. Please try again.');
            $this->create();
        }
    }

    /**
     * Edit Medicine (Show Form with Prefilled Data)
     *
     * @param int $id
     */
    public function edit($id) {
        $medicine = $this->Medicine_model->get_medicine_by_id($id);
        if (!$medicine) {
            $this->session->set_flashdata('error', 'Medicine not found.');
            redirect('medicines');
            return;
        }

        $data = array(
            'page_title'   => 'Edit Medicine: ' . $medicine->medicine_name,
            'active_menu'  => 'medicines',
            'breadcrumbs'  => array(
                'Medicines' => 'medicines',
                'Edit ' . $medicine->medicine_name => ''
            ),
            'categories'   => $this->Category_model->get_active_categories(),
            'suppliers'    => $this->Supplier_model->get_active_suppliers(),
            'medicine'     => $medicine
        );

        $this->render_view('medicines/edit', $data);
    }

    /**
     * Update Medicine (Process Edit Form)
     *
     * @param int $id
     */
    public function update($id) {
        $medicine = $this->Medicine_model->get_medicine_by_id($id);
        if (!$medicine) {
            $this->session->set_flashdata('error', 'Medicine not found.');
            redirect('medicines');
            return;
        }

        $this->_set_validation_rules(FALSE);

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $medicine_data = array(
            'medicine_name'  => trim($this->input->post('medicine_name', TRUE)),
            'category_id'    => (int) $this->input->post('category_id', TRUE),
            'supplier_id'    => !empty($this->input->post('supplier_id')) ? (int) $this->input->post('supplier_id') : NULL,
            'description'    => trim($this->input->post('description', TRUE)),
            'price'          => (float) $this->input->post('price', TRUE),
            'stock_quantity' => (int) $this->input->post('stock_quantity', TRUE),
            'expiry_date'    => trim($this->input->post('expiry_date', TRUE)),
            'image_url'      => trim($this->input->post('image_url', TRUE)) ?: $medicine->image_url,
            'status'         => $this->input->post('status') ? $this->input->post('status') : 'active',
            'updated_at'     => date('Y-m-d H:i:s')
        );

        $updated = $this->Medicine_model->update_medicine($id, $medicine_data);

        if ($updated) {
            $this->session->set_flashdata('success', 'Medicine "' . html_escape($medicine_data['medicine_name']) . '" updated successfully!');
            redirect('medicines');
        } else {
            $this->session->set_flashdata('error', 'Failed to update medicine.');
            $this->edit($id);
        }
    }

    /**
     * Medicine Details Page (Show)
     *
     * @param int $id
     */
    public function show($id) {
        $medicine = $this->Medicine_model->get_medicine_by_id($id);
        if (!$medicine) {
            $this->session->set_flashdata('error', 'Medicine record not found.');
            redirect('medicines');
            return;
        }

        $data = array(
            'page_title'   => $medicine->medicine_name . ' - Medicine Details',
            'active_menu'  => 'medicines',
            'breadcrumbs'  => array(
                'Medicines' => 'medicines',
                $medicine->medicine_name => ''
            ),
            'medicine'     => $medicine
        );

        $this->render_view('medicines/show', $data);
    }

    /**
     * Alias for show($id) / view($id)
     */
    public function view($id) {
        $this->show($id);
    }

    /**
     * Delete Medicine
     *
     * @param int $id
     */
    public function delete($id) {
        $medicine = $this->Medicine_model->get_medicine_by_id($id);
        if (!$medicine) {
            $this->session->set_flashdata('error', 'Medicine not found or already deleted.');
            redirect('medicines');
            return;
        }

        $deleted = $this->Medicine_model->delete_medicine($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Medicine "' . html_escape($medicine->medicine_name) . '" deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete medicine record.');
        }

        redirect('medicines');
    }

    /**
     * Setup Server-Side Validation Rules with Custom Callbacks
     *
     * @param bool $is_create Whether validating a create or update action
     */
    private function _set_validation_rules($is_create = TRUE) {
        $this->form_validation->set_rules('medicine_name', 'Medicine Name', 'trim|required|min_length[2]|max_length[150]', array(
            'required' => 'Please enter the %s.'
        ));

        // Business Rule: Category must exist
        $this->form_validation->set_rules('category_id', 'Category', 'trim|required|numeric|callback_validate_category_exists', array(
            'required' => 'Please select a %s.'
        ));

        // Business Rule: Supplier must exist if provided
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'trim|numeric|callback_validate_supplier_exists');

        // Business Rule: Price must be greater than 0
        $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric|greater_than[0]', array(
            'required'     => 'Please enter the %s.',
            'greater_than' => 'Price must be greater than 0.'
        ));

        // Business Rule: Stock cannot be negative
        $this->form_validation->set_rules('stock_quantity', 'Stock Quantity', 'trim|required|is_natural', array(
            'required'   => 'Please enter the %s.',
            'is_natural' => 'Stock quantity cannot be negative.'
        ));

        // Business Rule: Expiry date cannot be in the past while creating
        if ($is_create) {
            $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'trim|required|callback_validate_future_expiry', array(
                'required' => 'Please select the %s.'
            ));
        } else {
            $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'trim|required', array(
                'required' => 'Please select the %s.'
            ));
        }

        $this->form_validation->set_rules('status', 'Status', 'trim|in_list[active,inactive]');
    }

    /**
     * Custom Validation Callback: Expiry date cannot be in the past
     *
     * @param string $date
     * @return bool
     */
    public function validate_future_expiry($date) {
        if (empty($date)) return FALSE;

        $expiry_timestamp = strtotime($date);
        $today_timestamp = strtotime(date('Y-m-d'));

        if ($expiry_timestamp < $today_timestamp) {
            $this->form_validation->set_message('validate_future_expiry', 'Expiry date cannot be in the past while creating.');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Custom Validation Callback: Category must exist
     *
     * @param int $category_id
     * @return bool
     */
    public function validate_category_exists($category_id) {
        if (empty($category_id) || !$this->Medicine_model->category_exists($category_id)) {
            $this->form_validation->set_message('validate_category_exists', 'The selected Category does not exist.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Custom Validation Callback: Supplier must exist if selected
     *
     * @param int|null $supplier_id
     * @return bool
     */
    public function validate_supplier_exists($supplier_id) {
        if (!empty($supplier_id) && !$this->Medicine_model->supplier_exists($supplier_id)) {
            $this->form_validation->set_message('validate_supplier_exists', 'The selected Supplier does not exist.');
            return FALSE;
        }
        return TRUE;
    }
}
