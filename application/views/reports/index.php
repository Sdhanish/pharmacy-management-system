<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$type = $report_type ?? 'available';
$count_records = is_array($report_data) ? count($report_data) : 0;
?>

<!-- ==========================================
     PRINT-ONLY LETTERHEAD (Visible when Printing)
     ========================================== -->
<div class="print-header hidden mb-6 pb-4 border-b-2 border-slate-900">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 mb-0 uppercase">PharmaCare Management System</h1>
            <p class="text-xs text-slate-600 mb-0">Official Pharmacy Inventory & Audit Report &bull; ISO-9001 Dispensary Standard</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-slate-900 mb-0"><?php echo html_escape($report_title); ?></p>
            <p class="text-xs text-slate-500 mb-0">Generated: <?php echo date('d M Y, h:i A'); ?></p>
            <p class="text-[11px] text-slate-400 mb-0">Auditor: <?php echo html_escape($current_user['name'] ?? 'System Admin'); ?></p>
        </div>
    </div>
</div>

<!-- ==========================================
     SCREEN HEADER & ACTIONS
     ========================================== -->
<div class="no-print flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Pharmacy Reports & Analytics</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo $count_records; ?> <?php echo ($count_records === 1) ? 'Result' : 'Results'; ?>
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Generate inventory audits, stock valuations, low stock reorders, expiration risks, and transaction ledger logs.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <button onclick="window.print()" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition">
            <i class="fa-solid fa-print"></i>
            <span>Print Report</span>
        </button>
    </div>
</div>

<!-- ==========================================
     5 SUMMARY KPI CARDS (Screen Only)
     ========================================== -->
<div class="no-print grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 mb-6">
    <!-- 1. Available Stock -->
    <a href="<?php echo base_url('reports?type=available'); ?>" 
       class="app-card app-card-hover p-3.5 block text-decoration-none border-l-4 border-l-emerald-500 <?php echo ($type === 'available') ? 'ring-2 ring-emerald-500 shadow-sm' : ''; ?>">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Available Stock</span>
            <i class="fa-solid fa-boxes-stacked text-emerald-600 text-xs"></i>
        </div>
        <h4 class="text-lg font-extrabold text-slate-900 mb-0"><?php echo number_format($overview['available_units'] ?? 0); ?> <span class="text-xs font-normal text-slate-400">units</span></h4>
        <p class="text-[11px] text-emerald-700 font-mono font-bold mb-0 mt-1">$<?php echo number_format($overview['available_valuation'] ?? 0, 2); ?></p>
    </a>

    <!-- 2. Low Stock -->
    <a href="<?php echo base_url('reports?type=low_stock'); ?>" 
       class="app-card app-card-hover p-3.5 block text-decoration-none border-l-4 border-l-amber-500 <?php echo ($type === 'low_stock') ? 'ring-2 ring-amber-500 shadow-sm' : ''; ?>">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Low Stock Alert</span>
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xs"></i>
        </div>
        <h4 class="text-lg font-extrabold text-amber-600 mb-0"><?php echo number_format($overview['low_stock_count'] ?? 0); ?> <span class="text-xs font-normal text-slate-400">items</span></h4>
        <p class="text-[11px] text-amber-700 font-semibold mb-0 mt-1">&le; 10 Units Remaining</p>
    </a>

    <!-- 3. Expired Stock -->
    <a href="<?php echo base_url('reports?type=expired'); ?>" 
       class="app-card app-card-hover p-3.5 block text-decoration-none border-l-4 border-l-rose-500 <?php echo ($type === 'expired') ? 'ring-2 ring-rose-500 shadow-sm' : ''; ?>">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Expired Items</span>
            <i class="fa-solid fa-ban text-rose-600 text-xs"></i>
        </div>
        <h4 class="text-lg font-extrabold text-rose-600 mb-0"><?php echo number_format($overview['expired_count'] ?? 0); ?> <span class="text-xs font-normal text-slate-400">items</span></h4>
        <p class="text-[11px] text-rose-700 font-mono font-bold mb-0 mt-1">-$<?php echo number_format($overview['expired_loss'] ?? 0, 2); ?> Loss</p>
    </a>

    <!-- 4. Expiring Soon -->
    <a href="<?php echo base_url('reports?type=expiring_soon'); ?>" 
       class="app-card app-card-hover p-3.5 block text-decoration-none border-l-4 border-l-orange-500 <?php echo ($type === 'expiring_soon') ? 'ring-2 ring-orange-500 shadow-sm' : ''; ?>">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Expiring (30d)</span>
            <i class="fa-solid fa-clock-rotate-left text-orange-600 text-xs"></i>
        </div>
        <h4 class="text-lg font-extrabold text-orange-600 mb-0"><?php echo number_format($overview['expiring_soon_count'] ?? 0); ?> <span class="text-xs font-normal text-slate-400">items</span></h4>
        <p class="text-[11px] text-orange-700 font-semibold mb-0 mt-1">FIFO Priority</p>
    </a>

    <!-- 5. Stock Activities -->
    <a href="<?php echo base_url('reports?type=stock_activity'); ?>" 
       class="app-card app-card-hover p-3.5 block text-decoration-none border-l-4 border-l-purple-500 <?php echo ($type === 'stock_activity') ? 'ring-2 ring-purple-500 shadow-sm' : ''; ?>">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Stock Movements</span>
            <i class="fa-solid fa-clipboard-list text-purple-600 text-xs"></i>
        </div>
        <h4 class="text-lg font-extrabold text-purple-700 mb-0"><?php echo number_format($overview['total_activities'] ?? 0); ?> <span class="text-xs font-normal text-slate-400">logs</span></h4>
        <p class="text-[11px] text-purple-700 font-semibold mb-0 mt-1">Audit Trail</p>
    </a>
