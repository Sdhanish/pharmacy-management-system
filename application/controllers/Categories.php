<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->active_menu = 'categories';
        $this->page_title = 'Medicine Categories';
    }

    public function index() {
        $data = array(
            'page_title'  => 'Medicine Categories',
            'active_menu' => 'categories',
            'breadcrumbs' => array(
                'Categories' => ''
            ),
            'categories' => array(
                array('name' => 'Antibiotics', 'count' => 184, 'icon' => 'fa-shield-virus', 'color' => 'emerald'),
                array('name' => 'Analgesics & Pain Relief', 'count' => 96, 'icon' => 'fa-capsules', 'color' => 'blue'),
                array('name' => 'Cardiovascular', 'count' => 142, 'icon' => 'fa-heart-pulse', 'color' => 'rose'),
                array('name' => 'Respiratory & Asthma', 'count' => 64, 'icon' => 'fa-lungs', 'color' => 'cyan'),
                array('name' => 'Vitamins & Supplements', 'count' => 210, 'icon' => 'fa-apple-whole', 'color' => 'amber'),
                array('name' => 'Gastrointestinal', 'count' => 88, 'icon' => 'fa-bacterium', 'color' => 'purple'),
            )
        );

        $this->render_view('categories/index', $data);
    }
}
