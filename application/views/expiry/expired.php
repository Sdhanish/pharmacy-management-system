<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('expiry'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Expired Medicines</h2>
            <span class="badge bg-rose-100 text-rose-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows ?? 0); ?> Expired Items
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Pharmaceutical items past their expiration date. These must be quarantined and scheduled for safe disposal or supplier return.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('expiry'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            <i class="fa-solid fa-layer-group"></i>
            <span>All Expiry Alerts</span>
        </a>
    </div>
</div>

<!-- Alert Banner -->
<div class="alert alert-danger rounded-2xl border-rose-200 bg-rose-50 text-rose-800 p-4 mb-6 text-xs sm:text-sm flex items-start gap-3">
    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mt-0.5 shrink-0"></i>
    <div>
        <h5 class="font-bold mb-1 text-rose-900 text-xs sm:text-sm">Quarantine & Patient Safety Protocol</h5>
        <p class="mb-0 text-xs text-rose-700">
            Dispensing expired medications violates pharmaceutical regulations. Please remove physical stock of these items from dispensary shelves immediately.
        </p>
    </div>
</div>

<!-- STATS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="app-card p-4 border-l-4 border-l-rose-500 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-ban"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Expired Drugs</p>
            <h3 class="text-xl font-bold text-rose-600 mb-0"><?php echo number_format($kpis['total_expired'] ?? 0); ?> Records</h3>
        </div>
    </div>

    <div class="app-card p-4 border-l-4 border-l-rose-400 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-cubes"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Total Expired Units</p>
            <h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format($kpis['expired_units'] ?? 0); ?> Units</h3>
        </div>
    </div>

    <div class="app-card p-4 border-l-4 border-l-rose-600 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Total Financial Loss</p>
            <h3 class="text-xl font-bold text-rose-700 font-mono mb-0">$<?php echo number_format($kpis['expired_value'] ?? 0, 2); ?></h3>
        </div>
    </div>
</div>

<!-- SEARCH BAR -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('expiry/expired'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        <div class="sm:col-span-10">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Expired Medicines
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="<?php echo html_escape($search ?? ''); ?>" 
                       class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500" 
                       placeholder="Search by medicine name, category, supplier...">
            </div>
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-rose btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs bg-rose-600 text-white hover:bg-rose-700">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?php echo base_url('expiry/expired'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Search">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- EXPIRED MEDICINES TABLE -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 pl-5">Medicine</th>
                    <th class="py-3.5">Category</th>
                    <th class="py-3.5">Supplier</th>
                    <th class="py-3.5 text-center">Expired Units</th>
                    <th class="py-3.5">Expired On</th>
                    <th class="py-3.5 text-center">Overdue Duration</th>
                    <th class="py-3.5 text-end">Financial Loss</th>
                    <th class="py-3.5 text-end pr-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($medicines) && !empty($medicines)): ?>
                    <?php foreach ($medicines as $med): ?>
                        <?php
                        $days_overdue = (int)($med['days_overdue'] ?? 0);
                        $stock = (int)($med['stock_quantity'] ?? 0);
                        $loss = (float)($med['total_loss_value'] ?? ($stock * (float)$med['price']));
                        ?>
                        <tr class="hover:bg-rose-50/40 bg-rose-50/10 transition-colors">
                            <td class="py-3.5 pl-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        <img src="<?php echo html_escape($med['image_url'] ?: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=80&auto=format&fit=crop&q=80'); ?>" 
                                             alt="Medicine" class="w-full h-full object-cover grayscale opacity-75">
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block"><?php echo html_escape($med['medicine_name']); ?></span>
                                        <span class="text-[11px] text-slate-400 font-mono">$<?php echo number_format($med['price'], 2); ?> / unit</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 text-slate-600">
                                <span class="badge bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-[11px] font-medium">
                                    <?php echo html_escape($med['category_name'] ?: 'General'); ?>
                                </span>
                            </td>

                            <td class="py-3.5 text-slate-600 text-xs">
                                <?php echo html_escape($med['supplier_name'] ?: 'Direct Supply'); ?>
                            </td>

                            <td class="py-3.5 text-center">
                                <span class="font-bold font-mono text-rose-700 text-xs">
                                    <?php echo number_format($stock); ?>
                                </span>
                            </td>

                            <td class="py-3.5 font-mono text-xs text-rose-700 font-bold">
                                <?php echo date('d M Y', strtotime($med['expiry_date'])); ?>
                            </td>

                            <td class="py-3.5 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    <i class="fa-solid fa-ban text-[10px]"></i>
                                    <span><?php echo $days_overdue; ?> days overdue</span>
                                </span>
                            </td>

                            <td class="py-3.5 text-end font-mono font-bold text-xs text-rose-700">
                                -$<?php echo number_format($loss, 2); ?>
                            </td>

                            <td class="py-3.5 text-end pr-5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="<?php echo base_url('medicines/edit/' . $med['id']); ?>" 
                                       class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" 
                                       title="Edit Record">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="<?php echo base_url('medicines/show/' . $med['id']); ?>" 
                                       class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                       title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-shield-check text-4xl text-emerald-500 mb-3 block"></i>
                            <p class="text-sm font-semibold text-slate-700 mb-1">Zero Expired Medicines</p>
                            <p class="text-xs text-slate-400 mb-0">
                                <?php echo !empty($search) ? 'No expired items matching "' . html_escape($search) . '".' : 'Excellent! There are currently no expired items in inventory.'; ?>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <?php if (isset($pagination_links) && !empty($pagination_links)): ?>
        <div class="px-5 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <p class="text-xs text-slate-500 mb-0 font-medium">
                Showing <?php echo min($total_rows, ($offset ?? 0) + 1); ?> to <?php echo min($total_rows, ($offset ?? 0) + count($medicines)); ?> of <?php echo $total_rows; ?> expired items
            </p>
            <div>
                <?php echo $pagination_links; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