</div>

<!-- ==========================================
     REPORT SELECTOR TABS & FILTER FORM
     ========================================== -->
<div class="no-print app-card p-0 mb-6 overflow-hidden">

    <!-- Report Type Tabs -->
    <div class="flex flex-wrap items-center gap-2 p-4 border-b border-slate-100">
        <a href="<?php echo base_url('reports?type=available'); ?>"
           class="px-3.5 py-2 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($type === 'available') ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
            <i class="fa-solid fa-boxes-stacked text-[11px]"></i>
            <span>Available Medicines</span>
        </a>
        <a href="<?php echo base_url('reports?type=low_stock'); ?>"
           class="px-3.5 py-2 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($type === 'low_stock') ? 'bg-amber-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
            <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
            <span>Low Stock</span>
        </a>
        <a href="<?php echo base_url('reports?type=expired'); ?>"
           class="px-3.5 py-2 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($type === 'expired') ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
            <i class="fa-solid fa-ban text-[11px]"></i>
            <span>Expired</span>
        </a>
        <a href="<?php echo base_url('reports?type=expiring_soon'); ?>"
           class="px-3.5 py-2 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($type === 'expiring_soon') ? 'bg-orange-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
            <i class="fa-solid fa-clock-rotate-left text-[11px]"></i>
            <span>Expiring Soon</span>
        </a>
        <a href="<?php echo base_url('reports?type=stock_activity'); ?>"
           class="px-3.5 py-2 rounded-xl text-xs font-bold text-decoration-none transition flex items-center gap-1.5 <?php echo ($type === 'stock_activity') ? 'bg-purple-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
            <i class="fa-solid fa-clock text-[11px]"></i>
            <span>Stock Activity</span>
        </a>

        <!-- Filter Toggle Button (right side) -->
        <button type="button" id="toggleFilterBtn"
                class="ml-auto px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center gap-1.5 transition border border-slate-200">
            <i class="fa-solid fa-sliders text-[11px]"></i>
            <span>Filters</span>
            <i class="fa-solid fa-chevron-down text-[9px] transition-transform" id="filterChevron"></i>
        </button>
    </div>

    <!-- Collapsible Filter Form (hidden by default when results exist) -->
    <div id="filterPanel" class="<?php echo ($count_records > 0) ? 'hidden' : ''; ?> p-5 border-t border-slate-100">
        <form action="<?php echo base_url('reports'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
            <input type="hidden" name="type" value="<?php echo html_escape($type); ?>">

            <!-- Search Input -->
            <div class="sm:col-span-4">
                <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Search Medicine / Reference
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" id="search"
                           value="<?php echo html_escape($search ?? ''); ?>"
                           class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                           placeholder="Search keyword...">
                </div>
            </div>

            <!-- Category -->
            <div class="sm:col-span-3">
                <label for="category_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Category</label>
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

            <!-- Conditional Filters -->
            <?php if ($type === 'stock_activity'): ?>
                <div class="sm:col-span-2">
                    <label for="transaction_type" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Action Type</label>
                    <select name="transaction_type" id="transaction_type" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                        <option value="ALL" <?php echo ($transaction_type === 'ALL') ? 'selected' : ''; ?>>All Actions</option>
                        <option value="PURCHASE" <?php echo ($transaction_type === 'PURCHASE') ? 'selected' : ''; ?>>Purchase</option>
                        <option value="SALE" <?php echo ($transaction_type === 'SALE') ? 'selected' : ''; ?>>Sale</option>
                        <option value="ADJUSTMENT" <?php echo ($transaction_type === 'ADJUSTMENT') ? 'selected' : ''; ?>>Adjustment</option>
                        <option value="EXPIRED" <?php echo ($transaction_type === 'EXPIRED') ? 'selected' : ''; ?>>Expired Out</option>
                        <option value="RETURN" <?php echo ($transaction_type === 'RETURN') ? 'selected' : ''; ?>>Return</option>
                    </select>
                </div>
            <?php elseif ($type === 'expiring_soon'): ?>
                <div class="sm:col-span-2">
                    <label for="days" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Horizon</label>
                    <select name="days" id="days" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                        <option value="7" <?php echo ($days === 7) ? 'selected' : ''; ?>>Within 7 Days</option>
                        <option value="15" <?php echo ($days === 15) ? 'selected' : ''; ?>>Within 15 Days</option>
                        <option value="30" <?php echo ($days === 30) ? 'selected' : ''; ?>>Within 30 Days</option>
                        <option value="60" <?php echo ($days === 60) ? 'selected' : ''; ?>>Within 60 Days</option>
                        <option value="90" <?php echo ($days === 90) ? 'selected' : ''; ?>>Within 90 Days</option>
                    </select>
                </div>
            <?php elseif ($type === 'low_stock'): ?>
                <div class="sm:col-span-2">
                    <label for="threshold" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Threshold (&le;)</label>
                    <input type="number" name="threshold" id="threshold" min="1" max="100"
                           value="<?php echo html_escape($threshold); ?>"
                           class="form-control form-control-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                </div>
            <?php endif; ?>

            <!-- Date Range -->
            <div class="<?php echo in_array($type, ['stock_activity', 'expiring_soon', 'low_stock']) ? 'sm:col-span-3' : 'sm:col-span-5'; ?> grid grid-cols-2 gap-2">
                <div>
                    <label for="start_date" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">From</label>
                    <input type="date" name="start_date" id="start_date"
                           value="<?php echo html_escape($start_date ?? ''); ?>"
                           class="form-control form-control-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                </div>
                <div>
                    <label for="end_date" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">To</label>
                    <input type="date" name="end_date" id="end_date"
                           value="<?php echo html_escape($end_date ?? ''); ?>"
                           class="form-control form-control-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                </div>
            </div>

            <!-- Buttons -->
            <div class="sm:col-span-12 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <a href="<?php echo base_url('reports?type=' . urlencode($type)); ?>" class="btn btn-light btn-sm rounded-xl px-3.5 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center gap-1 text-decoration-none">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                    <span>Reset Filters</span>
                </a>
                <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Generate Filtered Report</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle filter panel
