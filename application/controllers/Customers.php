<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer Management Controller
 * Handles Customer List, Search, Pagination, Edit, and Delete
 *
 * @property Customer_model $Customer_model
 * @property CI_Form_validation $form_validation
 * @property CI_Pagination $pagination
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Customers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library(array('form_validation', 'pagination'));
        $this->load->helper(array('url', 'form', 'text'));

        $this->active_menu = 'customers';
        $this->page_title = 'Customer Management';
    }

    /**
     * Customer List Page with Search, Status Filter, and Pagination
     */
    public function index() {
        $search = $this->input->get('search', TRUE);
        $status = $this->input->get('status', TRUE);

        $per_page = 8;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Customer_model->count_customers($search, $status);

        // Configure Pagination
        $config['base_url']             = base_url('customers');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;
        $config['reuse_query_string']   = TRUE;

        // Bootstrap 5 & Tailwind Pagination Styling
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

        $customers = $this->Customer_model->get_customers($per_page, $offset, $search, $status);

        $data = array(
            'page_title'       => 'Customer Management',
            'active_menu'      => 'customers',
            'breadcrumbs'      => array('Customers' => ''),
            'customers'        => $customers,
            'search'           => $search,
            'status'           => $status,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('customers/index', $data);
    }

    /**
     * Customer Edit Page
     *
     * @param int $id
     */
    public function edit($id) {
        $customer = $this->Customer_model->get_customer_by_id($id);
        if (!$customer) {
            $this->session->set_flashdata('error', 'Customer record not found.');
            redirect('customers');
            return;
        }

        $data = array(
            'page_title'   => 'Edit Customer: ' . $customer->name,
            'active_menu'  => 'customers',
            'breadcrumbs'  => array(
                'Customers' => 'customers',
                'Edit ' . $customer->name => ''
            ),
            'customer'     => $customer
        );

        $this->render_view('customers/edit', $data);
    }

    /**
     * Update Customer Record
     *
     * @param int $id
     */
    public function update($id) {
        $customer = $this->Customer_model->get_customer_by_id($id);
        if (!$customer) {
            $this->session->set_flashdata('error', 'Customer record not found.');
            redirect('customers');
            return;
        }

        $this->_set_validation_rules($id);

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $customer_data = array(
            'name'       => trim($this->input->post('name', TRUE)),
            'email'      => trim($this->input->post('email', TRUE)),
            'phone'      => trim($this->input->post('phone', TRUE)),
            'address'    => trim($this->input->post('address', TRUE)),
            'status'     => $this->input->post('status') ? $this->input->post('status') : 'active',
            'updated_at' => date('Y-m-d H:i:s')
        );

        $updated = $this->Customer_model->update_customer($id, $customer_data);

        if ($updated) {
            $this->session->set_flashdata('success', 'Customer profile "' . html_escape($customer_data['name']) . '" updated successfully!');
            redirect('customers');
        } else {
            $this->session->set_flashdata('error', 'Failed to update customer profile.');
            $this->edit($id);
        }
    }

    /**
     * Delete Customer
     *
     * @param int $id
     */
    public function delete($id) {
        $customer = $this->Customer_model->get_customer_by_id($id);
        if (!$customer) {
            $this->session->set_flashdata('error', 'Customer record not found or cannot be deleted.');
            redirect('customers');
            return;
        }

        $deleted = $this->Customer_model->delete_customer($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Customer account "' . html_escape($customer->name) . '" deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete customer.');
        }

        redirect('customers');
    }

    /**
     * Form Validation Rules
     *
     * @param int|null $exclude_id
     */
    private function _set_validation_rules($exclude_id = null) {
        $this->form_validation->set_rules('name', 'Customer Name', 'trim|required|min_length[2]|max_length[100]', array(
            'required' => 'Please enter the %s.'
        ));
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|callback_validate_email_unique[' . $exclude_id . ']', array(
            'required'    => 'Please enter the %s.',
            'valid_email' => 'Please enter a valid email address.'
        ));
        $this->form_validation->set_rules('phone', 'Phone Number', 'trim|max_length[30]');
        $this->form_validation->set_rules('address', 'Physical Address', 'trim');
        $this->form_validation->set_rules('status', 'Account Status', 'trim|in_list[active,inactive]');
    }

    /**
     * Custom Validation Callback: Unique Email
     *
     * @param string $email
     * @param int|null $exclude_id
     * @return bool
     */
    public function validate_email_unique($email, $exclude_id = null) {
        $is_unique = $this->Customer_model->is_email_unique($email, $exclude_id);
        if (!$is_unique) {
            $this->form_validation->set_message('validate_email_unique', 'This email address is already registered to another user.');
            return FALSE;
        }
        return TRUE;
    }
}
