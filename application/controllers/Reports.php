<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reports & Analytics Controller
 * Generates Available Medicines, Low Stock, Expired, Expiring Soon, and Stock Activity Reports
 *
 * @property Report_model $Report_model
 * @property Category_model $Category_model
 * @property CI_Input $input
 * @property CI_Session $session
 */
class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Report_model', 'Category_model'));
        $this->load->helper(array('url', 'form', 'text'));

        $this->active_menu = 'reports';
        $this->page_title = 'Pharmacy Reports & Analytics';
    }

    /**
     * Master Report Hub & Interactive Report Generator
     */
    public function index() {
        $report_type      = $this->input->get('type', TRUE) ?: 'available';
        $category_id      = $this->input->get('category_id', TRUE);
        $search           = $this->input->get('search', TRUE);
        $start_date       = $this->input->get('start_date', TRUE);
        $end_date         = $this->input->get('end_date', TRUE);
        $threshold        = (int) ($this->input->get('threshold', TRUE) ?: 10);
        $days             = (int) ($this->input->get('days', TRUE) ?: 30);
        $transaction_type = $this->input->get('transaction_type', TRUE) ?: 'ALL';

        // Fetch data according to selected report type
        $report_data = array();
        $report_title = 'Available Medicines Inventory Report';

        switch ($report_type) {
            case 'low_stock':
                $report_title = 'Low Stock & Reorder Alert Report';
                $report_data = $this->Report_model->get_low_stock_report($category_id, $search, $threshold);
                break;

            case 'expired':
                $report_title = 'Expired Medicines & Financial Loss Report';
                $report_data = $this->Report_model->get_expired_medicines_report($category_id, $search, $start_date, $end_date);
                break;

            case 'expiring_soon':
                $report_title = 'Expiring Soon Medicines Watchlist (' . $days . ' Days)';
                $report_data = $this->Report_model->get_expiring_soon_report($category_id, $search, $days, $start_date, $end_date);
                break;

            case 'stock_activity':
                $report_title = 'Stock Activity & Audit Ledger Report';
                $report_data = $this->Report_model->get_stock_activity_report($category_id, $search, $start_date, $end_date, $transaction_type);
                break;

            case 'available':
            default:
                $report_type = 'available';
                $report_title = 'Available In-Stock Medicines Inventory Report';
                $report_data = $this->Report_model->get_available_medicines_report($category_id, $search, $start_date, $end_date);
                break;
        }

        $categories = $this->Category_model->get_active_categories();
        $overview = $this->Report_model->get_reports_overview_summary();

        $data = array(
            'page_title'       => $report_title,
            'active_menu'      => 'reports',
            'breadcrumbs'      => array(
                'Reports' => 'reports',
                $report_title => ''
            ),
            'report_type'      => $report_type,
            'report_title'     => $report_title,
            'report_data'      => $report_data,
            'categories'       => $categories,
            'overview'         => $overview,
            'category_id'      => $category_id,
            'search'           => $search,
            'start_date'       => $start_date,
            'end_date'         => $end_date,
            'threshold'        => $threshold,
            'days'             => $days,
            'transaction_type' => $transaction_type,
            'generated_at'     => date('d M Y, h:i A')
        );

        $this->render_view('reports/index', $data);
    }

    /**
     * Shortcut: Available Medicines Report
     */
    public function available() {
        $_GET['type'] = 'available';
        $this->index();
    }

    /**
     * Shortcut: Low Stock Report
     */
    public function low_stock() {
        $_GET['type'] = 'low_stock';
        $this->index();
    }

    /**
     * Shortcut: Expired Medicines Report
     */
    public function expired() {
        $_GET['type'] = 'expired';
        $this->index();
    }

    /**
     * Shortcut: Expiring Soon Report
     */
    public function expiring_soon() {
        $_GET['type'] = 'expiring_soon';
        $this->index();
    }

    /**
     * Shortcut: Stock Activity Report
     */
    public function stock_activity() {
        $_GET['type'] = 'stock_activity';
        $this->index();
    }
}
