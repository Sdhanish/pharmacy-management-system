<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Action Bar (Hidden on Print) -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('sales'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Customer Purchase Receipt</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo html_escape($sale->invoice_no); ?>
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Official dispensary invoice and customer transaction record.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <button type="button" onclick="window.print()" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i>
            <span>Print Invoice</span>
        </button>
        <a href="<?php echo base_url('sales/create'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-plus"></i>
            <span>New Sale</span>
        </a>
    </div>
</div>

<!-- INVOICE CARD -->
<div class="max-w-4xl mx-auto">
    <div class="app-card p-6 sm:p-10 print-card shadow-sm border border-slate-100">
        
        <!-- Pharmacy Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-sm">
                    <i class="fa-solid fa-staff-snake"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight mb-0">
                        Pharma<span class="text-emerald-600">Care</span> Dispensary
                    </h1>
                    <p class="text-xs text-slate-500 mb-0">Licensed Community Pharmacy & Dispensary Service</p>
                    <p class="text-[11px] text-slate-400 mb-0">support@pharmacare.local &bull; +1 (800) 555-PHARMA</p>
                </div>
            </div>
            
            <div class="sm:text-end">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tax Invoice #</span>
                <span class="text-lg font-mono font-bold text-slate-900 block"><?php echo html_escape($sale->invoice_no); ?></span>
                <span class="text-xs text-slate-500 block">Date: <?php echo date('M d, Y', strtotime($sale->sale_date)); ?></span>
            </div>
        </div>

        <!-- Bill To & Transaction Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider block mb-2 text-[10px]">Customer / Patient Details:</span>
                <h3 class="text-sm font-bold text-slate-900 mb-1"><?php echo html_escape($sale->customer_name); ?></h3>
                <?php if (!empty($sale->customer_phone)): ?>
                    <p class="text-slate-600 mb-0.5"><strong>Phone:</strong> <?php echo html_escape($sale->customer_phone); ?></p>
                <?php endif; ?>
                <?php if (!empty($sale->customer_email)): ?>
                    <p class="text-slate-600 mb-0.5"><strong>Email:</strong> <?php echo html_escape($sale->customer_email); ?></p>
                <?php endif; ?>
                <?php if (!empty($sale->customer_registered_address)): ?>
                    <p class="text-slate-600 mb-0"><strong>Address:</strong> <?php echo html_escape($sale->customer_registered_address); ?></p>
                <?php endif; ?>
            </div>

            <div class="sm:text-end space-y-1">
                <span class="font-bold text-slate-400 uppercase tracking-wider block mb-2 text-[10px]">Dispensing Details:</span>
                <p class="text-slate-600 mb-0.5">
                    <strong>Dispensed By:</strong> <?php echo html_escape($sale->billed_by_name ?? 'Administrator'); ?>
                </p>
                <p class="text-slate-600 mb-0.5">
                    <strong>Payment Method:</strong> <span class="capitalize font-semibold text-slate-800"><?php echo html_escape($sale->payment_method); ?></span>
                </p>
                <p class="text-slate-600 mb-0">
                    <strong>Payment Status:</strong> 
                    <?php if ($sale->payment_status === 'paid'): ?>
                        <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">PAID</span>
                    <?php else: ?>
                        <span class="badge bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full text-[10px]">PENDING</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="py-6">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-3">Itemized Pharmaceutical Drugs</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-xs">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-600">
                        <tr>
                            <th class="py-2.5 pl-3" style="width: 5%;">#</th>
                            <th class="py-2.5" style="width: 45%;">Medicine Description</th>
                            <th class="py-2.5 text-center" style="width: 15%;">Quantity</th>
                            <th class="py-2.5 text-end" style="width: 15%;">Unit Price</th>
                            <th class="py-2.5 text-end pr-3" style="width: 20%;">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($items)): ?>
                            <?php $sn = 1; foreach ($items as $item): ?>
                                <tr>
                                    <td class="py-3 pl-3 font-mono text-slate-400"><?php echo $sn++; ?></td>
                                    <td class="py-3">
                                        <span class="font-bold text-slate-900 block"><?php echo html_escape($item['medicine_name']); ?></span>
                                        <?php if (!empty($item['category_name'])): ?>
                                            <span class="text-[11px] text-slate-400"><?php echo html_escape($item['category_name']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 text-center font-bold text-slate-800">
                                        <?php echo (int) $item['quantity']; ?>
                                    </td>
                                    <td class="py-3 text-end font-mono text-slate-700">
                                        $<?php echo number_format($item['unit_price'], 2); ?>
                                    </td>
                                    <td class="py-3 text-end pr-3 font-mono font-bold text-slate-900">
                                        $<?php echo number_format($item['total_price'], 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calculations Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-200">
            <div>
                <?php if (!empty($sale->notes)): ?>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                        <span class="font-bold text-slate-700 block mb-1">Prescription / Dispensing Notes:</span>
                        <p class="text-slate-600 mb-0 italic"><?php echo nl2br(html_escape($sale->notes)); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Items Subtotal:</span>
                        <span class="font-mono font-bold text-slate-900">$<?php echo number_format($sale->subtotal, 2); ?></span>
                    </div>

                    <?php if ((float)$sale->discount > 0): ?>
                        <div class="flex items-center justify-between text-emerald-700">
                            <span>Discount:</span>
                            <span class="font-mono font-bold">-$<?php echo number_format($sale->discount, 2); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ((float)$sale->tax > 0): ?>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Tax / GST:</span>
                            <span class="font-mono font-bold text-slate-900">+$<?php echo number_format($sale->tax, 2); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-sm font-bold uppercase tracking-wider text-slate-900">Grand Total:</span>
                        <span class="text-xl font-extrabold font-mono text-emerald-700">$<?php echo number_format($sale->total_amount, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Footer Signatures -->
        <div class="mt-12 pt-8 border-t border-dashed border-slate-200 grid grid-cols-2 gap-6 text-center text-xs text-slate-400">
            <div>
                <div class="w-36 border-b border-slate-300 mx-auto mb-2"></div>
                <p class="mb-0">Customer Signature</p>
            </div>
            <div>
                <div class="w-36 border-b border-slate-300 mx-auto mb-2"></div>
                <p class="mb-0">Authorized Pharmacist</p>
            </div>
        </div>

    </div>
</div>
