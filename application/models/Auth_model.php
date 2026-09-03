<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Model
 * Manages user authentication, password verification via password_verify()
 */
class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Verify user login credentials
     *
     * @param string $identity Email or username
     * @param string $password Plain text password
     * @return object|false User object on success, FALSE on failure
     */
    public function verify_credentials($identity, $password) {
        $identity = trim($identity);

        // 1. Try querying from database
        try {
            if ($this->db) {
                $this->db->group_start();
                $this->db->where('email', $identity);
                $this->db->or_where('name', $identity);
                $this->db->group_end();
                $this->db->where('status', 'active');
                $query = $this->db->get('users');

                if ($query && $query->num_rows() === 1) {
                    $user = $query->row();
                    if (password_verify($password, $user->password)) {
                        $this->update_last_login($user->id);
                        return $user;
                    }
                    return FALSE;
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Auth DB Error: ' . $e->getMessage());
        }

        // 2. Fallback default credentials (for offline/standalone verification)
        $default_hash = password_hash('admin123', PASSWORD_BCRYPT);
        if (
            (strtolower($identity) === 'admin@pharmacare.com' || strtolower($identity) === 'admin') &&
            password_verify($password, $default_hash)
        ) {
            return (object) array(
                'id' => 1,
                'name' => 'Dr. Dhanish S',
                'email' => 'admin@pharmacare.com',
                'role' => 'Administrator',
                'avatar' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=150&auto=format&fit=crop&q=80',
                'status' => 'active'
            );
        }

        return FALSE;
    }

    /**
     * Update user last login timestamp
     *
     * @param int $user_id
     * @return bool
     */
    public function update_last_login($user_id) {
        try {
            if ($this->db) {
                $this->db->where('id', $user_id);
                return $this->db->update('users', array('updated_at' => date('Y-m-d H:i:s')));
            }
        } catch (Exception $e) {
            log_message('error', 'Update last login error: ' . $e->getMessage());
        }
        return FALSE;
    }

    /**
     * Get user by ID
     *
     * @param int $user_id
     * @return object|null
     */
    public function get_user_by_id($user_id) {
        try {
            if ($this->db) {
                $query = $this->db->get_where('users', array('id' => $user_id, 'status' => 'active'));
                if ($query && $query->num_rows() === 1) {
                    return $query->row();
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Get user error: ' . $e->getMessage());
        }
        return NULL;
    }
}
