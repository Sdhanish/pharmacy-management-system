<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->active_menu = 'reports';
        $this->page_title = 'Pharmacy Reports & Analytics';
    }

    public function index() {
        $data = array(
            'page_title'  => 'Reports & Analytics',
            'active_menu' => 'reports',
            'breadcrumbs' => array(
                'Reports' => ''
            )
        );

        $this->render_view('reports/index', $data);
    }
}
