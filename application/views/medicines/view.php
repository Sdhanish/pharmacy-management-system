<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$stock_qty = (int) ($medicine->stock_quantity ?? 0);
$min_alert = (int) ($medicine->min_stock_alert ?? 10);
$expiry_date = $medicine->nearest_expiry_date;
$is_expired = ($expiry_date && strtotime($expiry_date) < strtotime(date('Y-m-d')));
$is_expiring_soon = ($expiry_date && !$is_expired && strtotime($expiry_date) <= strtotime('+30 days'));

$profit = (float)$medicine->sell_price - (float)$medicine->buy_price;
$margin_pct = ((float)$medicine->sell_price > 0) ? ($profit / (float)$medicine->sell_price) * 100 : 0;
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">
                <?php echo html_escape($medicine->name); ?>
            </h2>
            <?php if ($medicine->status === 'active'): ?>
                <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">Active Catalog</span>
            <?php else: ?>
                <span class="badge bg-slate-100 text-slate-600 font-semibold px-2.5 py-1 rounded-full text-xs">Inactive</span>
            <?php endif; ?>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">
            <?php echo html_escape($medicine->generic_name ?: 'Pharmaceutical formulation'); ?> &bull; 
            <span class="font-mono"><?php echo html_escape($medicine->sku); ?></span>
        </p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('medicines/edit/' . $medicine->id); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit Specifications</span>
        </a>
        <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Back to Catalog
        </a>
    </div>
</div>

<!-- KEY SPECIFICATIONS & PRICING GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <!-- Available Stock -->
    <div class="app-card p-5 border-l-4 <?php echo ($stock_qty <= $min_alert) ? 'border-l-rose-500' : 'border-l-emerald-500'; ?>">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Available Inventory</span>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl font-bold <?php echo ($stock_qty <= $min_alert) ? 'text-rose-600' : 'text-slate-900'; ?> mb-0">
                <?php echo $stock_qty; ?>
            </h3>
            <span class="text-xs text-slate-500 font-medium"><?php echo html_escape($medicine->unit ?? 'Units'); ?></span>
        </div>
        <p class="text-xs text-slate-400 mt-1 mb-0">Safety threshold: <?php echo $min_alert; ?> units</p>
    </div>

    <!-- Selling Price -->
    <div class="app-card p-5 border-l-4 border-l-cyan-500">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Retail Selling Price</span>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl font-bold text-slate-900 mb-0">
                $<?php echo number_format((float)$medicine->sell_price, 2); ?>
            </h3>
            <span class="text-xs text-slate-400 font-mono">/ unit</span>
        </div>
        <p class="text-xs text-slate-400 mt-1 mb-0">Cost: $<?php echo number_format((float)$medicine->buy_price, 2); ?></p>
    </div>

    <!-- Profit Margin -->
    <div class="app-card p-5 border-l-4 border-l-emerald-500">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Profit Margin</span>
        <div class="flex items-baseline gap-2">
            <h3 class="text-2xl font-bold text-emerald-700 mb-0">
                <?php echo number_format($margin_pct, 1); ?>%
            </h3>
            <span class="text-xs text-emerald-600 font-semibold">+$<?php echo number_format($profit, 2); ?></span>
        </div>
        <p class="text-xs text-slate-400 mt-1 mb-0">Markup per unit sold</p>
    </div>

    <!-- Expiry Status -->
    <div class="app-card p-5 border-l-4 <?php echo $is_expired ? 'border-l-rose-500' : ($is_expiring_soon ? 'border-l-purple-500' : 'border-l-emerald-500'); ?>">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Active Batch Expiry</span>
        <div class="flex items-baseline gap-2">
            <h3 class="text-base font-bold font-mono <?php echo $is_expired ? 'text-rose-600' : ($is_expiring_soon ? 'text-purple-700' : 'text-slate-800'); ?> mb-0">
                <?php echo $expiry_date ? date('M d, Y', strtotime($expiry_date)) : 'N/A'; ?>
            </h3>
        </div>
        <p class="text-xs mt-1 mb-0 font-medium <?php echo $is_expired ? 'text-rose-600 font-bold' : ($is_expiring_soon ? 'text-purple-600 font-semibold' : 'text-emerald-600'); ?>">
            <?php 
            if ($is_expired) echo 'Quarantine: Expired';
            elseif ($is_expiring_soon) echo 'Attention: Expiring within 30 days';
            else echo 'Batch status: Optimal';
            ?>
        </p>
    </div>

