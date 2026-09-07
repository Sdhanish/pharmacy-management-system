<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Customer Purchases & Sales</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows); ?> Total Invoices
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">
            Record customer medicine purchases, track bulk dispensing, generate invoices, and audit inventory deductions.
        </p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('sales/create'); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition text-decoration-none">
            <i class="fa-solid fa-cart-flatbed"></i>
            <span>Record Customer Purchase</span>
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

<!-- SALES METRICS STATS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="app-card p-4 flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Invoices</span>
            <div class="text-xl font-extrabold text-slate-900"><?php echo number_format($metrics['total_sales'] ?? 0); ?></div>
        </div>
    </div>

    <div class="app-card p-4 flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Revenue</span>
            <div class="text-xl font-extrabold text-emerald-700 font-mono">$<?php echo number_format($metrics['total_revenue'] ?? 0.00, 2); ?></div>
        </div>
    </div>

    <div class="app-card p-4 flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today's Invoices</span>
            <div class="text-xl font-extrabold text-slate-900"><?php echo number_format($metrics['today_sales'] ?? 0); ?></div>
        </div>
    </div>

    <div class="app-card p-4 flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-hand-holding-dollar"></i>
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today's Revenue</span>
            <div class="text-xl font-extrabold text-blue-700 font-mono">$<?php echo number_format($metrics['today_revenue'] ?? 0.00, 2); ?></div>
        </div>
    </div>
</div>

<!-- SEARCH & DATE FILTERS CARD -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('sales'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        
        <!-- Search Input -->
        <div class="sm:col-span-4 lg:col-span-4">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Invoices
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="<?php echo html_escape($search ?? ''); ?>" 
                       class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-emerald-500" 
                       placeholder="Invoice #, customer name, phone...">
            </div>
        </div>

        <!-- Customer Filter -->
        <div class="sm:col-span-3 lg:col-span-3">
            <label for="customer_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Customer
            </label>
            <select name="customer_id" id="customer_id" class="form-select form-select-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
                <option value="">All Customers</option>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $cust): ?>
                        <option value="<?php echo $cust['id']; ?>" <?php echo (($customer_id ?? '') == $cust['id']) ? 'selected' : ''; ?>>
                            <?php echo html_escape($cust['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Date Range Filter -->
        <div class="sm:col-span-3 lg:col-span-3">
            <label for="start_date" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Sale Date
            </label>
            <input type="date" 
                   name="start_date" 
                   id="start_date" 
                   value="<?php echo html_escape($start_date ?? ''); ?>" 
                   class="form-control form-control-sm text-xs rounded-xl border-slate-200 focus:border-emerald-500">
        </div>

        <!-- Filter Action Buttons -->
        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <a href="<?php echo base_url('sales'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Filters">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- SALES LEDGER TABLE -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 pl-4">Invoice #</th>
                    <th class="py-3.5">Date</th>
                    <th class="py-3.5">Customer Details</th>
                    <th class="py-3.5 text-center">Items</th>
                    <th class="py-3.5">Payment</th>
                    <th class="py-3.5 text-end">Total Amount</th>
                    <th class="py-3.5 text-end pr-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($sales) && !empty($sales)): ?>
                    <?php foreach ($sales as $s): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Invoice # -->
                            <td class="py-3 pl-4">
                                <a href="<?php echo base_url('sales/invoice/' . $s['id']); ?>" class="font-mono font-bold text-emerald-700 hover:underline flex items-center gap-1.5 text-decoration-none">
                                    <i class="fa-solid fa-file-invoice text-emerald-600"></i>
                                    <span><?php echo html_escape($s['invoice_no']); ?></span>
                                </a>
                                <span class="text-[10px] text-slate-400 block">Billed by <?php echo html_escape($s['billed_by_name'] ?? 'Admin'); ?></span>
                            </td>

                            <!-- Date -->
                            <td class="py-3 text-slate-600">
                                <?php echo date('M d, Y', strtotime($s['sale_date'])); ?>
                            </td>

                            <!-- Customer Details -->
                            <td class="py-3">
                                <div class="font-bold text-slate-900">
                                    <?php echo html_escape($s['customer_name']); ?>
                                </div>
                                <?php if (!empty($s['customer_phone'])): ?>
                                    <div class="text-[11px] text-slate-400 font-mono">
                                        <i class="fa-solid fa-phone text-[9px] mr-1"></i><?php echo html_escape($s['customer_phone']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Items count -->
                            <td class="py-3 text-center">
                                <span class="badge bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded-full text-xs">
                                    <?php echo (int)($s['total_items_count'] ?? 1); ?> medicines
                                </span>
                            </td>

                            <!-- Payment Method & Status -->
                            <td class="py-3">
                                <span class="capitalize text-xs font-semibold text-slate-700 block">
                                    <i class="fa-solid fa-credit-card text-[10px] text-slate-400 mr-1"></i>
                                    <?php echo html_escape($s['payment_method']); ?>
                                </span>
                                <?php if ($s['payment_status'] === 'paid'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.2 rounded">
                                        <i class="fa-solid fa-check text-[8px]"></i> Paid
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.2 rounded">
                                        <i class="fa-solid fa-clock text-[8px]"></i> Pending
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Total Amount -->
                            <td class="py-3 text-end font-mono font-bold text-slate-900 text-sm">
                                $<?php echo number_format($s['total_amount'], 2); ?>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-end pr-4">
                                <div class="inline-flex items-center gap-1">
                                    <a href="<?php echo base_url('sales/invoice/' . $s['id']); ?>" 
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 border border-slate-200" 
                                       title="View & Print Invoice">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                    <a href="<?php echo base_url('sales/delete/' . $s['id']); ?>" 
                                       class="btn btn-sm btn-light p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 border border-slate-200" 
                                       title="Cancel Sale & Restore Stock"
                                       onclick="return confirm('Are you sure you want to cancel invoice #<?php echo html_escape(addslashes($s['invoice_no'])); ?>?\nThis will automatically RESTORE all sold medicines back into inventory.');">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="empty-state-icon-wrap mx-auto">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <h4 class="text-base font-bold text-slate-800 mb-1">No Customer Purchases Found</h4>
                            <p class="text-xs text-slate-400 mb-4 max-w-sm mx-auto">
                                Record a customer medicine purchase to start tracking sales and automatic inventory deductions.
                            </p>
                            <a href="<?php echo base_url('sales/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 font-semibold">
                                <i class="fa-solid fa-plus mr-1"></i> Record First Sale
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer & Pagination -->
    <?php if ($total_rows > 0): ?>
        <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500 bg-slate-50/50">
            <div>
                Showing <span class="font-bold text-slate-800"><?php echo min($offset + 1, $total_rows); ?></span> to 
                <span class="font-bold text-slate-800"><?php echo min($offset + $per_page, $total_rows); ?></span> of 
                <span class="font-bold text-slate-800"><?php echo number_format($total_rows); ?></span> sales records
            </div>
            <div>
                <?php echo $pagination_links; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
