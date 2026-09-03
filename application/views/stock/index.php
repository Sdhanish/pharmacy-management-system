<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Stock Purchases & Management</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows); ?> Orders
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Track stock receipts, supplier purchases, and automatic inventory balance adjustments.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('stock-history'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
            <span>Stock History</span>
        </a>
        <a href="<?php echo base_url('stock/create'); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition text-decoration-none">
            <i class="fa-solid fa-plus"></i>
            <span>Add Stock Purchase</span>
        </a>
    </div>
</div>

<!-- SEARCH & SUPPLIER FILTER CARD -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('stock'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        
        <!-- Search Input -->
        <div class="sm:col-span-6 lg:col-span-7">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Purchases
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="<?php echo html_escape($search ?? ''); ?>" 
                       class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" 
                       placeholder="Search by medicine name, supplier, or purchase notes...">
            </div>
        </div>

        <!-- Supplier Filter -->
        <div class="sm:col-span-4 lg:col-span-3">
            <label for="supplier_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Supplier / Brand
            </label>
            <select name="supplier_id" id="supplier_id" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                <option value="">All Suppliers</option>
                <?php if (isset($suppliers) && !empty($suppliers)): ?>
                    <?php foreach ($suppliers as $sup): ?>
                        <option value="<?php echo $sup['id']; ?>" <?php echo ((string)($supplier_id ?? '') === (string)$sup['id']) ? 'selected' : ''; ?>>
                            <?php echo html_escape($sup['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Actions -->
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <a href="<?php echo base_url('stock'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Filters">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- STOCK PURCHASES TABLE WITH CURRENT STOCK COLUMN -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 pl-4">Order #</th>
                    <th class="py-3.5">Medicine</th>
                    <th class="py-3.5 text-center">Current Stock</th>
                    <th class="py-3.5">Supplier / Brand</th>
                    <th class="py-3.5 text-center">Purchased Qty</th>
                    <th class="py-3.5 text-end">Unit Price</th>
                    <th class="py-3.5 text-end">Total Amount</th>
                    <th class="py-3.5">Purchase Date</th>
                    <th class="py-3.5 text-end pr-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($purchases) && !empty($purchases)): ?>
                    <?php foreach ($purchases as $p): ?>
                        <?php
                        $curr_stock = (int) ($p['current_stock'] ?? 0);
                        $qty = (int) $p['quantity'];
                        $unit_price = (float) $p['purchase_price'];
                        $total = (float) ($p['total_amount'] ?? ($qty * $unit_price));
                        $image_src = !empty($p['image_url']) ? $p['image_url'] : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=100&auto=format&fit=crop&q=80';
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Order ID -->
                            <td class="py-3 pl-4 font-mono font-bold text-slate-600">
                                #PO-<?php echo str_pad($p['id'], 5, '0', STR_PAD_LEFT); ?>
                            </td>

                            <!-- Medicine -->
                            <td class="py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        <img src="<?php echo html_escape($image_src); ?>" alt="Medicine" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-xs sm:text-sm">
                                            <?php echo html_escape($p['medicine_name'] ?? 'Unknown Medicine'); ?>
                                        </span>
                                        <?php if (!empty($p['notes'])): ?>
                                            <span class="text-[10px] text-slate-400 block line-clamp-1"><?php echo html_escape($p['notes']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- CURRENT STOCK COLUMN -->
                            <td class="py-3 text-center">
                                <span class="badge <?php echo ($curr_stock <= 10) ? 'bg-rose-100 text-rose-800 border-rose-200' : 'bg-emerald-100 text-emerald-800 border-emerald-200'; ?> border font-mono font-bold text-xs px-2.5 py-1 rounded-lg">
                                    <?php echo $curr_stock; ?> units
                                </span>
                            </td>

                            <!-- Supplier -->
                            <td class="py-3 text-slate-600 text-xs">
                                <?php echo html_escape($p['supplier_name'] ?? 'Direct Manufacturer'); ?>
                            </td>

                            <!-- Purchased Quantity -->
                            <td class="py-3 text-center">
                                <span class="badge bg-emerald-600 text-white font-mono font-bold text-xs px-2.5 py-1 rounded-lg">
                                    +<?php echo $qty; ?>
                                </span>
                            </td>

                            <!-- Unit Price -->
                            <td class="py-3 text-end font-mono text-xs text-slate-600">
                                $<?php echo number_format($unit_price, 2); ?>
                            </td>

                            <!-- Total Amount -->
                            <td class="py-3 text-end font-mono text-xs font-bold text-emerald-700">
                                $<?php echo number_format($total, 2); ?>
                            </td>

                            <!-- Purchase Date -->
                            <td class="py-3 text-xs font-mono text-slate-600">
                                <?php echo date('M d, Y', strtotime($p['purchase_date'])); ?>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-end pr-4">
                                <div class="inline-flex items-center gap-1">
                                    <a href="<?php echo base_url('stock/edit/' . $p['id']); ?>" 
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200" 
                                       title="Edit Purchase">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 btn-delete-purchase" 
                                            data-id="<?php echo $p['id']; ?>" 
                                            data-qty="<?php echo $qty; ?>" 
                                            data-med="<?php echo html_escape($p['medicine_name']); ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deletePurchaseModal" 
                                            title="Delete Purchase & Reverse Stock">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="py-12 text-center text-slate-400">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-boxes-stacked text-2xl"></i>
                            </div>
                            <h5 class="text-sm font-bold text-slate-700 mb-1">No Stock Purchases Found</h5>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">Record your first supplier consignment purchase to increase inventory stock.</p>
                            <a href="<?php echo base_url('stock/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold">
                                <i class="fa-solid fa-plus mr-1"></i> Add Stock Purchase
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer with Pagination -->
    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/60 flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="text-xs text-slate-500">
            Showing <span class="font-bold text-slate-800"><?php echo ($total_rows > 0) ? ($offset + 1) : 0; ?></span> to 
            <span class="font-bold text-slate-800"><?php echo min($offset + $per_page, $total_rows); ?></span> of 
            <span class="font-bold text-slate-800"><?php echo number_format($total_rows); ?></span> purchase records
        </div>

        <div>
            <?php echo $pagination_links; ?>
        </div>
    </div>
</div>

<!-- BOOTSTRAP 5 DELETE CONFIRMATION MODAL (WITH STOCK REVERSAL NOTICE) -->
<div class="modal fade" id="deletePurchaseModal" tabindex="-1" aria-labelledby="deletePurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <i class="fa-solid fa-arrow-rotate-left text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1" id="deletePurchaseModalLabel">Delete Purchase & Reverse Stock</h4>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Are you sure you want to delete purchase <strong id="deletePurchaseOrder" class="text-slate-800">#PO-00000</strong>?
                    <br><span class="text-rose-600 font-semibold">This will automatically reduce <span id="deletePurchaseMed">the medicine</span> inventory by <span id="deletePurchaseQty">0</span> units.</span>
                </p>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-600 border border-slate-200" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="#" id="confirmDeletePurchaseBtn" class="btn btn-danger rounded-xl px-4 py-2.5 text-xs font-semibold shadow-md shadow-rose-600/20 text-white text-decoration-none">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Yes, Delete & Reverse Stock
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-purchase');
    const deleteOrderSpan = document.getElementById('deletePurchaseOrder');
    const deleteMedSpan = document.getElementById('deletePurchaseMed');
    const deleteQtySpan = document.getElementById('deletePurchaseQty');
    const confirmDeleteBtn = document.getElementById('confirmDeletePurchaseBtn');

    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const qty = this.getAttribute('data-qty');
            const med = this.getAttribute('data-med');

            if (deleteOrderSpan) deleteOrderSpan.textContent = '#PO-' + id.padStart(5, '0');
            if (deleteMedSpan) deleteMedSpan.textContent = '"' + med + '"';
            if (deleteQtySpan) deleteQtySpan.textContent = qty;
            if (confirmDeleteBtn) confirmDeleteBtn.href = '<?php echo base_url("stock/delete/"); ?>' + id;
        });
    });
});
</script>
