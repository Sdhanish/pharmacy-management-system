<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shopping Cart Controller
 * Handles Cart View, Add to Cart, Stock Validation, Quantity Updates, Item Removal, and Navbar Mini Cart
 *
 * @property Cart_model $Cart_model
 * @property Medicine_model $Medicine_model
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Output $output
 */
class Cart extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Cart_model', 'Medicine_model'));
        $this->load->helper(array('url', 'form'));

        $this->active_menu = 'cart';
        $this->page_title = 'Shopping Cart';
    }

    /**
     * Display Shopping Cart Page
     */
    public function index() {
        $user_id = $this->session->userdata('user_id') ?: 1;
        $cart_summary = $this->Cart_model->get_cart_summary($user_id);

        $data = array(
            'page_title'   => 'Your Shopping Cart',
            'active_menu'  => 'cart',
            'breadcrumbs'  => array('Cart' => ''),
            'cart_items'   => $cart_summary['items'],
            'total_items'  => $cart_summary['total_items'],
            'unique_items' => $cart_summary['unique_items'],
            'subtotal'     => $cart_summary['subtotal'],
            'tax'          => $cart_summary['tax'],
            'shipping'     => $cart_summary['shipping'],
            'grand_total'  => $cart_summary['grand_total']
        );

        $this->render_view('cart/index', $data);
    }

    /**
     * Add Medicine to Cart (Supports standard POST and AJAX requests)
     */
    public function add() {
        $user_id = $this->session->userdata('user_id') ?: 1;
        $medicine_id = (int) $this->input->post('medicine_id', TRUE);
        $quantity = (int) ($this->input->post('quantity', TRUE) ?: 1);

        if (!$medicine_id) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_content_type('application/json')->set_output(json_encode([
                    'status' => FALSE, 'message' => 'Invalid medicine selected.'
                ]));
            }
            $this->session->set_flashdata('error', 'Invalid medicine selected.');
            redirect('medicines');
            return;
        }

        $result = $this->Cart_model->add_to_cart($user_id, $medicine_id, $quantity);

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('cart');
    }

    /**
     * Update Cart Item Quantity
     */
    public function update() {
        $user_id = $this->session->userdata('user_id') ?: 1;
        $item_id = (int) $this->input->post('item_id', TRUE);
        $quantity = (int) $this->input->post('quantity', TRUE);

        $result = $this->Cart_model->update_quantity($item_id, $quantity, $user_id);

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('cart');
    }

    /**
     * Remove Single Item from Cart
     *
     * @param int $item_id
     */
    public function remove($item_id) {
        $user_id = $this->session->userdata('user_id') ?: 1;
        $result = $this->Cart_model->remove_item($item_id, $user_id);

        if ($result['status']) {
            $this->session->set_flashdata('success', 'Item removed from your cart.');
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('cart');
    }

    /**
     * Empty Entire Shopping Cart
     */
    public function clear() {
        $user_id = $this->session->userdata('user_id') ?: 1;
        $this->Cart_model->empty_cart($user_id);
        $this->session->set_flashdata('success', 'Your shopping cart has been cleared.');
        redirect('cart');
    }

    /**
     * Get Mini Cart JSON for Navbar Live Updates
     */
    public function get_mini_cart_data() {
        $user_id = $this->session->userdata('user_id') ?: 1;
        $summary = $this->Cart_model->get_cart_summary($user_id);
        return $this->output->set_content_type('application/json')->set_output(json_encode($summary));
    }
}
