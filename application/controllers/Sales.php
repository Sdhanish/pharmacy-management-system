<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sales & Customer Purchases Controller
 * Handles Multi-Medicine Sales Dispensing, Customer Purchase Invoices, Stock Deductions, and Ledger Auditing
 *
 * @property Sale_model $Sale_model
 * @property Medicine_model $Medicine_model
 * @property Customer_model $Customer_model
 * @property CI_Form_validation $form_validation
 * @property CI_Pagination $pagination
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Sales extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Sale_model', 'Medicine_model', 'Customer_model'));
        $this->load->library(array('form_validation', 'pagination'));
        $this->load->helper(array('url', 'form'));

        $this->active_menu = 'sales';
        $this->page_title = 'Customer Purchases & Sales';
    }

    /**
     * Display Paginated List of Sales Transactions
     */
    public function index() {
        $search = $this->input->get('search', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);
        $payment_status = $this->input->get('payment_status', TRUE);
        $start_date = $this->input->get('start_date', TRUE);
        $end_date = $this->input->get('end_date', TRUE);

        $per_page = 10;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Sale_model->count_sales($search, $customer_id, $payment_status, $start_date, $end_date);

        // Configure Pagination
        $config['base_url']             = base_url('sales');
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

        $sales = $this->Sale_model->get_sales($per_page, $offset, $search, $customer_id, $payment_status, $start_date, $end_date);
        $customers = $this->Sale_model->get_active_customers();
        $metrics = $this->Sale_model->get_sales_metrics();

        $data = array(
            'page_title'       => 'Customer Purchases & Sales',
            'active_menu'      => 'sales',
            'breadcrumbs'      => array('Customer Purchases' => ''),
            'sales'            => $sales,
            'customers'        => $customers,
            'metrics'          => $metrics,
            'search'           => $search,
            'customer_id'      => $customer_id,
            'payment_status'   => $payment_status,
            'start_date'       => $start_date,
            'end_date'         => $end_date,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('sales/index', $data);
    }

    /**
     * Display Record Customer Purchase / Multi-Medicine POS Screen
     */
    public function create() {
        $medicines = $this->Sale_model->get_medicines_for_sale();
        $customers = $this->Sale_model->get_active_customers();
        $suggested_invoice = $this->Sale_model->generate_invoice_no();

        $selected_customer_id = $this->input->get('customer_id', TRUE);

        $data = array(
            'page_title'           => 'Record Customer Purchase (Bulk Sale)',
            'active_menu'          => 'sales',
            'breadcrumbs'          => array(
                'Customer Purchases' => 'sales',
                'New Sale / Dispense' => ''
            ),
            'medicines'            => $medicines,
            'customers'            => $customers,
            'suggested_invoice'    => $suggested_invoice,
            'selected_customer_id' => $selected_customer_id
        );

        $this->render_view('sales/create', $data);
    }

    /**
     * Store Customer Purchase, Atomically Deduct Stock, and Record in Stock History
     */
    public function store() {
        // Validate Primary Sale Headers
        $customer_id = $this->input->post('customer_id', TRUE);
        $customer_name = trim($this->input->post('customer_name', TRUE));
        $customer_phone = trim($this->input->post('customer_phone', TRUE));
        $payment_method = $this->input->post('payment_method', TRUE) ?: 'cash';
        $payment_status = $this->input->post('payment_status', TRUE) ?: 'paid';
        $sale_date = $this->input->post('sale_date', TRUE) ?: date('Y-m-d');
        $discount = (float) ($this->input->post('discount', TRUE) ?: 0.00);
        $tax = (float) ($this->input->post('tax', TRUE) ?: 0.00);
        $notes = trim($this->input->post('notes', TRUE));
        $invoice_no = trim($this->input->post('invoice_no', TRUE)) ?: $this->Sale_model->generate_invoice_no();

        // If customer_id is provided, auto-fill details from customer profile if missing
        if (!empty($customer_id)) {
            $cust_record = $this->Customer_model->get_customer_by_id($customer_id);
            if ($cust_record) {
                if (empty($customer_name)) {
                    $customer_name = $cust_record->name;
                }
                if (empty($customer_phone)) {
                    $customer_phone = $cust_record->phone;
                }
            }
        }

        if (empty($customer_name)) {
            $this->session->set_flashdata('error', 'Please provide or select a customer name.');
            redirect('sales/create');
            return;
        }

        // Parse Multi-Medicine Items
        $med_ids = $this->input->post('medicine_id', TRUE);
        $quantities = $this->input->post('quantity', TRUE);
        $unit_prices = $this->input->post('unit_price', TRUE);

        if (empty($med_ids) || !is_array($med_ids)) {
            $this->session->set_flashdata('error', 'Please select at least one medicine to record customer purchase.');
            redirect('sales/create');
            return;
        }

        $items = array();
        $subtotal = 0.00;

        foreach ($med_ids as $index => $mid) {
            $mid = (int) $mid;
            $qty = isset($quantities[$index]) ? (int) $quantities[$index] : 0;
            $price = isset($unit_prices[$index]) ? (float) $unit_prices[$index] : 0.00;

            if ($mid > 0 && $qty > 0) {
                $line_total = $qty * $price;
                $subtotal += $line_total;

                $items[] = array(
                    'medicine_id' => $mid,
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'total_price' => $line_total
                );
            }
        }

        if (empty($items)) {
            $this->session->set_flashdata('error', 'Please enter valid quantities (> 0) for the selected medicines.');
            redirect('sales/create');
            return;
        }

        $total_amount = max(0.00, ($subtotal - $discount + $tax));

        $sale_data = array(
            'invoice_no'     => $invoice_no,
            'customer_id'    => !empty($customer_id) ? (int)$customer_id : NULL,
            'customer_name'  => $customer_name,
            'customer_phone' => $customer_phone,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax'            => $tax,
            'total_amount'   => $total_amount,
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'notes'          => $notes,
            'created_by'     => $this->session->userdata('user_id') ?: 1,
            'sale_date'      => $sale_date
        );

        $result = $this->Sale_model->create_sale($sale_data, $items);

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
            redirect('sales/invoice/' . $result['sale_id']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
            redirect('sales/create');
        }
    }

    /**
     * Display Detailed & Printable Customer Invoice / Receipt
     *
     * @param int $id
     */
    public function invoice($id) {
        $sale = $this->Sale_model->get_sale_by_id($id);
        if (!$sale) {
            $this->session->set_flashdata('error', 'Sale invoice record not found.');
            redirect('sales');
            return;
        }

        $items = $this->Sale_model->get_sale_items($id);

        $data = array(
            'page_title'   => 'Invoice #' . $sale->invoice_no,
            'active_menu'  => 'sales',
            'breadcrumbs'  => array(
                'Customer Purchases' => 'sales',
                'Invoice #' . $sale->invoice_no => ''
            ),
            'sale'         => $sale,
            'items'        => $items
        );

        $this->render_view('sales/invoice', $data);
    }

    /**
     * Delete / Cancel Sale and Automatically Restore Medicine Stock
     *
     * @param int $id
     */
    public function delete($id) {
        $sale = $this->Sale_model->get_sale_by_id($id);
        if (!$sale) {
            $this->session->set_flashdata('error', 'Sale record not found.');
            redirect('sales');
            return;
        }

        $deleted = $this->Sale_model->delete_sale($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Sale invoice #' . html_escape($sale->invoice_no) . ' cancelled and medicine stock has been restored to inventory.');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel sale transaction.');
        }

        redirect('sales');
    }
}
