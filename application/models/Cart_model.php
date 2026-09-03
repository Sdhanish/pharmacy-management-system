<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cart Model
 * Handles Customer Shopping Cart, Cart Items, Stock Validation, and Real-time Pricing Summary
 */
class Cart_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Retrieve active cart ID for user, or create one if none exists
     *
     * @param int $user_id
     * @return int Cart ID
     */
    public function get_or_create_active_cart($user_id) {
        try {
            $this->db->where('user_id', (int) $user_id);
            $this->db->where('status', 'active');
            $cart = $this->db->get('cart')->row();

            if ($cart) {
                return (int) $cart->id;
            }

            // Create active cart
            $data = array(
                'user_id'    => (int) $user_id,
                'session_id' => $this->session->session_id,
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('cart', $data);
            return (int) $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Cart_model get_or_create_active_cart error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get all cart items with joined medicine details and stock
     *
     * @param int $user_id
     * @return array
     */
    public function get_cart_items($user_id) {
        try {
            $cart_id = $this->get_or_create_active_cart($user_id);

            $this->db->select('
                ci.id as item_id,
                ci.cart_id,
                ci.medicine_id,
                ci.quantity,
                ci.unit_price,
                (ci.quantity * ci.unit_price) as subtotal,
                m.medicine_name,
                m.image_url,
                m.stock_quantity as available_stock,
                m.expiry_date,
                m.status as medicine_status,
                c.name as category_name
            ');
            $this->db->from('cart_items ci');
            $this->db->join('medicines m', 'm.id = ci.medicine_id', 'left');
            $this->db->join('categories c', 'c.id = m.category_id', 'left');
            $this->db->where('ci.cart_id', (int) $cart_id);
            $this->db->order_by('ci.id', 'DESC');

            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->result_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Cart_model get_cart_items error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Add medicine to cart with available stock validation
     *
     * @param int $user_id
     * @param int $medicine_id
     * @param int $quantity
     * @return array ['status' => bool, 'message' => string, 'cart_count' => int]
     */
    public function add_to_cart($user_id, $medicine_id, $quantity = 1) {
        try {
            $quantity = max(1, (int) $quantity);

            // 1. Fetch medicine & Validate Available Stock
            $medicine = $this->db->get_where('medicines', array('id' => (int) $medicine_id))->row();
            if (!$medicine) {
                return array('status' => FALSE, 'message' => 'Medicine not found in catalog.');
            }

            if ($medicine->status !== 'active') {
                return array('status' => FALSE, 'message' => 'This medicine is currently unavailable.');
            }

            $available_stock = (int) $medicine->stock_quantity;
            if ($available_stock <= 0) {
                return array('status' => FALSE, 'message' => 'Sorry, "' . html_escape($medicine->medicine_name) . '" is currently out of stock.');
            }

            $cart_id = $this->get_or_create_active_cart($user_id);

            // Check if item is already in cart
            $existing = $this->db->get_where('cart_items', array(
                'cart_id'     => $cart_id,
                'medicine_id' => (int) $medicine_id
            ))->row();

            if ($existing) {
                $new_qty = (int) $existing->quantity + $quantity;
                if ($new_qty > $available_stock) {
                    return array(
                        'status'  => FALSE,
                        'message' => 'Cannot add more. You have ' . $existing->quantity . ' in cart, and available stock is ' . $available_stock . ' units.'
                    );
                }

                $this->db->where('id', $existing->id);
                $this->db->update('cart_items', array(
                    'quantity'   => $new_qty,
                    'unit_price' => (float) $medicine->price,
                    'updated_at' => date('Y-m-d H:i:s')
                ));
            } else {
                if ($quantity > $available_stock) {
                    return array(
                        'status'  => FALSE,
                        'message' => 'Requested quantity (' . $quantity . ') exceeds available stock (' . $available_stock . ' units).'
                    );
                }

                $this->db->insert('cart_items', array(
                    'cart_id'     => $cart_id,
                    'medicine_id' => (int) $medicine_id,
                    'quantity'    => $quantity,
                    'unit_price'  => (float) $medicine->price,
                    'created_at'  => date('Y-m-d H:i:s')
                ));
            }

            $summary = $this->get_cart_summary($user_id);
            return array(
                'status'     => TRUE,
                'message'    => '"' . html_escape($medicine->medicine_name) . '" added to your cart!',
                'cart_count' => $summary['total_items'],
                'subtotal'   => $summary['subtotal']
            );
        } catch (Exception $e) {
            log_message('error', 'Cart_model add_to_cart error: ' . $e->getMessage());
            return array('status' => FALSE, 'message' => 'An error occurred while adding to cart.');
        }
    }

    /**
     * Update cart item quantity with stock validation
     *
     * @param int $item_id
     * @param int $quantity
     * @param int $user_id
     * @return array
     */
    public function update_quantity($item_id, $quantity, $user_id) {
        try {
            $quantity = (int) $quantity;
            $cart_id = $this->get_or_create_active_cart($user_id);

            // If quantity <= 0, remove item
            if ($quantity <= 0) {
                return $this->remove_item($item_id, $user_id);
            }

            $this->db->select('ci.*, m.stock_quantity as available_stock, m.medicine_name');
            $this->db->from('cart_items ci');
            $this->db->join('medicines m', 'm.id = ci.medicine_id');
            $this->db->where('ci.id', (int) $item_id);
            $this->db->where('ci.cart_id', (int) $cart_id);
            $item = $this->db->get()->row();

            if (!$item) {
                return array('status' => FALSE, 'message' => 'Cart item not found.');
            }

            $available_stock = (int) $item->available_stock;
            if ($quantity > $available_stock) {
                return array(
                    'status'  => FALSE,
                    'message' => 'Cannot set quantity to ' . $quantity . '. Only ' . $available_stock . ' units available in stock.'
                );
            }

            $this->db->where('id', (int) $item_id);
            $this->db->update('cart_items', array(
                'quantity'   => $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ));

            $summary = $this->get_cart_summary($user_id);
            return array(
                'status'      => TRUE,
                'message'     => 'Quantity updated successfully.',
                'item_qty'    => $quantity,
                'item_total'  => number_format($quantity * (float)$item->unit_price, 2),
                'total_items' => $summary['total_items'],
                'subtotal'    => $summary['subtotal'],
                'grand_total' => $summary['grand_total']
            );
        } catch (Exception $e) {
            log_message('error', 'Cart_model update_quantity error: ' . $e->getMessage());
            return array('status' => FALSE, 'message' => 'Failed to update quantity.');
        }
    }

    /**
     * Remove single item from cart
     *
     * @param int $item_id
     * @param int $user_id
     * @return array
     */
    public function remove_item($item_id, $user_id) {
        try {
            $cart_id = $this->get_or_create_active_cart($user_id);
            $this->db->where('id', (int) $item_id);
            $this->db->where('cart_id', (int) $cart_id);
            $this->db->delete('cart_items');

            $summary = $this->get_cart_summary($user_id);
            return array(
                'status'      => TRUE,
                'message'     => 'Item removed from cart.',
                'total_items' => $summary['total_items'],
                'subtotal'    => $summary['subtotal']
            );
        } catch (Exception $e) {
            log_message('error', 'Cart_model remove_item error: ' . $e->getMessage());
            return array('status' => FALSE, 'message' => 'Failed to remove item.');
        }
    }

    /**
     * Empty entire cart for user
     *
     * @param int $user_id
     * @return bool
     */
    public function empty_cart($user_id) {
        try {
            $cart_id = $this->get_or_create_active_cart($user_id);
            $this->db->where('cart_id', (int) $cart_id);
            return $this->db->delete('cart_items');
        } catch (Exception $e) {
            log_message('error', 'Cart_model empty_cart error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Get real-time cart summary and totals
     *
     * @param int $user_id
     * @return array
     */
    public function get_cart_summary($user_id) {
        try {
            $items = $this->get_cart_items($user_id);
            
            $total_items = 0;
            $subtotal = 0.00;

            foreach ($items as $item) {
                $total_items += (int) $item['quantity'];
                $subtotal += (int) $item['quantity'] * (float) $item['unit_price'];
            }

            $tax = round($subtotal * 0.05, 2); // 5% estimate
            $shipping = ($subtotal >= 50.00 || $subtotal === 0.00) ? 0.00 : 5.00;
            $grand_total = $subtotal + $tax + $shipping;

            return array(
                'total_items'  => $total_items,
                'unique_items' => count($items),
                'subtotal'     => $subtotal,
                'tax'          => $tax,
                'shipping'     => $shipping,
                'grand_total'  => $grand_total,
                'items'        => $items
            );
        } catch (Exception $e) {
            return array(
                'total_items'  => 0,
                'unique_items' => 0,
                'subtotal'     => 0.00,
                'tax'          => 0.00,
                'shipping'     => 0.00,
                'grand_total'  => 0.00,
                'items'        => array()
            );
        }
    }
}
