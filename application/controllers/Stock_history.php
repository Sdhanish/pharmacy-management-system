<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stock History & Movement Audit Controller
 *
 * @property Stock_model $Stock_model
 * @property CI_Pagination $pagination
 * @property CI_Input $input
 */
class Stock_history extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Stock_model');
        $this->load->library('pagination');
        $this->load->helper(array('url', 'form'));

        $this->active_menu = 'stock_history';
        $this->page_title = 'Stock History & Audit Ledger';
    }

    /**
     * Display Stock Movements History
     */
    public function index() {
        $search = $this->input->get('search', TRUE);

        $per_page = 15;
        $page = (int) $this->input->get('page');
        $offset = ($page > 0) ? ($page - 1) * $per_page : 0;

        $total_rows = $this->Stock_model->count_stock_history($search);

        // Configure Pagination
        $config['base_url']             = base_url('stock-history');
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

        $history = $this->Stock_model->get_stock_history($per_page, $offset, $search);

        $data = array(
            'page_title'       => 'Stock History & Movement Audit',
            'active_menu'      => 'stock_history',
            'breadcrumbs'      => array('Stock History' => ''),
            'history'          => $history,
            'search'           => $search,
            'total_rows'       => $total_rows,
            'pagination_links' => $this->pagination->create_links(),
            'offset'           => $offset,
            'per_page'         => $per_page
        );

        $this->render_view('stock_history/index', $data);
    }
}
