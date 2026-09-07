<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Stock History & Audit Ledger</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows); ?> Events Logged
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Chronological ledger of all inventory receipts, sales, stock adjustments, and reversals.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('stock'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-boxes-stacked text-slate-500"></i>
            <span>Stock Purchases</span>
        </a>
        <a href="<?php echo base_url('stock/create'); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition text-decoration-none">
            <i class="fa-solid fa-plus"></i>
            <span>Add Stock Purchase</span>
        </a>
    </div>
</div>

<!-- SEARCH BAR -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('stock-history'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        <div class="sm:col-span-10">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Audit Trail
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
                       placeholder="Search by medicine name, reference number, or transaction notes...">
            </div>
        </div>
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Search</span>
            </button>
            <a href="<?php echo base_url('stock-history'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Search">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- AUDIT LEDGER TABLE -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="stockHistoryTable">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="sortable py-3.5 pl-4" data-sort="text">Transaction Ref</th>
                    <th class="sortable py-3.5" data-sort="text">Action Type</th>
                    <th class="sortable py-3.5" data-sort="text">Medicine</th>
                    <th class="sortable py-3.5 text-center" data-sort="number">Qty Change</th>
                    <th class="sortable py-3.5 text-center" data-sort="number">Balance After</th>
                    <th class="sortable py-3.5" data-sort="text">Recorded By</th>
                    <th class="py-3.5">Notes & Details</th>
                    <th class="sortable py-3.5 text-end pr-4" data-sort="date">Timestamp</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($history) && !empty($history)): ?>
                    <?php foreach ($history as $h): ?>
                        <?php
                        $type = strtoupper($h['transaction_type'] ?? 'PURCHASE');
                        $qty = (int) ($h['quantity'] ?? 0);
                        
                        switch ($type) {
                            case 'PURCHASE':
                                $badge_class = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                $badge_icon = 'fa-arrow-down-left';
                                $qty_class = 'text-emerald-600 font-bold';
                                $qty_text = '+' . abs($qty);
                                break;
                            case 'SALE':
                                $badge_class = 'bg-blue-100 text-blue-800 border-blue-200';
                                $badge_icon = 'fa-arrow-up-right';
                                $qty_class = 'text-rose-600 font-bold';
                                $qty_text = '-' . abs($qty);
                                break;
                            case 'RETURN':
                                $badge_class = 'bg-teal-100 text-teal-800 border-teal-200';
                                $badge_icon = 'fa-rotate-left';
                                $qty_class = 'text-teal-700 font-bold';
                                $qty_text = ($qty >= 0) ? '+' . $qty : (string)$qty;
                                break;
                            case 'EXPIRED':
                                $badge_class = 'bg-rose-100 text-rose-800 border-rose-200';
                                $badge_icon = 'fa-circle-xmark';
                                $qty_class = 'text-rose-600 font-bold';
                                $qty_text = '-' . abs($qty);
                                break;
                            default: // ADJUSTMENT
                                $badge_class = 'bg-amber-100 text-amber-800 border-amber-200';
                                $badge_icon = 'fa-sliders';
                                $qty_class = ($qty >= 0) ? 'text-emerald-600 font-bold' : 'text-rose-600 font-bold';
                                $qty_text = ($qty >= 0) ? '+' . $qty : (string)$qty;
                                break;
                        }
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Ref No -->
                            <td class="py-3 pl-4 font-mono text-xs font-bold text-slate-700">
                                <?php echo html_escape($h['reference_no'] ?? ('#TRX-' . $h['id'])); ?>
                            </td>

                            <!-- Action Type -->
                            <td class="py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $badge_class; ?>">
                                    <i class="fa-solid <?php echo $badge_icon; ?> text-[10px]"></i>
                                    <span><?php echo $type; ?></span>
                                </span>
                            </td>

                            <!-- Medicine -->
                            <td class="py-3">
                                <span class="font-bold text-slate-900 block text-xs sm:text-sm">
                                    <?php echo html_escape($h['medicine_name'] ?? 'Medicine Item'); ?>
                                </span>
                            </td>

                            <!-- Qty Change -->
                            <td class="py-3 text-center">
                                <span class="<?php echo $qty_class; ?> font-mono text-xs sm:text-sm">
                                    <?php echo $qty_text; ?>
                                </span>
                            </td>

                            <!-- Balance After -->
                            <td class="py-3 text-center font-mono font-bold text-slate-800 text-xs">
                                <?php echo $h['balance_after']; ?> units
                            </td>

                            <!-- Recorded By -->
                            <td class="py-3 text-slate-600 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-user text-slate-400 text-[10px]"></i>
                                    <span><?php echo html_escape($h['user_name'] ?? 'System User'); ?></span>
                                </div>
                            </td>

                            <!-- Notes -->
                            <td class="py-3 text-xs text-slate-500 max-w-xs">
                                <?php echo html_escape($h['notes'] ?: 'Standard inventory stock movement'); ?>
                            </td>

                            <!-- Timestamp -->
                            <td class="py-3 text-end pr-4 font-mono text-xs text-slate-500">
                                <?php echo date('M d, Y &bull; h:i A', strtotime($h['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300 mb-2"></i>
                            <h5 class="text-sm font-bold text-slate-700 mb-1">No Audit Records Found</h5>
                            <p class="text-xs text-slate-400 mb-0">Stock transaction events will be recorded here automatically.</p>
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
            <span class="font-bold text-slate-800"><?php echo number_format($total_rows); ?></span> audit records
        </div>

        <div>
            <?php echo $pagination_links; ?>
        </div>
    </div>
</div>
