<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Stock Management</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($inv_total ?? 0); ?> Medicines
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Track current inventory levels and supplier purchase history for all medicines.</p>
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

<!-- SEARCH FILTER -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('stock'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">

        <!-- Search by Medicine Name -->
        <div class="sm:col-span-6 lg:col-span-7">
            <label for="inv_search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Medicine
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="inv_search" id="inv_search"
                       value="<?php echo html_escape($inv_search ?? ''); ?>"
                       class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="Search by medicine name, category, or supplier...">
            </div>
        </div>

        <!-- Supplier Filter -->
        <div class="sm:col-span-4 lg:col-span-3">
            <label for="supplier_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Supplier / Brand
            </label>
            <select name="supplier_id" id="supplier_id" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                <option value="">All Suppliers</option>
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $sup): ?>
                        <option value="<?php echo $sup['id']; ?>" <?php echo ((string)($supplier_id ?? '') === (string)$sup['id']) ? 'selected' : ''; ?>>
                            <?php echo html_escape($sup['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Buttons -->
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <a href="<?php echo base_url('stock'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- UNIFIED STOCK TABLE: ALL MEDICINES + PURCHASE DETAILS -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="sortable py-3.5 pl-4" data-sort="text">Medicine</th>
                    <th class="sortable py-3.5 text-center" data-sort="number">Current Stock</th>
                    <th class="sortable py-3.5" data-sort="text">Category</th>
                    <th class="sortable py-3.5" data-sort="text">Supplier</th>
                    <th class="sortable py-3.5 text-center" data-sort="number">Total Purchased</th>
                    <th class="sortable py-3.5" data-sort="date">Last Purchase</th>
                    <th class="py-3.5 text-end pr-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($inventory_list)): ?>
                    <?php foreach ($inventory_list as $inv): ?>
                        <?php
                        $curr_stock    = (int) ($inv['current_stock'] ?? 0);
                        $total_pur     = (int) ($inv['total_purchased'] ?? 0);
                        $last_sup      = $inv['last_supplier_name'] ?? null;
                        $last_pur_date = $inv['last_purchase_date'] ?? null;
                        $image_src     = !empty($inv['image_url'])
                            ? $inv['image_url']
                            : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=100&auto=format&fit=crop&q=80';

                        if ($curr_stock === 0) {
                            $stock_class = 'bg-rose-100 text-rose-800 border-rose-200';
                            $stock_dot   = 'bg-rose-500';
                            $stock_label = 'Out of Stock';
                        } elseif ($curr_stock <= 10) {
                            $stock_class = 'bg-amber-100 text-amber-800 border-amber-200';
                            $stock_dot   = 'bg-amber-500';
                            $stock_label = 'Low Stock';
                        } else {
                            $stock_class = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            $stock_dot   = 'bg-emerald-500';
                            $stock_label = 'In Stock';
                        }
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">

                            <!-- Medicine Name -->
                            <td class="py-3.5 pl-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        <img src="<?php echo html_escape($image_src); ?>" alt="Medicine" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 mb-0 text-xs sm:text-sm leading-snug">
                                            <?php echo html_escape($inv['medicine_name']); ?>
                                        </p>
                                        <span class="text-[10px] text-slate-400">ID #<?php echo $inv['id']; ?></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Current Stock -->
                            <td class="py-3.5 text-center">
                                <span class="badge <?php echo $stock_class; ?> border font-mono font-bold text-xs px-2.5 py-1.5 rounded-xl">
                                    <?php echo $curr_stock; ?> units
                                </span>
                                <p class="text-[10px] mt-1 mb-0 font-semibold flex items-center justify-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo $stock_dot; ?> inline-block"></span>
                                    <?php echo $stock_label; ?>
                                </p>
                            </td>

                            <!-- Category -->
                            <td class="py-3.5 text-xs text-slate-600">
                                <?php echo html_escape($inv['category_name'] ?? 'Uncategorized'); ?>
                            </td>

                            <!-- Supplier -->
                            <td class="py-3.5 text-xs text-slate-600">
                                <?php if ($last_sup): ?>
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-truck-ramp-box text-slate-400 text-[10px]"></i>
                                        <?php echo html_escape($last_sup); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400 italic text-[11px]">No purchase yet</span>
                                <?php endif; ?>
                            </td>

                            <!-- Total Purchased -->
                            <td class="py-3.5 text-center">
                                <?php if ($total_pur > 0): ?>
                                    <span class="badge bg-blue-100 text-blue-800 border border-blue-200 font-mono font-bold text-xs px-2 py-1 rounded-lg">
                                        <?php echo $total_pur; ?> units
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-[11px]">—</span>
                                <?php endif; ?>
                            </td>

                            <!-- Last Purchase Date -->
                            <td class="py-3.5 text-xs font-mono text-slate-600">
                                <?php if ($last_pur_date): ?>
                                    <?php echo date('M d, Y', strtotime($last_pur_date)); ?>
                                <?php else: ?>
                                    <span class="text-slate-400 italic text-[11px]">Never purchased</span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 text-end pr-4">
                                <div class="inline-flex items-center gap-1">
                                    <a href="<?php echo base_url('stock/create'); ?>"
                                       class="btn btn-sm p-1.5 rounded-lg text-emerald-700 hover:text-white hover:bg-emerald-600 bg-emerald-50 border border-emerald-200 hover:border-emerald-600 transition"
                                       title="Add Purchase for this Medicine">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </a>
                                    <a href="<?php echo base_url('stock-history?search=' . urlencode($inv['medicine_name'])); ?>"
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-purple-600 hover:bg-purple-50 border border-slate-200"
                                       title="View Purchase History">
                                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                    </a>
                                    <a href="<?php echo base_url('medicines/edit/' . $inv['id']); ?>"
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200"
                                       title="Edit Medicine">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-14 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-pills text-2xl"></i>
                            </div>
                            <h5 class="text-sm font-bold text-slate-700 mb-1">No Medicines Found</h5>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">Add medicines to your catalog to start tracking inventory.</p>
                            <a href="<?php echo base_url('medicines/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold">
                                <i class="fa-solid fa-plus mr-1"></i> Add Medicine
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer: count -->
    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/60 flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="text-xs text-slate-500">
            Showing <span class="font-bold text-slate-800"><?php echo count($inventory_list ?? []); ?></span> of
            <span class="font-bold text-slate-800"><?php echo number_format($inv_total ?? 0); ?></span> medicines
        </div>
        <div class="flex items-center gap-4 text-[11px] text-slate-400">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> In Stock</span>
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> Low Stock (≤10)</span>
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span> Out of Stock</span>
        </div>
    </div>
</div>

<!-- RECENT PURCHASE ORDERS (collapsible section at bottom) -->
<div class="app-card p-0 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-receipt text-sm"></i>
            </div>
            <div>
                <h5 class="text-sm font-bold text-slate-900 mb-0">Recent Purchase Orders</h5>
                <p class="text-[11px] text-slate-400 mb-0">Latest supplier stock receipts</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold text-xs px-2 py-1 rounded-lg">
                <?php echo number_format($total_rows ?? 0); ?> Orders
            </span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="sortable py-3 pl-4" data-sort="text">Order #</th>
                    <th class="sortable py-3" data-sort="text">Medicine</th>
                    <th class="sortable py-3" data-sort="text">Supplier</th>
                    <th class="sortable py-3 text-center" data-sort="number">Qty Added</th>
                    <th class="sortable py-3 text-end" data-sort="number">Total Cost</th>
                    <th class="sortable py-3" data-sort="date">Date</th>
                    <th class="py-3 text-end pr-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($purchases)): ?>
                    <?php foreach ($purchases as $p): ?>
                        <?php
                        $qty        = (int) $p['quantity'];
                        $unit_price = (float) $p['purchase_price'];
                        $total      = (float) ($p['total_amount'] ?? ($qty * $unit_price));
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 pl-4 font-mono font-bold text-slate-500 text-xs">
                                #PO-<?php echo str_pad($p['id'], 5, '0', STR_PAD_LEFT); ?>
                            </td>
                            <td class="py-3 font-semibold text-slate-800 text-xs">
                                <?php echo html_escape($p['medicine_name'] ?? '—'); ?>
                                <?php if (!empty($p['notes'])): ?>
                                    <span class="block text-[10px] text-slate-400 font-normal line-clamp-1"><?php echo html_escape($p['notes']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-xs text-slate-500">
                                <?php echo html_escape($p['supplier_name'] ?? 'Direct'); ?>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-emerald-600 text-white font-mono font-bold text-xs px-2 py-1 rounded-lg">
                                    +<?php echo $qty; ?>
                                </span>
                            </td>
                            <td class="py-3 text-end font-mono text-xs font-bold text-emerald-700">
                                $<?php echo number_format($total, 2); ?>
                            </td>
                            <td class="py-3 text-xs font-mono text-slate-500">
                                <?php echo date('M d, Y', strtotime($p['purchase_date'])); ?>
                            </td>
                            <td class="py-3 text-end pr-4">
                                <div class="inline-flex gap-1">
                                    <a href="<?php echo base_url('stock/edit/' . $p['id']); ?>"
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200"
                                       title="Edit">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 btn-delete-purchase"
                                            data-id="<?php echo $p['id']; ?>"
                                            data-qty="<?php echo $qty; ?>"
                                            data-med="<?php echo html_escape($p['medicine_name']); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deletePurchaseModal"
                                            title="Delete & Reverse Stock">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                            <p class="text-xs mb-3">No purchase orders recorded yet.</p>
                            <a href="<?php echo base_url('stock/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold">
                                <i class="fa-solid fa-plus mr-1"></i> Add First Purchase
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/60 text-xs text-slate-400">
        <?php echo number_format($total_rows ?? 0); ?> total purchase orders recorded
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deletePurchaseModal" tabindex="-1" aria-labelledby="deletePurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <i class="fa-solid fa-arrow-rotate-left text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1" id="deletePurchaseModalLabel">Delete Purchase & Reverse Stock</h4>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Are you sure you want to delete purchase <strong id="deletePurchaseOrder" class="text-slate-800">#PO-00000</strong>?<br>
                    <span class="text-rose-600 font-semibold">This will automatically reduce <span id="deletePurchaseMed">the medicine</span> stock by <span id="deletePurchaseQty">0</span> units.</span>
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-600 border border-slate-200" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeletePurchaseBtn" class="btn btn-danger rounded-xl px-4 py-2.5 text-xs font-semibold text-white text-decoration-none">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Yes, Delete & Reverse
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-purchase').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id  = this.getAttribute('data-id');
            const qty = this.getAttribute('data-qty');
            const med = this.getAttribute('data-med');
            const orderEl = document.getElementById('deletePurchaseOrder');
            const medEl   = document.getElementById('deletePurchaseMed');
            const qtyEl   = document.getElementById('deletePurchaseQty');
            const cfmBtn  = document.getElementById('confirmDeletePurchaseBtn');
            if (orderEl) orderEl.textContent = '#PO-' + id.padStart(5, '0');
            if (medEl)   medEl.textContent   = '"' + med + '"';
            if (qtyEl)   qtyEl.textContent   = qty;
            if (cfmBtn)  cfmBtn.href         = '<?php echo base_url("stock/delete/"); ?>' + id;
        });
    });
});
</script>