document.getElementById('toggleFilterBtn').addEventListener('click', function() {
    var panel   = document.getElementById('filterPanel');
    var chevron = document.getElementById('filterChevron');
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        panel.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
});
</script>


<!-- ==========================================
     REPORT RESULTS TABLE
     ========================================== -->
<div class="app-card p-0 overflow-hidden mb-6 shadow-sm border border-slate-200 print-card">
    
    <!-- Table Header Metadata -->
    <div class="no-print px-5 py-4 bg-slate-50/80 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-bold text-slate-900 mb-0.5 flex items-center gap-2">
                <i class="fa-solid fa-table-list text-emerald-600"></i>
                <span><?php echo html_escape($report_title); ?></span>
            </h3>
            <p class="text-xs text-slate-400 mb-0">
                Found <?php echo $count_records; ?> matching records &bull; Scope: <?php echo !empty($category_id) ? 'Specific Category' : 'All Categories'; ?>
            </p>
        </div>
        <div class="text-xs text-slate-500 font-mono">
            Generated: <?php echo $generated_at; ?>
        </div>
    </div>

    <div class="table-responsive">
        
        <!-- 1. AVAILABLE MEDICINES TABLE -->
        <?php if ($type === 'available'): ?>
            <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="reportTableAvailable">
                <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="sortable py-3 pl-5" data-sort="number">#</th>
                        <th class="sortable py-3" data-sort="text">Medicine Name</th>
                        <th class="sortable py-3" data-sort="text">Category</th>
                        <th class="sortable py-3" data-sort="text">Supplier / Brand</th>
                        <th class="sortable py-3 text-end" data-sort="number">Unit Price</th>
                        <th class="sortable py-3 text-center" data-sort="number">In Stock</th>
                        <th class="sortable py-3" data-sort="date">Expiry Date</th>
                        <th class="sortable py-3 text-end pr-5" data-sort="number">Total Valuation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $total_stock_sum = 0;
                    $total_val_sum = 0.00;
                    ?>
                    <?php if (!empty($report_data)): ?>
                        <?php foreach ($report_data as $index => $row): ?>
                            <?php 
                            $qty = (int)$row['stock_quantity'];
                            $val = (float)$row['total_valuation'];
                            $total_stock_sum += $qty;
                            $total_val_sum += $val;
                            ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 pl-5 font-mono text-slate-400 text-xs"><?php echo $index + 1; ?></td>
                                <td class="py-3 font-bold text-slate-900"><?php echo html_escape($row['medicine_name']); ?></td>
                                <td class="py-3 text-slate-600"><?php echo html_escape($row['category_name'] ?: 'Unassigned'); ?></td>
                                <td class="py-3 text-slate-500 text-xs"><?php echo html_escape($row['supplier_name'] ?: 'Direct Supply'); ?></td>
                                <td class="py-3 text-end font-mono text-slate-800">$<?php echo number_format($row['price'], 2); ?></td>
                                <td class="py-3 text-center font-bold font-mono text-emerald-700"><?php echo number_format($qty); ?></td>
                                <td class="py-3 font-mono text-xs"><?php echo date('d M Y', strtotime($row['expiry_date'])); ?></td>
                                <td class="py-3 text-end pr-5 font-mono font-bold text-slate-900">$<?php echo number_format($val, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Summation Footer Row -->
                        <tr class="bg-slate-100/80 font-bold border-t-2 border-slate-300">
                            <td colspan="5" class="py-3.5 pl-5 text-slate-800 uppercase text-xs tracking-wider">Total Available Valuation:</td>
                            <td class="py-3.5 text-center font-mono text-emerald-800 text-sm"><?php echo number_format($total_stock_sum); ?> units</td>
                            <td></td>
                            <td class="py-3.5 text-end pr-5 font-mono text-emerald-800 text-sm">$<?php echo number_format($total_val_sum, 2); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="8" class="py-8 text-center text-slate-400">No matching available medicines found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <!-- 2. LOW STOCK REPORT TABLE -->
        <?php elseif ($type === 'low_stock'): ?>
            <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="reportTableLowStock">
                <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="sortable py-3 pl-5" data-sort="number">#</th>
                        <th class="sortable py-3" data-sort="text">Medicine Name</th>
                        <th class="sortable py-3" data-sort="text">Category</th>
                        <th class="sortable py-3 text-center" data-sort="number">Current Stock</th>
                        <th class="sortable py-3 text-center" data-sort="number">Threshold</th>
                        <th class="sortable py-3 text-center" data-sort="number">Units to Reorder</th>
                        <th class="sortable py-3" data-sort="text">Supplier Contact</th>
                        <th class="sortable py-3 text-end pr-5" data-sort="number">Est. Reorder Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $total_reorder_units = 0;
                    $total_reorder_cost = 0.00;
                    ?>
                    <?php if (!empty($report_data)): ?>
                        <?php foreach ($report_data as $index => $row): ?>
                            <?php 
                            $to_reorder = (int)$row['units_to_reorder'];
                            $reorder_cost = (float)$row['estimated_reorder_cost'];
                            $total_reorder_units += $to_reorder;
                            $total_reorder_cost += $reorder_cost;
                            ?>
                            <tr class="hover:bg-amber-50/40 bg-amber-50/10 transition">
                                <td class="py-3 pl-5 font-mono text-slate-400 text-xs"><?php echo $index + 1; ?></td>
                                <td class="py-3 font-bold text-slate-900"><?php echo html_escape($row['medicine_name']); ?></td>
                                <td class="py-3 text-slate-600"><?php echo html_escape($row['category_name'] ?: 'General'); ?></td>
                                <td class="py-3 text-center font-bold font-mono text-rose-600"><?php echo $row['stock_quantity']; ?> left</td>
                                <td class="py-3 text-center font-mono text-slate-500"><?php echo $row['min_alert_threshold']; ?></td>
                                <td class="py-3 text-center font-bold font-mono text-amber-700">+<?php echo $to_reorder; ?> units</td>
                                <td class="py-3 text-xs text-slate-600">
                                    <span class="font-bold block"><?php echo html_escape($row['supplier_name'] ?: 'N/A'); ?></span>
                                    <span class="text-slate-400 text-[11px]"><?php echo html_escape($row['supplier_phone'] ?: ''); ?></span>
                                </td>
                                <td class="py-3 text-end pr-5 font-mono font-bold text-slate-900">$<?php echo number_format($reorder_cost, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Summation Footer Row -->
                        <tr class="bg-slate-100/80 font-bold border-t-2 border-slate-300">
                            <td colspan="5" class="py-3.5 pl-5 text-slate-800 uppercase text-xs tracking-wider">Total Reorder Estimate:</td>
                            <td class="py-3.5 text-center font-mono text-amber-800 text-sm">+<?php echo number_format($total_reorder_units); ?> units</td>
                            <td></td>
                            <td class="py-3.5 text-end pr-5 font-mono text-amber-800 text-sm">$<?php echo number_format($total_reorder_cost, 2); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="8" class="py-8 text-center text-slate-400">No low stock items detected below threshold.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <!-- 3. EXPIRED MEDICINES REPORT TABLE -->
        <?php elseif ($type === 'expired'): ?>
            <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="reportTableExpired">
                <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="sortable py-3 pl-5" data-sort="number">#</th>
                        <th class="sortable py-3" data-sort="text">Medicine Name</th>
                        <th class="sortable py-3" data-sort="text">Category</th>
                        <th class="sortable py-3" data-sort="text">Supplier</th>
                        <th class="sortable py-3 text-center" data-sort="number">Expired Units</th>
                        <th class="sortable py-3" data-sort="date">Expiry Date</th>
                        <th class="sortable py-3 text-center" data-sort="text">Status</th>
                        <th class="sortable py-3 text-end pr-5" data-sort="number">Financial Loss</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $total_expired_units = 0;
                    $total_loss_val = 0.00;
                    ?>
                    <?php if (!empty($report_data)): ?>
                        <?php foreach ($report_data as $index => $row): ?>
                            <?php 
                            $units = (int)$row['stock_quantity'];
                            $loss = (float)$row['financial_loss'];
                            $total_expired_units += $units;
                            $total_loss_val += $loss;
                            ?>
                            <tr class="hover:bg-rose-50/40 bg-rose-50/10 transition">
                                <td class="py-3 pl-5 font-mono text-slate-400 text-xs"><?php echo $index + 1; ?></td>
                                <td class="py-3 font-bold text-slate-900"><?php echo html_escape($row['medicine_name']); ?></td>
                                <td class="py-3 text-slate-600"><?php echo html_escape($row['category_name'] ?: 'General'); ?></td>
                                <td class="py-3 text-slate-500 text-xs"><?php echo html_escape($row['supplier_name'] ?: 'Direct Supply'); ?></td>
                                <td class="py-3 text-center font-bold font-mono text-rose-700"><?php echo number_format($units); ?></td>
                                <td class="py-3 font-mono text-xs text-rose-700"><?php echo date('d M Y', strtotime($row['expiry_date'])); ?></td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-rose-100 text-rose-800 font-semibold px-2 py-1 rounded-md text-[11px]">
                                        <?php echo $row['days_overdue']; ?>d Overdue
                                    </span>
                                </td>
                                <td class="py-3 text-end pr-5 font-mono font-bold text-rose-700">-$<?php echo number_format($loss, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Summation Footer Row -->
                        <tr class="bg-slate-100/80 font-bold border-t-2 border-slate-300">
                            <td colspan="4" class="py-3.5 pl-5 text-slate-800 uppercase text-xs tracking-wider">Total Expired Loss:</td>
                            <td class="py-3.5 text-center font-mono text-rose-800 text-sm"><?php echo number_format($total_expired_units); ?> units</td>
                            <td colspan="2"></td>
                            <td class="py-3.5 text-end pr-5 font-mono text-rose-800 text-sm">-$<?php echo number_format($total_loss_val, 2); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="8" class="py-8 text-center text-slate-400">No expired medicines in the selected scope.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <!-- 4. EXPIRING SOON REPORT TABLE -->
        <?php elseif ($type === 'expiring_soon'): ?>
            <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="reportTableExpiringSoon">
                <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="sortable py-3 pl-5" data-sort="number">#</th>
                        <th class="sortable py-3" data-sort="text">Medicine Name</th>
                        <th class="sortable py-3" data-sort="text">Category</th>
                        <th class="sortable py-3" data-sort="text">Supplier</th>
                        <th class="sortable py-3 text-center" data-sort="number">Stock Units</th>
                        <th class="sortable py-3" data-sort="date">Expiry Date</th>
                        <th class="sortable py-3 text-center" data-sort="text">Time Remaining</th>
                        <th class="sortable py-3 text-end pr-5" data-sort="number">Value At Risk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $total_risk_units = 0;
                    $total_risk_val = 0.00;
                    ?>
                    <?php if (!empty($report_data)): ?>
                        <?php foreach ($report_data as $index => $row): ?>
                            <?php 
                            $units = (int)$row['stock_quantity'];
                            $risk_val = (float)$row['value_at_risk'];
                            $days_left = (int)$row['days_left'];
                            $total_risk_units += $units;
                            $total_risk_val += $risk_val;
                            ?>
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="py-3 pl-5 font-mono text-slate-400 text-xs"><?php echo $index + 1; ?></td>
                                <td class="py-3 font-bold text-slate-900"><?php echo html_escape($row['medicine_name']); ?></td>
                                <td class="py-3 text-slate-600"><?php echo html_escape($row['category_name'] ?: 'General'); ?></td>
                                <td class="py-3 text-slate-500 text-xs"><?php echo html_escape($row['supplier_name'] ?: 'Direct Supply'); ?></td>
                                <td class="py-3 text-center font-bold font-mono text-slate-800"><?php echo number_format($units); ?></td>
                                <td class="py-3 font-mono text-xs"><?php echo date('d M Y', strtotime($row['expiry_date'])); ?></td>
                                <td class="py-3 text-center">
                                    <?php if ($days_left <= 7): ?>
                                        <span class="badge bg-orange-100 text-orange-800 font-bold px-2 py-1 rounded-md text-[11px]">
                                            <?php echo $days_left; ?> days (Urgent)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-amber-100 text-amber-800 font-semibold px-2 py-1 rounded-md text-[11px]">
                                            <?php echo $days_left; ?> days left
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-end pr-5 font-mono font-bold text-slate-900">$<?php echo number_format($risk_val, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Summation Footer Row -->
                        <tr class="bg-slate-100/80 font-bold border-t-2 border-slate-300">
                            <td colspan="4" class="py-3.5 pl-5 text-slate-800 uppercase text-xs tracking-wider">Total At-Risk Valuation:</td>
                            <td class="py-3.5 text-center font-mono text-orange-800 text-sm"><?php echo number_format($total_risk_units); ?> units</td>
                            <td colspan="2"></td>
                            <td class="py-3.5 text-end pr-5 font-mono text-orange-800 text-sm">$<?php echo number_format($total_risk_val, 2); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="8" class="py-8 text-center text-slate-400">No medicines expiring within the specified horizon.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <!-- 5. STOCK ACTIVITY REPORT TABLE -->
        <?php elseif ($type === 'stock_activity'): ?>
            <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="reportTableStockActivity">
                <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="sortable py-3 pl-5" data-sort="number">#</th>
                        <th class="sortable py-3" data-sort="date">Date & Time</th>
                        <th class="sortable py-3" data-sort="text">Medicine</th>
                        <th class="sortable py-3" data-sort="text">Category</th>
                        <th class="sortable py-3 text-center" data-sort="text">Action Type</th>
                        <th class="sortable py-3 text-center" data-sort="number">Quantity</th>
                        <th class="sortable py-3 text-center" data-sort="number">Balance After</th>
                        <th class="sortable py-3" data-sort="text">User & Ref</th>
                        <th class="sortable py-3 text-end pr-5" data-sort="number">Transaction Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $total_activity_val = 0.00;
                    ?>
                    <?php if (!empty($report_data)): ?>
                        <?php foreach ($report_data as $index => $row): ?>
                            <?php 
                            $act_type = strtoupper($row['transaction_type'] ?? 'SALE');
                            $qty = (int)$row['quantity'];
                            $tx_val = (float)$row['transaction_valuation'];
                            $total_activity_val += $tx_val;

                            switch ($act_type) {
                                case 'PURCHASE':
                                    $b_class = 'bg-emerald-100 text-emerald-800';
                                    $q_class = 'text-emerald-700 font-bold';
                                    $sign = '+' . abs($qty);
                                    break;
                                case 'SALE':
                                    $b_class = 'bg-blue-100 text-blue-800';
                                    $q_class = 'text-rose-600 font-bold';
                                    $sign = '-' . abs($qty);
                                    break;
                                case 'EXPIRED':
                                    $b_class = 'bg-rose-100 text-rose-800';
                                    $q_class = 'text-rose-600 font-bold';
                                    $sign = '-' . abs($qty);
                                    break;
                                default:
                                    $b_class = 'bg-amber-100 text-amber-800';
                                    $q_class = 'text-amber-700 font-bold';
                                    $sign = ($qty >= 0) ? '+' . $qty : (string)$qty;
                                    break;
                            }
                            ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 pl-5 font-mono text-slate-400 text-xs"><?php echo $index + 1; ?></td>
                                <td class="py-3 font-mono text-xs"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                                <td class="py-3 font-bold text-slate-900"><?php echo html_escape($row['medicine_name']); ?></td>
                                <td class="py-3 text-slate-600 text-xs"><?php echo html_escape($row['category_name'] ?: 'General'); ?></td>
                                <td class="py-3 text-center">
                                    <span class="badge <?php echo $b_class; ?> font-semibold px-2.5 py-1 rounded-md text-[10px]">
                                        <?php echo $act_type; ?>
                                    </span>
                                </td>
                                <td class="py-3 text-center font-mono <?php echo $q_class; ?>"><?php echo $sign; ?></td>
                                <td class="py-3 text-center font-mono font-bold text-slate-800"><?php echo $row['balance_after']; ?></td>
                                <td class="py-3 text-xs">
                                    <span class="font-semibold block text-slate-800"><?php echo html_escape($row['user_name'] ?: 'System'); ?></span>
                                    <span class="font-mono text-[10px] text-slate-400"><?php echo html_escape($row['reference_no'] ?: 'N/A'); ?></span>
                                </td>
                                <td class="py-3 text-end pr-5 font-mono font-bold text-slate-900">$<?php echo number_format($tx_val, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Summation Footer Row -->
                        <tr class="bg-slate-100/80 font-bold border-t-2 border-slate-300">
                            <td colspan="8" class="py-3.5 pl-5 text-slate-800 uppercase text-xs tracking-wider">Total Transaction Volume:</td>
                            <td class="py-3.5 text-end pr-5 font-mono text-purple-800 text-sm">$<?php echo number_format($total_activity_val, 2); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="9" class="py-8 text-center text-slate-400">No stock activity entries found for the selected criteria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>

<!-- ==========================================
     PRINT-ONLY FOOTER SIGNATURE BLOCK
     ========================================== -->
<div class="print-footer hidden mt-12 pt-6 border-t border-slate-300">
    <div class="grid grid-cols-2 gap-8">
        <div>
            <p class="text-xs font-bold text-slate-700 uppercase mb-8">Prepared By (Pharmacist / Auditor):</p>
            <div class="w-48 border-b border-slate-900 mb-1"></div>
            <p class="text-xs text-slate-500 mb-0">Signature & Date</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold text-slate-700 uppercase mb-8">Verified & Approved By (Chief Administrator):</p>
            <div class="w-48 border-b border-slate-900 mb-1 ml-auto"></div>
            <p class="text-xs text-slate-500 mb-0">Official Stamp & Date</p>
        </div>
    </div>
</div>
