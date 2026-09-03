<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Shopping Cart</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs" id="cartItemCountBadge">
                <?php echo $total_items; ?> <?php echo ($total_items === 1) ? 'Item' : 'Items'; ?>
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Review prescription and OTC items, adjust quantities, and verify stock availability.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Continue Shopping</span>
        </a>
        <?php if (!empty($cart_items)): ?>
            <a href="<?php echo base_url('cart/clear'); ?>" class="btn btn-outline-danger text-xs sm:text-sm font-semibold rounded-xl px-3.5 py-2.5 flex items-center gap-1.5 text-decoration-none" onclick="return confirm('Are you sure you want to empty your entire cart?');">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Empty Cart</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($cart_items)): ?>
    <!-- MAIN CART LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- LEFT 2 COLS: CART ITEMS LIST -->
        <div class="lg:col-span-2 space-y-4">
            
            <div class="app-card p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
                        <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 pl-4">Medicine Item</th>
                                <th class="py-3.5 text-center">Unit Price</th>
                                <th class="py-3.5 text-center">Quantity</th>
                                <th class="py-3.5 text-end">Subtotal</th>
                                <th class="py-3.5 text-end pr-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($cart_items as $item): ?>
                                <?php
                                $avail_stock = (int)$item['available_stock'];
                                $item_subtotal = (int)$item['quantity'] * (float)$item['unit_price'];
                                $image_src = !empty($item['image_url']) ? $item['image_url'] : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=100&auto=format&fit=crop&q=80';
                                ?>
                                <tr id="cart-row-<?php echo $item['item_id']; ?>" class="hover:bg-slate-50/80 transition-colors">
                                    <!-- Medicine Thumbnail & Name -->
                                    <td class="py-3.5 pl-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 shadow-xs">
                                                <img src="<?php echo html_escape($image_src); ?>" 
                                                     alt="Medicine" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <a href="<?php echo base_url('medicines/show/' . $item['medicine_id']); ?>" class="font-bold text-slate-900 hover:text-emerald-600 text-decoration-none text-xs sm:text-sm block">
                                                    <?php echo html_escape($item['medicine_name']); ?>
                                                </a>
                                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                                    <span><?php echo html_escape($item['category_name'] ?? 'General'); ?></span>
                                                    <span>&bull;</span>
                                                    <span class="text-emerald-700 font-semibold font-mono">
                                                        <?php echo $avail_stock; ?> in stock
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Unit Price -->
                                    <td class="py-3.5 text-center font-mono text-slate-600 text-xs sm:text-sm">
                                        $<?php echo number_format($item['unit_price'], 2); ?>
                                    </td>

                                    <!-- Quantity Controls -->
                                    <td class="py-3.5 text-center">
                                        <div class="inline-flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden shadow-2xs">
                                            <button type="button" 
                                                    class="btn btn-sm btn-light border-0 px-2 py-1 text-slate-600 hover:text-emerald-700 focus:outline-none" 
                                                    onclick="changeQuantity(<?php echo $item['item_id']; ?>, -1, <?php echo $avail_stock; ?>)"
                                                    title="Decrease">
                                                <i class="fa-solid fa-minus text-[10px]"></i>
                                            </button>
                                            <input type="number" 
                                                   id="qty-input-<?php echo $item['item_id']; ?>" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1" 
                                                   max="<?php echo $avail_stock; ?>" 
                                                   class="w-12 text-center bg-transparent border-0 font-mono font-bold text-xs sm:text-sm text-slate-800 p-0 focus:outline-none" 
                                                   onchange="updateCartItem(<?php echo $item['item_id']; ?>, this.value, <?php echo $avail_stock; ?>)">
                                            <button type="button" 
                                                    class="btn btn-sm btn-light border-0 px-2 py-1 text-slate-600 hover:text-emerald-700 focus:outline-none" 
                                                    onclick="changeQuantity(<?php echo $item['item_id']; ?>, 1, <?php echo $avail_stock; ?>)"
                                                    title="Increase">
                                                <i class="fa-solid fa-plus text-[10px]"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Subtotal -->
                                    <td class="py-3.5 text-end font-mono font-bold text-emerald-700 text-xs sm:text-sm">
                                        $<span id="subtotal-<?php echo $item['item_id']; ?>"><?php echo number_format($item_subtotal, 2); ?></span>
                                    </td>

                                    <!-- Remove Action -->
                                    <td class="py-3.5 text-end pr-4">
                                        <a href="<?php echo base_url('cart/remove/' . $item['item_id']); ?>" 
                                           class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200" 
                                           title="Remove item" 
                                           onclick="return confirm('Remove <?php echo html_escape(addslashes($item['medicine_name'])); ?> from cart?');">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer of table card -->
                <div class="p-4 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-truck-fast text-emerald-600"></i>
                        <span>Free shipping on orders over $50.00</span>
                    </span>
                    <a href="<?php echo base_url('medicines'); ?>" class="text-emerald-600 font-semibold hover:underline">
                        + Add more medicines &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: ORDER TOTAL SUMMARY -->
        <div class="space-y-6">
            
            <div class="app-card p-5 sm:p-6 shadow-md">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-3 mb-4 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-emerald-600"></i>
                    <span>Order Summary</span>
                </h4>

                <div class="space-y-3 text-xs mb-5">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Cart Subtotal:</span>
                        <span class="font-mono font-bold text-slate-800" id="summarySubtotal">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Healthcare Tax (5% est.):</span>
                        <span class="font-mono font-bold text-slate-800" id="summaryTax">$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Shipping / Delivery:</span>
                        <span class="font-mono font-bold text-slate-800" id="summaryShipping">
                            <?php echo ($shipping === 0.00) ? '<span class="text-emerald-600 font-bold">FREE</span>' : '$' . number_format($shipping, 2); ?>
                        </span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-slate-200 text-sm">
                        <span class="font-bold text-slate-900">Grand Total:</span>
                        <span class="font-mono font-extrabold text-emerald-700 text-lg" id="summaryGrandTotal">$<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                </div>

                <!-- Checkout CTA -->
                <button type="button" class="btn btn-emerald w-full py-3.5 rounded-2xl font-bold text-sm shadow-md shadow-emerald-600/30 hover:shadow-lg flex items-center justify-center gap-2 mb-3" onclick="alert('Proceeding to pharmacy checkout order dispatch...');">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span>Proceed to Checkout</span>
                </button>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-center gap-4 text-[11px] text-slate-400">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-shield-check text-emerald-600"></i> Secure Dispensing</span>
                    <span class="flex items-center gap-1"><i class="fa-solid fa-check-double text-emerald-600"></i> Verified Stock</span>
                </div>
            </div>

        </div>

    </div>
