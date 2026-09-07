<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Controller
 * Handles user login, session management, and logout
 *
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Config $config
 * @property CI_Security $security
 * @property Auth_model $Auth_model
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
        $this->load->helper(array('url', 'form'));
    }

    /**
     * Display Login Page or Process Login Form Submission
     */
public function login()
{
    // Already logged in
    if ($this->session->userdata('logged_in')) {
        redirect('dashboard');
    }

    if ($this->input->method() === 'post') {

        $this->form_validation->set_rules('identity','Email','required');
        $this->form_validation->set_rules('password','Password','required');

        if ($this->form_validation->run()) {

            $user = $this->Auth_model->verify_credentials(
                $this->input->post('identity'),
                $this->input->post('password')
            );

            if ($user) {

                $this->session->set_userdata([
                    'user_id'   => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'user_avatar' => '',
                    'logged_in' => TRUE
                ]);

                redirect('dashboard');
            }

            $this->session->set_flashdata('error','Invalid credentials');
            redirect('login');
        }
    }

    $this->load->view('auth/login');
}

    /**
     * Terminate Session and Logout
     */
    public function logout() {
        $this->session->unset_userdata(array('user_id', 'user_name', 'user_email', 'user_role', 'user_avatar', 'logged_in', 'login_time'));
        $this->session->sess_destroy();
        redirect('welcome');
    }
}
