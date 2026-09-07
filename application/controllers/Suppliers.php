<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Suppliers Controller
 * Handles Supplier Management Module.
 *
 * NOTE:
 * This is only the controller skeleton.
 * Database operations will be added in Supplier_model.php.
 */
class Suppliers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load Supplier Model (we'll create it next)
        $this->load->model('Supplier_model');
        $this->load->library('form_validation');

        // Used for sidebar active menu.
        $this->active_menu = 'suppliers';
    }

    /**
     * Supplier Listing Page
     */
    public function index()
{
    $data['page_title'] = 'Supplier Management';

    // Search keyword (optional for now)
    $search = $this->input->get('search');

    // Fetch suppliers
    $data['suppliers'] = $this->Supplier_model->get_suppliers(100, 0, $search);

    $data['search'] = $search;

    $this->render_view('suppliers/index', $data);
}


/**
 * Show Create Supplier Page
 */
public function create()
{
    $data['page_title'] = 'Add New Supplier';

    $this->render_view('suppliers/create', $data);
}

/**
 * Store New Supplier
 */
public function store()
{
    // Validation Rules
    $this->form_validation->set_rules('name', 'Supplier Name', 'required|trim');
    $this->form_validation->set_rules('contact_person', 'Contact Person', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
    $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
    $this->form_validation->set_rules('address', 'Address', 'required|trim');
    $this->form_validation->set_rules('status', 'Status', 'required');

    // If validation fails
    if ($this->form_validation->run() == FALSE) {
        return $this->create();
    }

    // Check duplicate email
    if (!$this->Supplier_model->is_email_unique($this->input->post('email'))) {

        $this->session->set_flashdata('error', 'Supplier email already exists.');

        return redirect('suppliers/create');
    }

    // Prepare data
    $supplierData = [
        'name'           => $this->input->post('name', TRUE),
        'contact_person' => $this->input->post('contact_person', TRUE),
        'email'          => $this->input->post('email', TRUE),
        'phone'          => $this->input->post('phone', TRUE),
        'address'        => $this->input->post('address', TRUE),
        'status'         => $this->input->post('status', TRUE)
    ];

    // Save supplier
    if ($this->Supplier_model->create_supplier($supplierData)) {

        $this->session->set_flashdata('success', 'Supplier added successfully.');

    } else {

        $this->session->set_flashdata('error', 'Unable to save supplier.');

    }

    redirect('suppliers');
}

/**
 * View Supplier Details
 */
public function view($id)
{
    $supplier = $this->Supplier_model->get_supplier_by_id($id);

    if (!$supplier) {
        $this->session->set_flashdata('error', 'Supplier not found.');
        return redirect('suppliers');
    }

    $data['page_title'] = 'Supplier Details';
    $data['supplier'] = $supplier;

    $this->render_view('suppliers/show', $data);
}

/**
 * Edit Supplier Page
 */
public function edit($id)
{
    $supplier = $this->Supplier_model->get_supplier_by_id($id);

    if (!$supplier) {
        $this->session->set_flashdata('error', 'Supplier not found.');
        return redirect('suppliers');
    }

    $data['page_title'] = 'Edit Supplier';
    $data['supplier'] = $supplier;

    $this->render_view('suppliers/edit', $data);
}

/**
 * Update Supplier
 */
public function update($id)
{
    $supplier = $this->Supplier_model->get_supplier_by_id($id);

    if (!$supplier) {
        $this->session->set_flashdata('error', 'Supplier not found.');
        return redirect('suppliers');
    }

    // Validation Rules
    $this->form_validation->set_rules('name', 'Supplier Name', 'required|trim');
    $this->form_validation->set_rules('contact_person', 'Contact Person', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
    $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
    $this->form_validation->set_rules('address', 'Address', 'required|trim');
    $this->form_validation->set_rules('status', 'Status', 'required');

    if ($this->form_validation->run() == FALSE) {

        $data['page_title'] = 'Edit Supplier';
        $data['supplier'] = $supplier;

        return $this->render_view('suppliers/edit', $data);
    }

    // Check duplicate email except current supplier
    if (!$this->Supplier_model->is_email_unique(
        $this->input->post('email'),
        $id
    )) {
        $this->session->set_flashdata('error', 'Email already exists.');
        return redirect('suppliers/edit/'.$id);
    }

    $supplierData = [
        'name'           => $this->input->post('name', TRUE),
        'contact_person' => $this->input->post('contact_person', TRUE),
        'email'          => $this->input->post('email', TRUE),
        'phone'          => $this->input->post('phone', TRUE),
        'address'        => $this->input->post('address', TRUE),
        'status'         => $this->input->post('status', TRUE)
    ];

    if ($this->Supplier_model->update_supplier($id, $supplierData)) {

        $this->session->set_flashdata('success', 'Supplier updated successfully.');

    } else {

        $this->session->set_flashdata('error', 'Failed to update supplier.');

    }

    redirect('suppliers');
}

}