<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles the signed-in user's profile settings.
 *
 * @property Auth_model $Auth_model
 * @property CI_Session $session
 */
class Profile extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->active_menu = '';
        $this->page_title = 'Profile Settings';
        $this->breadcrumbs = array('Profile Settings' => '');
    }

    public function index() {
        $user = $this->Auth_model->get_user_by_id($this->session->userdata('user_id'));
        if (!$user) {
            $this->session->set_flashdata('error', 'Your profile could not be found.');
            redirect('dashboard');
            return;
        }

        $this->render_view('profile/index', array(
            'user' => $user,
            'page_title' => 'Profile Settings',
            'breadcrumbs' => array('Profile Settings' => '')
        ));
    }

    public function update() {
        if ($this->input->method() !== 'post') {
            redirect('profile');
            return;
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');
        $this->form_validation->set_rules('password', 'New password', 'trim|min_length[6]');
        $this->form_validation->set_rules('password_confirmation', 'Confirm new password', 'matches[password]');

        if (!$this->form_validation->run()) {
            $this->index();
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $email = trim($this->input->post('email', TRUE));
        $existing = $this->Auth_model->get_user_by_id($user_id);
        $email_owner = $this->db->get_where('users', array('email' => $email))->row();

        if ($email_owner && (int) $email_owner->id !== (int) $user_id) {
            $this->session->set_flashdata('error', 'That email address is already in use.');
            redirect('profile');
            return;
        }

        $data = array(
            'name' => trim($this->input->post('name', TRUE)),
            'email' => $email
        );
        $password = $this->input->post('password', TRUE);
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($this->Auth_model->update_profile($user_id, $data)) {
            $this->session->set_userdata(array(
                'user_name' => $data['name'],
                'user_email' => $data['email']
            ));
            $this->session->set_flashdata('success', 'Profile settings updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Unable to update profile settings.');
        }

        redirect('profile');
    }
}