<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$user_display_name = isset($current_user['name']) ? $current_user['name'] : 'Doctor';
?>

<!-- Welcome Banner -->
<div class="mb-8 rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/10 relative overflow-hidden">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/40 border border-emerald-400/30 text-xs font-semibold text-emerald-100 mb-3 backdrop-blur-sm">
                <i class="fa-solid fa-heart-pulse text-emerald-300"></i>
                <span>Pharmacy Live Dashboard &bull; <?php echo date('l, d F Y'); ?></span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">
                Welcome back, <?php echo html_escape($user_display_name); ?>!
            </h2>
            <p class="text-emerald-100 text-xs sm:text-sm max-w-xl mb-0 leading-relaxed">
                Live pharmaceutical inventory overview, stock movement audits, and prescription dispensary activity.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light text-emerald-800 font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:bg-emerald-50 transition flex items-center gap-2 text-xs sm:text-sm border-0">
                <i class="fa-solid fa-plus text-emerald-600"></i>
                <span>Add Medicine</span>
            </a>
            <a href="<?php echo base_url('stock'); ?>" class="btn bg-emerald-900/60 text-white font-semibold px-4 py-2.5 rounded-xl hover:bg-emerald-900/80 transition flex items-center gap-2 text-xs sm:text-sm border border-emerald-400/30">
                <i class="fa-solid fa-boxes-stacked text-emerald-300"></i>
                <span>New Stock In</span>
            </a>
        </div>
    </div>
    <!-- Decorative Ambient Glows -->
    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-12 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>
</div>

<!-- 5 REQUIRED DASHBOARD CARDS GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-5 mb-8">
    
    <!-- CARD 1: Total Medicines -->
    <div class="app-card app-card-hover p-5 border-l-4 border-l-emerald-500 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Medicines</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-pills text-lg"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-0">
                <?php echo number_format(isset($stats['total_medicines']) ? $stats['total_medicines'] : 0); ?>
            </h3>
            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Catalog</span>
        </div>
        <p class="text-xs text-slate-400 mt-2 mb-0 flex items-center justify-between">
            <span>Active SKUs</span>
            <a href="<?php echo base_url('medicines'); ?>" class="text-emerald-600 font-semibold hover:underline text-[11px]">View all &rarr;</a>
        </p>
    </div>

    <!-- CARD 2: Total Stock Quantity -->
    <div class="app-card app-card-hover p-5 border-l-4 border-l-cyan-500 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Stock Qty</span>
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cubes-stacked text-lg"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-0">
                <?php echo number_format(isset($stats['total_stock_quantity']) ? $stats['total_stock_quantity'] : 0); ?>
            </h3>
            <span class="text-[11px] font-semibold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-200">Units</span>
        </div>
        <p class="text-xs text-slate-400 mt-2 mb-0 flex items-center justify-between">
            <span>All inventory batches</span>
            <a href="<?php echo base_url('stock'); ?>" class="text-cyan-600 font-semibold hover:underline text-[11px]">Manage &rarr;</a>
        </p>
    </div>

    <!-- CARD 3: Low Stock Medicines -->
    <div class="app-card app-card-hover p-5 border-l-4 border-l-amber-500 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Low Stock Alert</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600 mb-0">
                <?php echo number_format(isset($stats['low_stock_medicines']) ? $stats['low_stock_medicines'] : 0); ?>
            </h3>
            <?php if (($stats['low_stock_medicines'] ?? 0) > 0): ?>
                <span class="text-[11px] font-semibold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-md animate-pulse">Reorder</span>
            <?php else: ?>
                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">Adequate</span>
            <?php endif; ?>
        </div>
        <p class="text-xs text-slate-400 mt-2 mb-0 flex items-center justify-between">
            <span>Below safety margin</span>
            <a href="<?php echo base_url('stock'); ?>" class="text-amber-600 font-semibold hover:underline text-[11px]">Restock &rarr;</a>
        </p>
    </div>

    <!-- CARD 4: Expired Medicines -->
    <div class="app-card app-card-hover p-5 border-l-4 border-l-rose-500 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Expired Medicines</span>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-rose-600 mb-0">
                <?php echo number_format(isset($stats['expired_medicines']) ? $stats['expired_medicines'] : 0); ?>
            </h3>
            <?php if (($stats['expired_medicines'] ?? 0) > 0): ?>
                <span class="text-[11px] font-semibold text-rose-800 bg-rose-100 px-2 py-0.5 rounded-md">Quarantine</span>
            <?php else: ?>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">None</span>
            <?php endif; ?>
        </div>
        <p class="text-xs text-slate-400 mt-2 mb-0 flex items-center justify-between">
            <span>Past expiration date</span>
            <a href="<?php echo base_url('reports'); ?>" class="text-rose-600 font-semibold hover:underline text-[11px]">Audit &rarr;</a>
        </p>
    </div>

    <!-- CARD 5: Expiring Soon Medicines -->
    <div class="app-card app-card-hover p-5 border-l-4 border-l-purple-500 relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Expiring Soon</span>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-hourglass-half text-lg"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-purple-700 mb-0">
                <?php echo number_format(isset($stats['expiring_soon']) ? $stats['expiring_soon'] : 0); ?>
            </h3>
            <span class="text-[11px] font-semibold text-purple-800 bg-purple-100 px-2 py-0.5 rounded-md">&le; 30 Days</span>
        </div>
        <p class="text-xs text-slate-400 mt-2 mb-0 flex items-center justify-between">
            <span>Requires attention</span>
            <a href="<?php echo base_url('stock-history'); ?>" class="text-purple-600 font-semibold hover:underline text-[11px]">Inspect &rarr;</a>
        </p>
    </div>

