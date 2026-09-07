<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Expiry Management Controller
 * Handles Expiry Alerts, Expired Medicines, 30-Day Watchlist, 7-Day Urgent Watchlist, and Date Comparisons
 *
 * @property Expiry_model $Expiry_model
 * @property CI_Pagination $pagination
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Expiry extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Expiry_model');
        $this->load->library('pagination');
        $this->load->helper(array('url', 'form', 'text'));

        $this->active_menu = 'expiry';
        $this->page_title = 'Expiry Management & Alerts';
    }

    /**
     * Unified Expiry Alert Hub (Overview with Filter Tabs)
     */
    public function index() {
        $filter = $this->input->get('filter', TRUE) ?: 'all';
        $search = $this->input->get('search', TRUE);

        $per_page = 10;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Expiry_model->count_all_expiry_medicines($search, $filter);

        // Pagination Configuration
        $config['base_url']             = base_url('expiry');
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

        $medicines = $this->Expiry_model->get_all_expiry_medicines($per_page, $offset, $search, $filter);
        $kpis = $this->Expiry_model->get_expiry_kpis();

        $data = array(
            'page_title'       => 'Expiry Management & Inventory Alerts',
            'active_menu'      => 'expiry',
            'breadcrumbs'      => array('Expiry Alerts' => ''),
            'medicines'        => $medicines,
            'kpis'             => $kpis,
            'current_filter'   => $filter,
            'search'           => $search,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('expiry/index', $data);
    }

    /**
     * Dedicated View: Expired Medicines (expiry_date < CURRENT_DATE)
     */
    public function expired() {
        $search = $this->input->get('search', TRUE);

        $per_page = 10;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Expiry_model->count_expired_medicines($search);

        $config['base_url']             = base_url('expiry/expired');
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
        $config['cur_tag_open']    = '<li class="page-item active" aria-current="page"><span class="page-link bg-rose-600 border-rose-600 text-white font-bold rounded-lg">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = array('class' => 'page-link rounded-lg text-slate-600 hover:text-rose-700 hover:bg-rose-50 border-slate-200');

        $this->pagination->initialize($config);

        $medicines = $this->Expiry_model->get_expired_medicines($per_page, $offset, $search);
        $kpis = $this->Expiry_model->get_expiry_kpis();

        $data = array(
            'page_title'       => 'Expired Medicines & Disposal Audit',
            'active_menu'      => 'expiry',
            'breadcrumbs'      => array(
                'Expiry Alerts' => 'expiry',
                'Expired Medicines' => ''
            ),
            'medicines'        => $medicines,
            'kpis'             => $kpis,
            'search'           => $search,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('expiry/expired', $data);
    }

    /**
     * Dedicated View: Expiring Within 30 Days
     */
    public function expiring_30_days() {
        $search = $this->input->get('search', TRUE);

        $per_page = 10;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Expiry_model->count_expiring_medicines(30, $search);

        $config['base_url']             = base_url('expiry/expiring-30-days');
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
        $config['cur_tag_open']    = '<li class="page-item active" aria-current="page"><span class="page-link bg-amber-500 border-amber-500 text-white font-bold rounded-lg">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = array('class' => 'page-link rounded-lg text-slate-600 hover:text-amber-700 hover:bg-amber-50 border-slate-200');

        $this->pagination->initialize($config);

        $medicines = $this->Expiry_model->get_expiring_medicines(30, $per_page, $offset, $search);
        $kpis = $this->Expiry_model->get_expiry_kpis();

        $data = array(
            'page_title'       => 'Medicines Expiring Within 30 Days',
            'active_menu'      => 'expiry',
            'breadcrumbs'      => array(
                'Expiry Alerts' => 'expiry',
                'Expiring in 30 Days' => ''
            ),
            'medicines'        => $medicines,
            'kpis'             => $kpis,
            'search'           => $search,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('expiry/expiring_30', $data);
    }

    /**
     * Dedicated View: Expiring Within 7 Days (Critical Urgency)
     */
    public function expiring_7_days() {
        $search = $this->input->get('search', TRUE);

        $per_page = 10;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Expiry_model->count_expiring_medicines(7, $search);

        $config['base_url']             = base_url('expiry/expiring-7-days');
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
        $config['cur_tag_open']    = '<li class="page-item active" aria-current="page"><span class="page-link bg-orange-600 border-orange-600 text-white font-bold rounded-lg">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = array('class' => 'page-link rounded-lg text-slate-600 hover:text-orange-700 hover:bg-orange-50 border-slate-200');

        $this->pagination->initialize($config);

        $medicines = $this->Expiry_model->get_expiring_medicines(7, $per_page, $offset, $search);
        $kpis = $this->Expiry_model->get_expiry_kpis();

        $data = array(
            'page_title'       => 'Critical Alerts: Medicines Expiring Within 7 Days',
            'active_menu'      => 'expiry',
            'breadcrumbs'      => array(
                'Expiry Alerts' => 'expiry',
                'Expiring in 7 Days' => ''
            ),
            'medicines'        => $medicines,
            'kpis'             => $kpis,
            'search'           => $search,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('expiry/expiring_7', $data);
    }
}
