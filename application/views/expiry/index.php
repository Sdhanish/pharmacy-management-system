<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Expiry Management & Alerts</h2>
            <span class="badge bg-rose-100 text-rose-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows ?? 0); ?> Alert Items
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Monitor expiration horizons, quarantine expired drugs, and manage clearance for soon-to-expire pharmaceutical batches.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('expiry/expired'); ?>" class="btn btn-outline-danger text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-xs text-decoration-none">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>View Expired (<?php echo $kpis['total_expired'] ?? 0; ?>)</span>
        </a>
        <a href="<?php echo base_url('expiry/expiring-7-days'); ?>" class="btn btn-warning text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-xs text-decoration-none text-slate-900">
            <i class="fa-solid fa-clock"></i>
            <span>7-Day Critical (<?php echo $kpis['expiring_7_days'] ?? 0; ?>)</span>
        </a>
    </div>
</div>

<!-- Flash Notifications -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-emerald-200 bg-emerald-50 text-emerald-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span><?php echo $this->session->flashdata('success'); ?></span>
        </div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
        </div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- 4 SUMMARY METRIC CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    <!-- CARD 1: Expired Medicines -->
    <a href="<?php echo base_url('expiry/expired'); ?>" class="app-card app-card-hover p-4 border-l-4 border-l-rose-500 block text-decoration-none group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-600 transition">Expired Drugs</span>
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-rose-600 mb-0"><?php echo number_format($kpis['total_expired'] ?? 0); ?></h3>
        <p class="text-[11px] text-slate-400 mb-0 mt-1 flex items-center justify-between">
            <span><?php echo number_format($kpis['expired_units'] ?? 0); ?> Units</span>
            <span class="font-bold text-rose-700 font-mono">$<?php echo number_format($kpis['expired_value'] ?? 0, 2); ?> Loss</span>
        </p>
    </a>

    <!-- CARD 2: Expiring in 7 Days (Critical) -->
    <a href="<?php echo base_url('expiry/expiring-7-days'); ?>" class="app-card app-card-hover p-4 border-l-4 border-l-orange-500 block text-decoration-none group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-orange-600 transition">Critical (≤ 7 Days)</span>
            <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-hourglass-start animate-pulse"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-orange-600 mb-0"><?php echo number_format($kpis['expiring_7_days'] ?? 0); ?></h3>
        <p class="text-[11px] text-slate-400 mb-0 mt-1 flex items-center justify-between">
            <span>Immediate Action</span>
            <span class="text-orange-700 font-semibold font-mono">Urgent Clearance</span>
        </p>
    </a>

    <!-- CARD 3: Expiring in 30 Days -->
    <a href="<?php echo base_url('expiry/expiring-30-days'); ?>" class="app-card app-card-hover p-4 border-l-4 border-l-amber-500 block text-decoration-none group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-amber-600 transition">Watchlist (≤ 30 Days)</span>
            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-amber-600 mb-0"><?php echo number_format($kpis['expiring_30_days'] ?? 0); ?></h3>
        <p class="text-[11px] text-slate-400 mb-0 mt-1 flex items-center justify-between">
            <span>Approaching Horizon</span>
            <span class="text-amber-700 font-semibold">Priority Dispensing</span>
        </p>
    </a>

    <!-- CARD 4: Total Value at Risk -->
    <div class="app-card p-4 border-l-4 border-l-purple-500">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Value At Risk</span>
            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-purple-700 font-mono mb-0">$<?php echo number_format($kpis['total_risk_value'] ?? 0, 2); ?></h3>
        <p class="text-[11px] text-slate-400 mb-0 mt-1">
            Across <?php echo number_format($kpis['total_risk_units'] ?? 0); ?> total at-risk medicine units
        </p>
    </div>
</div>

