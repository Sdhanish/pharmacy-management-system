<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Customer Management</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows); ?> Registered Customers
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Manage customer accounts, contact details, addresses, and dispensary eligibility status.</p>
    </div>
</div>

<!-- SEARCH & STATUS FILTER CARD -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('customers'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        
        <!-- Search Input -->
        <div class="sm:col-span-6 lg:col-span-7">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Customers
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
                       placeholder="Search by customer name, email, phone, address...">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="sm:col-span-4 lg:col-span-3">
            <label for="status" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Account Status
            </label>
            <select name="status" id="status" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                <option value="">All Statuses</option>
                <option value="active" <?php echo (($status ?? '') === 'active') ? 'selected' : ''; ?>>Active Only</option>
                <option value="inactive" <?php echo (($status ?? '') === 'inactive') ? 'selected' : ''; ?>>Inactive Only</option>
            </select>
        </div>

        <!-- Filter Actions -->
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <a href="<?php echo base_url('customers'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Filters">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- CUSTOMER LIST TABLE -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 pl-4">Customer</th>
                    <th class="py-3.5">Email Address</th>
                    <th class="py-3.5">Phone Number</th>
                    <th class="py-3.5">Address</th>
                    <th class="py-3.5 text-center">Status</th>
                    <th class="py-3.5">Registered</th>
                    <th class="py-3.5 text-end pr-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($customers) && !empty($customers)): ?>
                    <?php foreach ($customers as $c): ?>
                        <?php
                        $is_active = ($c['status'] === 'active');
                        $initials = strtoupper(substr($c['name'], 0, 2));
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Customer Name & Avatar -->
                            <td class="py-3 pl-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                        <?php echo $initials; ?>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-xs sm:text-sm">
                                            <?php echo html_escape($c['name']); ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">ID #CUS-<?php echo str_pad($c['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3 font-mono text-xs text-slate-600">
                                <?php echo html_escape($c['email']); ?>
                            </td>

                            <!-- Phone -->
                            <td class="py-3 font-mono text-xs text-slate-600">
                                <?php echo !empty($c['phone']) ? html_escape($c['phone']) : '<span class="text-slate-300">--</span>'; ?>
                            </td>

                            <!-- Address -->
                            <td class="py-3 text-slate-600 text-xs max-w-xs">
                                <?php echo !empty($c['address']) ? html_escape($c['address']) : '<span class="text-slate-300">No address recorded</span>'; ?>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 text-center">
                                <?php if ($is_active): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Active</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fa-solid fa-circle-pause text-[10px]"></i>
                                        <span>Inactive</span>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Registered Date -->
                            <td class="py-3 text-xs font-mono text-slate-500">
                                <?php echo date('M d, Y', strtotime($c['created_at'])); ?>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-end pr-4">
                                <div class="inline-flex items-center gap-1">
                                    <a href="<?php echo base_url('customers/edit/' . $c['id']); ?>" 
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200" 
                                       title="Edit Customer Profile">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 btn-delete-customer" 
                                            data-id="<?php echo $c['id']; ?>" 
                                            data-name="<?php echo html_escape($c['name']); ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCustomerModal" 
                                            title="Delete Customer">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-users text-2xl"></i>
                            </div>
                            <h5 class="text-sm font-bold text-slate-700 mb-1">No Customers Found</h5>
                            <p class="text-xs text-slate-400 mb-0">No matching customer records found with current filter criteria.</p>
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
            <span class="font-bold text-slate-800"><?php echo number_format($total_rows); ?></span> customers
        </div>

        <div>
            <?php echo $pagination_links; ?>
        </div>
    </div>
</div>

<!-- BOOTSTRAP 5 DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <i class="fa-solid fa-user-xmark text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1" id="deleteCustomerModalLabel">Delete Customer Account</h4>
                <p class="text-xs text-slate-500 mb-4">
                    Are you sure you want to delete <strong id="deleteCustomerName" class="text-slate-800">this customer</strong>?
                    <br>This action will permanently remove their profile.
                </p>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-600 border border-slate-200" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="#" id="confirmDeleteCustomerBtn" class="btn btn-danger rounded-xl px-4 py-2.5 text-xs font-semibold shadow-md shadow-rose-600/20 text-white text-decoration-none">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Yes, Delete Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-customer');
    const deleteNameSpan = document.getElementById('deleteCustomerName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteCustomerBtn');

    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            if (deleteNameSpan) deleteNameSpan.textContent = '"' + name + '"';
            if (confirmDeleteBtn) confirmDeleteBtn.href = '<?php echo base_url("customers/delete/"); ?>' + id;
        });
    });
});
</script>
