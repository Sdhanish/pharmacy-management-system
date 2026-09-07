<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller
 * Displays key pharmacy KPIs, inventory levels, expiring medicine alerts, and recent transaction activities
 *
 * @property Dashboard_model $Dashboard_model
 * @property CI_Session $session
 */
class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->active_menu = 'dashboard';
        $this->page_title = 'Dashboard Overview & Analytics';
        $this->breadcrumbs = array(
            'Dashboard' => ''
        );
    }

    /**
     * Display Main Dashboard Overview Page
     */
    public function index() {
        // Fetch real-time metrics and activity history from Dashboard Model
        $summary = $this->Dashboard_model->get_dashboard_summary();

        $data = array(
            'page_title'          => 'Dashboard Overview',
            'active_menu'         => 'dashboard',
            'breadcrumbs'         => array('Overview' => ''),
            'stats'               => array(
                'total_medicines'      => $summary['total_medicines'],
                'total_stock_quantity' => $summary['total_stock_quantity'],
                'low_stock_medicines'  => $summary['low_stock_medicines'],
                'expired_medicines'    => $summary['expired_medicines'],
                'expiring_soon'        => $summary['expiring_soon'],
                'expiring_7_days'      => $summary['expiring_7_days'],
            ),
            'recent_activities'     => $summary['recent_activities'],
            'low_stock_items'       => $summary['low_stock_items'],
            'expiring_soon_items'   => $summary['expiring_soon_items'],
        );

        $this->render_view('dashboard/index', $data);
    }
}