</div>

<!-- MAIN SECTION: RECENT ACTIVITY TABLE & QUICK ATTENTION WIDGETS -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- LEFT 2 COLS: RECENT ACTIVITY TABLE -->
    <div class="lg:col-span-2 app-card p-5 sm:p-6 flex flex-col justify-between">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clock-rotate-left text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900 mb-0.5">Recent Activity</h4>
                        <p class="text-xs text-slate-400 mb-0">Live log of stock receipts, dispensations, and adjustments</p>
                    </div>
                </div>
                <a href="<?php echo base_url('stock-history'); ?>" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5 text-decoration-none">
                    <span>Full Activity Log</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Activity Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
                    <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="border-0 rounded-start py-3 pl-3">Medicine</th>
                            <th class="border-0 text-center py-3">Action</th>
                            <th class="border-0 text-center py-3">Quantity</th>
                            <th class="border-0 text-end rounded-end py-3 pr-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (isset($recent_activities) && !empty($recent_activities)): ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <?php
                                $type = strtoupper($activity['transaction_type'] ?? 'SALE');
                                $qty = (int) ($activity['quantity'] ?? 0);
                                $created_time = strtotime($activity['created_at'] ?? 'now');
                                $time_formatted = date('M d, Y &bull; h:i A', $created_time);
                                
                                // Badge styling per action type
                                switch ($type) {
                                    case 'PURCHASE':
                                        $badge_class = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                        $icon = 'fa-arrow-down-left';
                                        $action_text = 'Purchase In';
                                        $qty_class = 'text-emerald-600 font-bold';
                                        $qty_sign = '+' . abs($qty);
                                        break;
                                    case 'SALE':
                                        $badge_class = 'bg-blue-100 text-blue-800 border-blue-200';
                                        $icon = 'fa-arrow-up-right';
                                        $action_text = 'Dispensed';
                                        $qty_class = 'text-rose-600 font-bold';
                                        $qty_sign = '-' . abs($qty);
                                        break;
                                    case 'EXPIRED':
                                        $badge_class = 'bg-rose-100 text-rose-800 border-rose-200';
                                        $icon = 'fa-circle-xmark';
                                        $action_text = 'Expired Out';
                                        $qty_class = 'text-rose-600 font-bold';
                                        $qty_sign = '-' . abs($qty);
                                        break;
                                    case 'RETURN':
                                        $badge_class = 'bg-teal-100 text-teal-800 border-teal-200';
                                        $icon = 'fa-rotate-left';
                                        $action_text = 'Returned';
                                        $qty_class = 'text-teal-600 font-bold';
                                        $qty_sign = '+' . abs($qty);
                                        break;
                                    default: // ADJUSTMENT
                                        $badge_class = 'bg-amber-100 text-amber-800 border-amber-200';
                                        $icon = 'fa-sliders';
                                        $action_text = 'Adjustment';
                                        $qty_class = ($qty >= 0) ? 'text-emerald-600 font-bold' : 'text-rose-600 font-bold';
                                        $qty_sign = ($qty >= 0) ? '+' . $qty : (string)$qty;
                                        break;
                                }
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <!-- 1. Medicine Column -->
                                    <td class="py-3 pl-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-tablets text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 mb-0 text-xs sm:text-sm">
                                                    <?php echo html_escape($activity['medicine_name'] ?? 'Medicine Item'); ?>
                                                </p>
                                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                                    <span><?php echo html_escape($activity['dosage_form'] ?? ''); ?></span>
                                                    <?php if (!empty($activity['strength'])): ?>
                                                        <span>&bull;</span>
                                                        <span class="font-semibold text-slate-600"><?php echo html_escape($activity['strength']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($activity['reference_no'])): ?>
                                                        <span>&bull;</span>
                                                        <span class="font-mono text-[10px] text-slate-400"><?php echo html_escape($activity['reference_no']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Action Column -->
                                    <td class="py-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $badge_class; ?>">
                                            <i class="fa-solid <?php echo $icon; ?> text-[10px]"></i>
                                            <span><?php echo $action_text; ?></span>
                                        </span>
                                    </td>

                                    <!-- 3. Quantity Column -->
                                    <td class="py-3 text-center">
                                        <span class="<?php echo $qty_class; ?> text-sm font-mono">
                                            <?php echo $qty_sign; ?>
                                        </span>
                                        <?php if (isset($activity['balance_after'])): ?>
                                            <span class="block text-[10px] text-slate-400 font-normal">Bal: <?php echo $activity['balance_after']; ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- 4. Date Column -->
                                    <td class="py-3 text-end pr-3">
                                        <div class="text-xs font-medium text-slate-700 font-mono">
                                            <?php echo $time_formatted; ?>
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            <?php echo html_escape($activity['user_name'] ?? 'System User'); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-inbox text-2xl mb-2 text-slate-300"></i>
                                    <p class="mb-0 text-xs">No stock transactions recorded yet.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-4 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>Displaying latest <?php echo count($recent_activities ?? []); ?> activities</span>
            <a href="<?php echo base_url('stock-history'); ?>" class="text-emerald-600 font-semibold hover:underline">View full ledger &rarr;</a>
        </div>
    </div>

    <!-- RIGHT 1 COL: INVENTORY HEALTH & ALERTS -->
    <div class="space-y-6">
        
        <!-- Low Stock Notice Panel -->
        <div class="app-card p-5">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                    <h5 class="text-sm font-bold text-slate-900 mb-0">Low Stock Priority</h5>
                </div>
                <a href="<?php echo base_url('stock'); ?>" class="text-[11px] font-semibold text-amber-600 hover:underline">Restock All</a>
            </div>

            <?php if (!empty($low_stock_items)): ?>
                <div class="space-y-2.5">
                    <?php foreach ($low_stock_items as $low_item): ?>
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/60 border border-amber-200/70">
                            <div>
                                <p class="text-xs font-bold text-slate-800 mb-0.5">
                                    <?php echo html_escape($low_item['name']); ?>
                                </p>
                                <p class="text-[10px] text-slate-500 mb-0">
                                    <?php echo html_escape($low_item['strength'] ?? ''); ?> &bull; Min Alert: <?php echo $low_item['min_stock_alert']; ?>
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-rose-600 text-white font-mono text-xs font-bold px-2 py-1 rounded-lg">
                                    <?php echo $low_item['current_stock']; ?> Left
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                    <i class="fa-solid fa-check-circle text-emerald-500 text-lg mb-1"></i>
                    <p class="mb-0 font-medium text-slate-600">All medicines have adequate stock levels.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Expiring Soon Notice Panel -->
        <div class="app-card p-5">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                    <h5 class="text-sm font-bold text-slate-900 mb-0">Expiring (&le; 30 Days)</h5>
                </div>
                <a href="<?php echo base_url('reports'); ?>" class="text-[11px] font-semibold text-purple-600 hover:underline">Expiry Report</a>
            </div>

            <?php if (!empty($expiring_soon_items)): ?>
                <div class="space-y-2.5">
                    <?php foreach ($expiring_soon_items as $exp_item): ?>
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-purple-50/60 border border-purple-200/70">
                            <div>
                                <p class="text-xs font-bold text-slate-800 mb-0.5">
                                    <?php echo html_escape($exp_item['medicine_name']); ?>
                                </p>
                                <p class="text-[10px] text-slate-500 mb-0">
                                    Batch #<?php echo html_escape($exp_item['batch_number']); ?> &bull; Qty: <?php echo $exp_item['quantity']; ?>
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-purple-200 text-purple-900 font-mono text-[11px] font-semibold px-2 py-1 rounded-lg">
                                    <?php echo $exp_item['days_left']; ?> days
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                    <i class="fa-solid fa-calendar-check text-emerald-500 text-lg mb-1"></i>
                    <p class="mb-0 font-medium text-slate-600">No batches expiring within the next 30 days.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>
