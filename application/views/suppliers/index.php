<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$supplier_rows = isset($suppliers) && is_array($suppliers) ? $suppliers : array();
$active_suppliers = 0;
$inactive_suppliers = 0;
foreach ($supplier_rows as $supplier_row) {
    if (($supplier_row['status'] ?? '') === 'active') {
        $active_suppliers++;
    } else {
        $inactive_suppliers++;
    }
}
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Supplier Management</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format(count($supplier_rows)); ?> Total
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Manage the suppliers and brands connected to your medicine inventory.</p>
    </div>
    <a href="<?php echo base_url('suppliers/create'); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition text-decoration-none">
        <i class="fa-solid fa-plus"></i>
        <span>Add Supplier</span>
    </a>
</div>

<!-- Flash Notifications -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-emerald-200 bg-emerald-50 text-emerald-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-base"></i><span><?php echo $this->session->flashdata('success'); ?></span></div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i><span><?php echo $this->session->flashdata('error'); ?></span></div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Summary Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="app-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-building"></i></div>
        <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Total Suppliers</p><h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format(count($supplier_rows)); ?></h3></div>
    </div>
    <div class="app-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-circle-check"></i></div>
        <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Active Suppliers</p><h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format($active_suppliers); ?></h3></div>
    </div>
    <div class="app-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-pause-circle"></i></div>
        <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Inactive Suppliers</p><h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format($inactive_suppliers); ?></h3></div>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('suppliers'); ?>" method="get" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        <div class="sm:col-span-10">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Search Suppliers</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                <input type="text" name="search" id="search" value="<?php echo html_escape($search ?? ''); ?>" class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Search by supplier, contact, email, or phone...">
            </div>
        </div>
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs"><i class="fa-solid fa-filter text-[10px]"></i><span>Filter</span></button>
            <?php if (!empty($search)): ?><a href="<?php echo base_url('suppliers'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Search"><i class="fa-solid fa-rotate-left"></i></a><?php endif; ?>
        </div>
    </form>
</div>

<!-- Suppliers Table -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="suppliersTable">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="sortable py-3.5 pl-5" data-sort="number">#</th>
                    <th class="sortable py-3.5" data-sort="text">Supplier</th>
                    <th class="sortable py-3.5" data-sort="text">Contact Person</th>
                    <th class="sortable py-3.5" data-sort="text">Email</th>
                    <th class="sortable py-3.5" data-sort="text">Phone</th>
                    <th class="sortable py-3.5 text-center" data-sort="text">Status</th>
                    <th class="py-3.5 text-end pr-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($supplier_rows)): ?>
                    <?php foreach ($supplier_rows as $index => $supplier): ?>
                        <?php $is_active = ($supplier['status'] ?? '') === 'active'; ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 pl-5 font-mono text-slate-400 text-xs"><?php echo $index + 1; ?></td>
                            <td class="py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0"><i class="fa-solid fa-building text-sm"></i></div>
                                    <div><p class="font-bold text-slate-900 mb-0 text-xs sm:text-sm"><?php echo html_escape($supplier['name'] ?? ''); ?></p><p class="text-[10px] text-slate-400 mb-0">Supplier #<?php echo str_pad((int) $supplier['id'], 4, '0', STR_PAD_LEFT); ?></p></div>
                                </div>
                            </td>
                            <td class="py-3 text-slate-600 text-xs"><?php echo html_escape($supplier['contact_person'] ?? 'Not provided'); ?></td>
                            <td class="py-3 text-slate-600 text-xs"><?php echo html_escape($supplier['email'] ?? 'Not provided'); ?></td>
                            <td class="py-3 text-slate-600 text-xs font-mono"><?php echo html_escape($supplier['phone'] ?? 'Not provided'); ?></td>
                            <td class="py-3 text-center"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'; ?>"><i class="fa-solid <?php echo $is_active ? 'fa-circle-check' : 'fa-circle-pause'; ?> text-[10px]"></i><?php echo $is_active ? 'Active' : 'Inactive'; ?></span></td>
                            <td class="py-3 text-end pr-5"><div class="inline-flex items-center gap-1"><a href="<?php echo base_url('suppliers/view/' . $supplier['id']); ?>" class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 border border-slate-200" title="View Supplier"><i class="fa-regular fa-eye text-xs"></i></a><a href="<?php echo base_url('suppliers/edit/' . $supplier['id']); ?>" class="btn btn-sm btn-light p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200" title="Edit Supplier"><i class="fa-regular fa-pen-to-square text-xs"></i></a></div></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="py-12 text-center"><div class="empty-state-icon-wrap w-14 h-14 text-xl mx-auto mb-3"><i class="fa-solid fa-building text-emerald-500"></i></div><h6 class="text-sm font-bold text-slate-700 mb-1">No suppliers found</h6><p class="text-xs text-slate-400 mb-3">Add a supplier or adjust your search.</p><a href="<?php echo base_url('suppliers/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-3 py-2 text-xs font-semibold text-decoration-none"><i class="fa-solid fa-plus mr-1"></i>Add Supplier</a></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
