<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stock Management Controller
 * Handles Stock Purchases, Auto Stock Increment, Auto Stock Reversal, and Current Stock Monitoring
 *
 * @property Stock_model $Stock_model
 * @property Medicine_model $Medicine_model
 * @property Supplier_model $Supplier_model
 * @property CI_Form_validation $form_validation
 * @property CI_Pagination $pagination
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Stock extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Stock_model', 'Medicine_model', 'Supplier_model'));
        $this->load->library(array('form_validation', 'pagination'));
        $this->load->helper(array('url', 'form'));

        $this->active_menu = 'stock';
        $this->page_title = 'Stock Management';
    }

    /**
     * Read Stock Purchases List with Current Stock Column, Search, and Pagination
     */
    public function index() {
        $search      = $this->input->get('search', TRUE);
        $supplier_id = $this->input->get('supplier_id', TRUE);

        $per_page = 10;
        $page     = (int) $this->input->get('page');
        $offset   = ($page > 0) ? ($page - 1) * $per_page : 0;

        // --- Purchase Orders Tab ---
        $total_rows = $this->Stock_model->count_purchases($search, $supplier_id);

        $config['base_url']             = base_url('stock');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;
        $config['reuse_query_string']   = TRUE;
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

        $purchases = $this->Stock_model->get_purchases($per_page, $offset, $search, $supplier_id);
        $suppliers = $this->Supplier_model->get_active_suppliers();

        // --- Inventory Overview Tab: ALL medicines with current stock ---
        $inv_search     = $this->input->get('inv_search', TRUE);
        $inv_total      = $this->Stock_model->count_medicine_inventory($inv_search);
        $inv_page       = (int) $this->input->get('inv_page');
        $inv_offset     = ($inv_page > 0) ? ($inv_page - 1) * $per_page : 0;
        $inventory_list = $this->Stock_model->get_medicine_inventory_overview($per_page, $inv_offset, $inv_search);

        $data = array(
            'page_title'       => 'Stock Management',
            'active_menu'      => 'stock',
            'breadcrumbs'      => array('Stock Management' => ''),
            'purchases'        => $purchases,
            'suppliers'        => $suppliers,
            'search'           => $search,
            'supplier_id'      => $supplier_id,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page,
            // Inventory overview
            'inventory_list'   => $inventory_list,
            'inv_total'        => $inv_total,
            'inv_search'       => $inv_search,
            'inv_offset'       => $inv_offset,
        );

        $this->render_view('stock/index', $data);
    }

    /**
     * Display Add Stock Purchase Form
     */
    public function create() {
        $data = array(
            'page_title'   => 'Add Stock Purchase',
            'active_menu'  => 'stock',
            'breadcrumbs'  => array(
                'Stock' => 'stock',
                'New Purchase' => ''
            ),
            'medicines'    => $this->Medicine_model->get_medicines(100, 0),
            'suppliers'    => $this->Supplier_model->get_active_suppliers(),
            'purchase'     => NULL
        );

        $this->render_view('stock/create', $data);
    }

    /**
     * Store New Stock Purchase & Automatically Increase Medicine Stock
     */
    public function store() {
        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        $purchase_data = array(
            'medicine_id'    => (int) $this->input->post('medicine_id', TRUE),
            'supplier_id'    => (int) $this->input->post('supplier_id', TRUE),
            'quantity'       => (int) $this->input->post('quantity', TRUE),
            'purchase_price' => (float) $this->input->post('purchase_price', TRUE),
            'purchase_date'  => $this->input->post('purchase_date', TRUE) ?: date('Y-m-d'),
            'notes'          => trim($this->input->post('notes', TRUE)),
            'created_at'     => date('Y-m-d H:i:s')
        );

        $purchase_id = $this->Stock_model->add_purchase($purchase_data);

        if ($purchase_id) {
            $this->session->set_flashdata('success', 'Stock purchase #' . $purchase_id . ' added! Medicine inventory increased by +' . $purchase_data['quantity'] . ' units.');
            redirect('stock');
        } else {
            $this->session->set_flashdata('error', 'Failed to record stock purchase. Please try again.');
            $this->create();
        }
    }

    /**
     * Display Edit Stock Purchase Form
     *
     * @param int $id
     */
    public function edit($id) {
        $purchase = $this->Stock_model->get_purchase_by_id($id);
        if (!$purchase) {
            $this->session->set_flashdata('error', 'Stock purchase record not found.');
            redirect('stock');
            return;
        }

        $data = array(
            'page_title'   => 'Edit Stock Purchase #' . $purchase->id,
            'active_menu'  => 'stock',
            'breadcrumbs'  => array(
                'Stock' => 'stock',
                'Edit Purchase #' . $purchase->id => ''
            ),
            'purchase'     => $purchase,
            'medicines'    => $this->Medicine_model->get_medicines(100, 0),
            'suppliers'    => $this->Supplier_model->get_active_suppliers()
        );

        $this->render_view('stock/edit', $data);
    }

    /**
     * Update Stock Purchase & Adjust Medicine Stock
     *
     * @param int $id
     */
    public function update($id) {
        $purchase = $this->Stock_model->get_purchase_by_id($id);
        if (!$purchase) {
            $this->session->set_flashdata('error', 'Stock purchase record not found.');
            redirect('stock');
            return;
        }

        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $purchase_data = array(
            'medicine_id'    => (int) $this->input->post('medicine_id', TRUE),
            'supplier_id'    => (int) $this->input->post('supplier_id', TRUE),
            'quantity'       => (int) $this->input->post('quantity', TRUE),
            'purchase_price' => (float) $this->input->post('purchase_price', TRUE),
            'purchase_date'  => $this->input->post('purchase_date', TRUE),
            'notes'          => trim($this->input->post('notes', TRUE)),
            'updated_at'     => date('Y-m-d H:i:s')
        );

        $updated = $this->Stock_model->update_purchase($id, $purchase_data);

        if ($updated) {
            $this->session->set_flashdata('success', 'Stock purchase #' . $id . ' updated and medicine inventory rebalanced successfully!');
            redirect('stock');
        } else {
            $this->session->set_flashdata('error', 'Failed to update stock purchase.');
            $this->edit($id);
        }
    }

    /**
     * Delete Stock Purchase & Automatically Reverse Medicine Stock
     *
     * @param int $id
     */
    public function delete($id) {
        $purchase = $this->Stock_model->get_purchase_by_id($id);
        if (!$purchase) {
            $this->session->set_flashdata('error', 'Purchase record not found.');
            redirect('stock');
            return;
        }

        $deleted = $this->Stock_model->delete_purchase($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Purchase #' . $id . ' deleted and medicine inventory reversed by -' . $purchase->quantity . ' units successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete purchase record.');
        }

        redirect('stock');
    }

    /**
     * Dedicated Stock Movement History View
     */
    public function history() {
        redirect('stock-history');
    }

    /**
     * Form Validation Rules
     */
    private function _set_validation_rules() {
        $this->form_validation->set_rules('medicine_id', 'Medicine', 'trim|required|numeric', array(
            'required' => 'Please select a %s.'
        ));
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'trim|required|numeric', array(
            'required' => 'Please select a %s.'
        ));
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|is_natural_no_zero', array(
            'required'              => 'Please enter the purchase %s.',
            'is_natural_no_zero'    => 'Quantity must be a positive integer greater than 0.'
        ));
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required|numeric|greater_than[0]', array(
            'required'     => 'Please enter the %s.',
            'greater_than' => 'Purchase price must be greater than 0.'
        ));
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'trim|required', array(
            'required' => 'Please choose the %s.'
        ));
    }
}
