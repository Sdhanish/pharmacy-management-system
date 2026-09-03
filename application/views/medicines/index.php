<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Medicines Catalog</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows); ?> Total
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Browse, search, and manage medicine inventory, prices, expiration dates, and suppliers.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('medicines/create'); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition text-decoration-none">
            <i class="fa-solid fa-plus"></i>
            <span>Add Medicine</span>
        </a>
    </div>
</div>

<!-- SEARCH & CATEGORY FILTER CARD -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('medicines'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        
        <!-- 1. Search Medicines -->
        <div class="sm:col-span-6 lg:col-span-7">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Medicines
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
                       placeholder="Search by medicine name, description, supplier...">
            </div>
        </div>

        <!-- 2. Filter by Category -->
        <div class="sm:col-span-4 lg:col-span-3">
            <label for="category_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Category
            </label>
            <select name="category_id" id="category_id" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                <option value="">All Categories</option>
                <?php if (isset($categories) && !empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ((string)($category_id ?? '') === (string)$cat['id']) ? 'selected' : ''; ?>>
                            <?php echo html_escape($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- 3. Filter Actions -->
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Filters">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- MEDICINES TABLE -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 pl-4">Medicine</th>
                    <th class="py-3.5">Category</th>
                    <th class="py-3.5">Supplier / Brand</th>
                    <th class="py-3.5 text-end">Price</th>
                    <th class="py-3.5 text-center">Stock Qty</th>
                    <th class="py-3.5">Expiry Date</th>
                    <th class="py-3.5 text-center">Status</th>
                    <th class="py-3.5 text-end pr-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($medicines) && !empty($medicines)): ?>
                    <?php foreach ($medicines as $med): ?>
                        <?php
                        $stock = (int) ($med['stock_quantity'] ?? 0);
                        $exp_date = $med['expiry_date'];
                        $is_expired = ($exp_date && strtotime($exp_date) < strtotime(date('Y-m-d')));
                        $is_expiring_soon = ($exp_date && !$is_expired && strtotime($exp_date) <= strtotime('+30 days'));
                        
                        // Status badge logic
                        if ($med['status'] === 'inactive') {
                            $status_label = 'Inactive';
                            $status_class = 'bg-slate-100 text-slate-600 border-slate-200';
                            $status_icon = 'fa-circle-pause';
                        } elseif ($is_expired) {
                            $status_label = 'Expired';
                            $status_class = 'bg-rose-100 text-rose-800 border-rose-200';
                            $status_icon = 'fa-circle-xmark';
                        } elseif ($stock === 0) {
                            $status_label = 'Out of Stock';
                            $status_class = 'bg-slate-100 text-slate-700 border-slate-200';
                            $status_icon = 'fa-ban';
                        } elseif ($is_expiring_soon) {
                            $status_label = 'Expiring Soon';
                            $status_class = 'bg-purple-100 text-purple-800 border-purple-200';
                            $status_icon = 'fa-hourglass-half';
                        } elseif ($stock <= 10) {
                            $status_label = 'Low Stock';
                            $status_class = 'bg-amber-100 text-amber-800 border-amber-200';
                            $status_icon = 'fa-triangle-exclamation';
                        } else {
                            $status_label = 'In Stock';
                            $status_class = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            $status_icon = 'fa-circle-check';
                        }

                        $image_src = !empty($med['image_url']) ? $med['image_url'] : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=120&auto=format&fit=crop&q=80';
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Medicine Name & Thumbnail -->
                            <td class="py-3 pl-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200/80 overflow-hidden shrink-0">
                                        <img src="<?php echo html_escape($image_src); ?>" 
                                             alt="<?php echo html_escape($med['medicine_name']); ?>" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=120&auto=format&fit=crop&q=80'">
                                    </div>
                                    <div>
                                        <a href="<?php echo base_url('medicines/show/' . $med['id']); ?>" class="font-bold text-slate-900 hover:text-emerald-600 text-decoration-none text-xs sm:text-sm">
                                            <?php echo html_escape($med['medicine_name']); ?>
                                        </a>
                                        <?php if (!empty($med['description'])): ?>
                                            <p class="text-[11px] text-slate-400 mb-0 line-clamp-1 max-w-xs">
                                                <?php echo html_escape(character_limiter($med['description'], 50)); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3">
                                <span class="badge bg-slate-100 text-slate-700 border border-slate-200 font-medium text-[11px] px-2 py-0.5 rounded-md">
                                    <?php echo html_escape($med['category_name'] ?? 'Unassigned'); ?>
                                </span>
                            </td>

                            <!-- Supplier / Brand -->
                            <td class="py-3 text-slate-600 text-xs">
                                <?php echo html_escape($med['supplier_name'] ?? 'Direct / Generic'); ?>
                            </td>

                            <!-- Price -->
                            <td class="py-3 text-end font-mono text-xs font-bold text-emerald-700">
                                $<?php echo number_format((float)$med['price'], 2); ?>
                            </td>

                            <!-- Stock Quantity -->
                            <td class="py-3 text-center">
                                <span class="font-bold text-sm font-mono <?php echo ($stock <= 10) ? 'text-rose-600' : 'text-slate-800'; ?>">
                                    <?php echo $stock; ?>
                                </span>
                                <span class="block text-[10px] text-slate-400">units</span>
                            </td>

                            <!-- Expiry Date -->
                            <td class="py-3 text-xs font-mono">
                                <span class="<?php echo $is_expired ? 'text-rose-600 font-bold' : ($is_expiring_soon ? 'text-purple-600 font-bold' : 'text-slate-700'); ?>">
                                    <?php echo date('M d, Y', strtotime($exp_date)); ?>
                                </span>
                                <?php if ($is_expired): ?>
                                    <span class="block text-[10px] text-rose-500 font-bold">Past date</span>
                                <?php elseif ($is_expiring_soon): ?>
                                    <span class="block text-[10px] text-purple-600 font-semibold">&le; 30 days</span>
                                <?php endif; ?>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $status_class; ?>">
                                    <i class="fa-solid <?php echo $status_icon; ?> text-[10px]"></i>
                                    <span><?php echo $status_label; ?></span>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-end pr-4">
                                <div class="inline-flex items-center gap-1">
                                    <form action="<?php echo base_url('cart/add'); ?>" method="POST" class="inline m-0">
                                        <input type="hidden" name="medicine_id" value="<?php echo $med['id']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" 
                                                class="btn btn-sm btn-emerald p-1.5 rounded-lg text-white shadow-2xs" 
                                                title="Add to Cart" 
                                                <?php echo ($stock <= 0) ? 'disabled' : ''; ?>>
                                            <i class="fa-solid fa-cart-plus text-xs"></i>
                                        </button>
                                    </form>
                                    <a href="<?php echo base_url('medicines/show/' . $med['id']); ?>" 
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 border border-slate-200" 
                                       title="View Details">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </a>
                                    <a href="<?php echo base_url('medicines/edit/' . $med['id']); ?>" 
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200" 
                                       title="Edit Medicine">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 btn-delete-medicine" 
                                            data-id="<?php echo $med['id']; ?>" 
                                            data-name="<?php echo html_escape($med['medicine_name']); ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteMedicineModal" 
                                            title="Delete Medicine">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-pills text-2xl"></i>
                            </div>
                            <h5 class="text-sm font-bold text-slate-700 mb-1">No Medicines Found</h5>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">No matching medicines found with current search or filter criteria.</p>
                            <a href="<?php echo base_url('medicines/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold">
                                <i class="fa-solid fa-plus mr-1"></i> Add New Medicine
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
            <span class="font-bold text-slate-800"><?php echo number_format($total_rows); ?></span> medicines
        </div>

        <div>
            <?php echo $pagination_links; ?>
        </div>
    </div>
</div>

<!-- BOOTSTRAP 5 DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteMedicineModal" tabindex="-1" aria-labelledby="deleteMedicineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1" id="deleteMedicineModalLabel">Confirm Medicine Deletion</h4>
                <p class="text-xs text-slate-500 mb-4">
                    Are you sure you want to delete <strong id="deleteMedicineName" class="text-slate-800">this medicine</strong>?
                    <br>This action cannot be undone.
                </p>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-600 border border-slate-200" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger rounded-xl px-4 py-2.5 text-xs font-semibold shadow-md shadow-rose-600/20 text-white text-decoration-none">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Yes, Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Deletion Link Binding -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-medicine');
    const deleteNameSpan = document.getElementById('deleteMedicineName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            if (deleteNameSpan) deleteNameSpan.textContent = '"' + name + '"';
            if (confirmDeleteBtn) confirmDeleteBtn.href = '<?php echo base_url("medicines/delete/"); ?>' + id;
        });
    });
});
</script>
