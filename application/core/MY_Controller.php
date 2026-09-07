<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Application Controller
 * Handles common layout rendering and view variables
 *
 * @property CI_Session $session
 * @property CI_Router $router
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_URI $uri
 */
class MY_Controller extends CI_Controller {

    public $page_title = 'PharmaCare - Pharmacy Management System';
    public $active_menu = 'dashboard';
    public $breadcrumbs = array();
    public $current_user = array();
    public $inventory_notifications = array();

    public function __construct() {
        parent::__construct();

        // Check if user is authenticated
        $is_logged_in = $this->session->userdata('logged_in');
        $current_controller = strtolower($this->router->fetch_class());

        // Prevent unauthenticated users from accessing protected pages
        if ($is_logged_in !== TRUE && $current_controller !== 'auth') {
            $this->session->set_flashdata('error', 'Please sign in to access the Pharmacy Management System.');
            redirect('login');
            return;
        }

        // Populate current user data from active session
        if ($is_logged_in === TRUE) {
            $this->current_user = array(
                'id'     => $this->session->userdata('user_id'),
                'name'   => $this->session->userdata('user_name'),
                'email'  => $this->session->userdata('user_email'),
                'role'   => $this->session->userdata('user_role'),
                'avatar' => ''
            );

            $this->load->model('Dashboard_model');
            $this->inventory_notifications = $this->Dashboard_model->get_inventory_notifications(5);
        }
    }

    /**
     * Render a view wrapped in the Master Layout
     *
     * @param string $view_path Path to the content view
     * @param array $data Data array passed to views
     * @param bool $return Whether to return as string or output directly
     * @return string|void
     */
    protected function render_view($view_path, $data = array(), $return = FALSE) {
        $view_data = array_merge($data, array(
            'content_view'  => $view_path,
            'page_title'    => isset($data['page_title']) ? $data['page_title'] : $this->page_title,
            'active_menu'   => isset($data['active_menu']) ? $data['active_menu'] : $this->active_menu,
            'breadcrumbs'   => isset($data['breadcrumbs']) ? $data['breadcrumbs'] : $this->breadcrumbs,
            'current_user'  => isset($data['current_user']) ? $data['current_user'] : $this->current_user,
            'inventory_notifications' => isset($data['inventory_notifications']) ? $data['inventory_notifications'] : $this->inventory_notifications,
        ));

        return $this->load->view('layouts/master', $view_data, $return);
    }
}