</div>

<!-- DETAILS ACCORDION / SPECIFICATION PANELS -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- LEFT 2 COLS: BATCH INVENTORY LEDGER -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Batches Table Card -->
        <div class="app-card p-5 sm:p-6">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-0">Active Stock Batches</h4>
                </div>
                <a href="<?php echo base_url('stock'); ?>" class="text-xs font-semibold text-emerald-600 hover:underline">Manage All Batches</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
                    <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-2.5 pl-3">Batch Number</th>
                            <th class="py-2.5 text-center">Available Units</th>
                            <th class="py-2.5">Purchase Date</th>
                            <th class="py-2.5">Expiry Date</th>
                            <th class="py-2.5 text-end pr-3">Batch Health</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (isset($batches) && !empty($batches)): ?>
                            <?php foreach ($batches as $batch): ?>
                                <?php
                                $b_exp = $batch['expiry_date'];
                                $b_expired = (strtotime($b_exp) < strtotime(date('Y-m-d')));
                                $b_exp_soon = (!$b_expired && strtotime($b_exp) <= strtotime('+30 days'));
                                ?>
                                <tr>
                                    <td class="font-mono text-xs font-bold text-slate-800 py-3 pl-3">
                                        <?php echo html_escape($batch['batch_number']); ?>
                                    </td>
                                    <td class="text-center font-bold font-mono text-sm <?php echo ($batch['quantity'] <= 10) ? 'text-rose-600' : 'text-slate-800'; ?>">
                                        <?php echo $batch['quantity']; ?>
                                    </td>
                                    <td class="text-slate-500 text-xs font-mono">
                                        <?php echo $batch['purchase_date'] ? date('M d, Y', strtotime($batch['purchase_date'])) : '--'; ?>
                                    </td>
                                    <td class="font-mono text-xs <?php echo $b_expired ? 'text-rose-600 font-bold' : ($b_exp_soon ? 'text-purple-600 font-bold' : 'text-slate-700'); ?>">
                                        <?php echo date('M d, Y', strtotime($b_exp)); ?>
                                    </td>
                                    <td class="text-end pr-3">
                                        <?php if ($b_expired): ?>
                                            <span class="badge bg-rose-100 text-rose-800 text-[10px] px-2 py-0.5 rounded-full font-semibold">Expired</span>
                                        <?php elseif ($b_exp_soon): ?>
                                            <span class="badge bg-purple-100 text-purple-800 text-[10px] px-2 py-0.5 rounded-full font-semibold">Expiring Soon</span>
                                        <?php else: ?>
                                            <span class="badge bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full font-semibold">Good Condition</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400 text-xs">
                                    No stock batches currently on record for this medicine.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions Audit Log -->
        <div class="app-card p-5 sm:p-6">
            <h4 class="text-base font-bold text-slate-800 pb-3 mb-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i>
                <span>Recent Stock Activity History</span>
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-xs">
                    <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-2.5 pl-3">Action Type</th>
                            <th class="py-2.5 text-center">Qty Change</th>
                            <th class="py-2.5 text-center">New Balance</th>
                            <th class="py-2.5">Reference / Notes</th>
                            <th class="py-2.5 text-end pr-3">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (isset($history) && !empty($history)): ?>
                            <?php foreach ($history as $h): ?>
                                <tr>
                                    <td class="py-2.5 pl-3">
                                        <span class="badge bg-slate-100 text-slate-800 font-semibold px-2 py-0.5 rounded-md">
                                            <?php echo html_escape($h['transaction_type']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center font-mono font-bold <?php echo ($h['quantity'] > 0) ? 'text-emerald-600' : 'text-rose-600'; ?>">
                                        <?php echo ($h['quantity'] > 0) ? '+' . $h['quantity'] : $h['quantity']; ?>
                                    </td>
                                    <td class="text-center font-mono text-slate-700 font-semibold"><?php echo $h['balance_after']; ?></td>
                                    <td class="text-slate-500">
                                        <span class="font-mono text-slate-700"><?php echo html_escape($h['reference_no'] ?? ''); ?></span>
                                        <?php if (!empty($h['notes'])): ?>
                                            <span class="block text-[10px] text-slate-400"><?php echo html_escape($h['notes']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pr-3 font-mono text-slate-400 text-[11px]">
                                        <?php echo date('M d, Y h:i A', strtotime($h['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400 text-xs">
                                    No stock activity logged yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- RIGHT 1 COL: SPECIFICATION SHEET & SUPPLIER -->
    <div class="space-y-6">
        
        <!-- Specifications Card -->
        <div class="app-card p-5">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-3 mb-3 border-b border-slate-100">
                Drug Specifications
            </h4>
            
            <dl class="space-y-3 text-xs mb-0">
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <dt class="text-slate-400 font-medium">Therapeutic Category</dt>
                    <dd class="font-bold text-slate-800 mb-0"><?php echo html_escape($medicine->category_name ?? 'N/A'); ?></dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <dt class="text-slate-400 font-medium">Dosage Form</dt>
                    <dd class="font-bold text-slate-800 mb-0"><?php echo html_escape($medicine->dosage_form ?? 'Tablet'); ?></dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <dt class="text-slate-400 font-medium">Strength</dt>
                    <dd class="font-bold text-emerald-700 mb-0"><?php echo html_escape($medicine->strength ?? 'N/A'); ?></dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <dt class="text-slate-400 font-medium">Packaging Unit</dt>
                    <dd class="font-bold text-slate-800 mb-0"><?php echo html_escape($medicine->unit ?? 'Strip'); ?></dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <dt class="text-slate-400 font-medium">Item SKU</dt>
                    <dd class="font-mono font-bold text-slate-800 mb-0"><?php echo html_escape($medicine->sku); ?></dd>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <dt class="text-slate-400 font-medium">Barcode / EAN</dt>
                    <dd class="font-mono text-slate-800 mb-0"><?php echo html_escape($medicine->barcode ?: '--'); ?></dd>
                </div>
            </dl>

            <?php if (!empty($medicine->description)): ?>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Medical Description</span>
                    <p class="text-xs text-slate-600 leading-relaxed mb-0">
                        <?php echo nl2br(html_escape($medicine->description)); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Manufacturer / Supplier Card -->
        <div class="app-card p-5">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-3 mb-3 border-b border-slate-100">
                Manufacturer / Brand
            </h4>

            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-industry text-lg"></i>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-slate-900 mb-0">
                        <?php echo html_escape($medicine->supplier_name ?? 'Generic Manufacturer'); ?>
                    </h5>
                    <p class="text-[11px] text-slate-400 mb-0">Authorized Distributor</p>
                </div>
            </div>

            <?php if (!empty($medicine->supplier_contact) || !empty($medicine->supplier_phone)): ?>
                <div class="space-y-1.5 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <?php if (!empty($medicine->supplier_contact)): ?>
                        <p class="mb-0 flex items-center gap-2">
                            <i class="fa-regular fa-user text-slate-400 w-4"></i>
                            <span><?php echo html_escape($medicine->supplier_contact); ?></span>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($medicine->supplier_phone)): ?>
                        <p class="mb-0 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-slate-400 w-4"></i>
                            <span class="font-mono"><?php echo html_escape($medicine->supplier_phone); ?></span>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>