<!-- FILTER TABS & SEARCH BAR -->
<div class="app-card p-4 sm:p-5 mb-6">
    <!-- Tabs Header -->
    <div class="flex flex-wrap items-center gap-2 pb-4 mb-4 border-b border-slate-100">
        <a href="<?php echo base_url('expiry?filter=all' . (!empty($search) ? '&search=' . urlencode($search) : '')); ?>" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($current_filter === 'all') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
            <span>All Alerts</span>
        </a>

        <a href="<?php echo base_url('expiry?filter=expired' . (!empty($search) ? '&search=' . urlencode($search) : '')); ?>" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($current_filter === 'expired') ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100'; ?>">
            <i class="fa-solid fa-ban text-[10px]"></i>
            <span>Expired (<?php echo $kpis['total_expired'] ?? 0; ?>)</span>
        </a>

        <a href="<?php echo base_url('expiry?filter=7days' . (!empty($search) ? '&search=' . urlencode($search) : '')); ?>" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($current_filter === '7days') ? 'bg-orange-600 text-white shadow-sm' : 'bg-orange-50 text-orange-700 hover:bg-orange-100'; ?>">
            <i class="fa-solid fa-hourglass-start text-[10px]"></i>
            <span>Within 7 Days (<?php echo $kpis['expiring_7_days'] ?? 0; ?>)</span>
        </a>

        <a href="<?php echo base_url('expiry?filter=30days' . (!empty($search) ? '&search=' . urlencode($search) : '')); ?>" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($current_filter === '30days') ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'; ?>">
            <i class="fa-solid fa-clock text-[10px]"></i>
            <span>Within 30 Days (<?php echo $kpis['expiring_30_days'] ?? 0; ?>)</span>
        </a>
    </div>

    <!-- Search Form -->
    <form action="<?php echo base_url('expiry'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        <input type="hidden" name="filter" value="<?php echo html_escape($current_filter); ?>">
        
        <div class="sm:col-span-10">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Alert Inventory
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
                       placeholder="Search by medicine name, therapeutic category, supplier...">
            </div>
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?php echo base_url('expiry?filter=' . urlencode($current_filter)); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Search">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- MEDICINES TABLE WITH STATUS BADGES -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="expiryTable">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="sortable py-3.5 pl-5" data-sort="text">Medicine</th>
                    <th class="sortable py-3.5" data-sort="text">Category</th>
                    <th class="sortable py-3.5" data-sort="text">Supplier</th>
                    <th class="sortable py-3.5 text-center" data-sort="number">Stock Units</th>
                    <th class="sortable py-3.5" data-sort="date">Expiration Date</th>
                    <th class="sortable py-3.5 text-center" data-sort="text">Urgency Status</th>
                    <th class="sortable py-3.5 text-end" data-sort="number">Est. Value</th>
                    <th class="py-3.5 text-end pr-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($medicines) && !empty($medicines)): ?>
                    <?php foreach ($medicines as $med): ?>
                        <?php
                        $days_left = (int)($med['days_left'] ?? 0);
                        $is_expired = ($days_left < 0);
                        $is_today = ($days_left === 0);
                        $is_7_days = (!$is_expired && $days_left <= 7);
                        $is_30_days = (!$is_expired && $days_left > 7 && $days_left <= 30);
                        $stock = (int)($med['stock_quantity'] ?? 0);
                        $val = $stock * (float)($med['price'] ?? 0);
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors <?php echo $is_expired ? 'bg-rose-50/20' : ($is_7_days ? 'bg-orange-50/20' : ''); ?>">
                            <!-- Medicine Info -->
                            <td class="py-3.5 pl-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        <img src="<?php echo html_escape($med['image_url'] ?: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=80&auto=format&fit=crop&q=80'); ?>" 
                                             alt="Medicine" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block"><?php echo html_escape($med['medicine_name']); ?></span>
                                        <span class="text-[11px] text-slate-400 font-mono">$<?php echo number_format($med['price'], 2); ?> / unit</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3.5 text-slate-600">
                                <span class="badge bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-[11px] font-medium">
                                    <?php echo html_escape($med['category_name'] ?: 'General'); ?>
                                </span>
                            </td>

                            <!-- Supplier -->
                            <td class="py-3.5 text-slate-600 text-xs">
                                <?php echo html_escape($med['supplier_name'] ?: 'Direct Consignment'); ?>
                            </td>

                            <!-- Stock -->
                            <td class="py-3.5 text-center">
                                <span class="font-bold font-mono text-slate-800 text-xs">
                                    <?php echo number_format($stock); ?>
                                </span>
                            </td>

                            <!-- Expiry Date -->
                            <td class="py-3.5 font-mono text-xs">
                                <span class="font-bold <?php echo $is_expired ? 'text-rose-600' : ($is_7_days ? 'text-orange-600' : 'text-slate-800'); ?>">
                                    <?php echo date('d M Y', strtotime($med['expiry_date'])); ?>
                                </span>
                            </td>

                            <!-- Urgency Status Badge -->
                            <td class="py-3.5 text-center">
                                <?php if ($is_expired): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <i class="fa-solid fa-ban text-[10px]"></i>
                                        <span>Expired (<?php echo abs($days_left); ?>d ago)</span>
                                    </span>
                                <?php elseif ($is_today): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-600 text-white shadow-xs animate-pulse">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                        <span>Expires Today!</span>
                                    </span>
                                <?php elseif ($is_7_days): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-100 text-orange-800 border border-orange-200 animate-pulse">
                                        <i class="fa-solid fa-hourglass-start text-[10px]"></i>
                                        <span>Urgent: <?php echo $days_left; ?>d left</span>
                                    </span>
                                <?php elseif ($is_30_days): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                        <span>Expiring: <?php echo $days_left; ?>d left</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Good (> 30d)</span>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Value at Risk -->
                            <td class="py-3.5 text-end font-mono font-bold text-xs <?php echo $is_expired ? 'text-rose-700' : 'text-slate-800'; ?>">
                                $<?php echo number_format($val, 2); ?>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 text-end pr-5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="<?php echo base_url('medicines/edit/' . $med['id']); ?>" 
                                       class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" 
                                       title="Edit Medicine / Batch Details">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="<?php echo base_url('medicines/show/' . $med['id']); ?>" 
                                       class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                       title="View Full Medicine Profile">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clipboard-check text-4xl text-emerald-500 mb-3 block"></i>
                            <p class="text-sm font-semibold text-slate-700 mb-1">No Expiry Alerts Found</p>
                            <p class="text-xs text-slate-400 mb-3">
                                <?php echo !empty($search) ? 'No medicines matched your alert search criteria "' . html_escape($search) . '".' : 'All medicines in inventory are healthy and outside the immediate alert horizons.'; ?>
                            </p>
                            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold inline-flex items-center gap-2">
                                <i class="fa-solid fa-pills"></i>
                                <span>Browse Catalog</span>
                            </a>
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
                Showing <?php echo min($total_rows, ($offset ?? 0) + 1); ?> to <?php echo min($total_rows, ($offset ?? 0) + count($medicines)); ?> of <?php echo $total_rows; ?> alert items
            </p>
            <div>
                <?php echo $pagination_links; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