<?php else: ?>
    <!-- EMPTY CART STATE -->
    <div class="app-card p-12 text-center max-w-lg mx-auto my-8">
        <div class="w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-inner">
            <i class="fa-solid fa-cart-shopping text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Your Shopping Cart is Empty</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
            You have no medicines currently in your shopping cart. Browse our verified pharmaceutical catalog to add items.
        </p>
        <a href="<?php echo base_url('medicines'); ?>" class="btn btn-emerald rounded-xl px-5 py-2.5 font-bold text-xs shadow-md shadow-emerald-600/20 hover:shadow-lg inline-flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-pills"></i>
            <span>Browse Medicines Catalog</span>
        </a>
    </div>
<?php endif; ?>

<!-- Real-time Quantity Update Script -->
<script>
function changeQuantity(itemId, delta, maxStock) {
    const input = document.getElementById('qty-input-' + itemId);
    if (!input) return;

    let currentQty = parseInt(input.value) || 1;
    let newQty = currentQty + delta;

    if (newQty < 1) newQty = 1;
    if (newQty > maxStock) {
        alert('Cannot exceed available stock of ' + maxStock + ' units.');
        return;
    }

    input.value = newQty;
    updateCartItem(itemId, newQty, maxStock);
}

function updateCartItem(itemId, quantity, maxStock) {
    const qty = parseInt(quantity);
    if (qty < 1 || isNaN(qty)) {
        window.location.href = '<?php echo base_url("cart/remove/"); ?>' + itemId;
        return;
    }

    if (qty > maxStock) {
        alert('Requested quantity exceeds available stock of ' + maxStock + ' units.');
        document.getElementById('qty-input-' + itemId).value = maxStock;
        return;
    }

    // Send AJAX update
    const formData = new FormData();
    formData.append('item_id', itemId);
    formData.append('quantity', qty);

    fetch('<?php echo base_url("cart/update"); ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            // Update row subtotal
            const subtotalSpan = document.getElementById('subtotal-' + itemId);
            if (subtotalSpan && data.item_total) {
                subtotalSpan.textContent = data.item_total;
            }

            // Update Summary Totals
            if (data.subtotal !== undefined) {
                document.getElementById('summarySubtotal').textContent = '$' + parseFloat(data.subtotal).toFixed(2);
                const tax = parseFloat(data.subtotal) * 0.05;
                document.getElementById('summaryTax').textContent = '$' + tax.toFixed(2);
                
                const shipping = (parseFloat(data.subtotal) >= 50.00 || parseFloat(data.subtotal) === 0) ? 0.00 : 5.00;
                document.getElementById('summaryShipping').innerHTML = (shipping === 0) ? '<span class="text-emerald-600 font-bold">FREE</span>' : '$' + shipping.toFixed(2);

                const grand = parseFloat(data.subtotal) + tax + shipping;
                document.getElementById('summaryGrandTotal').textContent = '$' + grand.toFixed(2);
            }

            // Update Navbar Mini Cart Counter
            const navBadge = document.getElementById('navCartBadge');
            if (navBadge) {
                navBadge.textContent = data.total_items;
                if (data.total_items > 0) {
                    navBadge.classList.remove('hidden');
                }
            }

            const headerBadge = document.getElementById('cartItemCountBadge');
            if (headerBadge) {
                headerBadge.textContent = data.total_items + (data.total_items === 1 ? ' Item' : ' Items');
            }
        } else {
            alert(data.message || 'Failed to update quantity.');
        }
    })
    .catch(err => {
        console.error('Update error:', err);
    });
}
</script>
