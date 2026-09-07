<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('sales'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Record Customer Purchase (Bulk Sale)</h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">
            Select an existing customer or walk-in patient, add multiple medicines, and record the sale. Inventory is automatically deducted.
        </p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('sales'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="salesForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i>
            <span>Complete & Generate Invoice</span>
        </button>
    </div>
</div>

<!-- Flash Notifications -->
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
        </div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo base_url('sales/store'); ?>" method="POST" id="salesForm" class="needs-validation" novalidate>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- LEFT 2 COLS: CUSTOMER INFO & MEDICINE ITEMS -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- CUSTOMER DETAILS CARD -->
            <div class="app-card p-5 sm:p-6">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-0 flex items-center gap-2">
                        <i class="fa-solid fa-user-tag text-emerald-600"></i>
                        <span>Customer Information</span>
                    </h4>
                    <a href="<?php echo base_url('customers/create'); ?>" target="_blank" class="text-xs text-emerald-600 hover:text-emerald-800 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-user-plus text-[10px]"></i>
                        <span>+ Add New Customer Profile</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <!-- Customer Dropdown Selector -->
                    <div class="sm:col-span-6">
                        <label for="customer_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Select Registered Customer
                        </label>
                        <select name="customer_id" id="customer_id" class="form-select text-xs sm:text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                            <option value="">-- Walk-in / Unregistered Patient --</option>
                            <?php if (!empty($customers)): ?>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" 
                                            data-name="<?php echo html_escape($c['name']); ?>" 
                                            data-phone="<?php echo html_escape($c['phone'] ?? ''); ?>"
                                            <?php echo ($selected_customer_id == $c['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($c['name']); ?> (<?php echo html_escape($c['email']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Customer Name -->
                    <div class="sm:col-span-6">
                        <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Customer Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="customer_name" 
                               id="customer_name" 
                               required 
                               class="form-control text-xs sm:text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="e.g. Johnathan Miller">
                        <div class="invalid-feedback text-xs">Customer name is required.</div>
                    </div>

                    <!-- Customer Phone -->
                    <div class="sm:col-span-6">
                        <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Contact / Phone Number
                        </label>
                        <input type="text" 
                               name="customer_phone" 
                               id="customer_phone" 
                               class="form-control text-xs sm:text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="e.g. +1 555-0199">
                    </div>

                    <!-- Sale Date -->
                    <div class="sm:col-span-6">
                        <label for="sale_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Sale Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" 
                               name="sale_date" 
                               id="sale_date" 
                               required 
                               value="<?php echo date('Y-m-d'); ?>" 
                               class="form-control text-xs sm:text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- MEDICINES BULK PURCHASE LIST -->
            <div class="app-card p-5 sm:p-6">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-0.5 flex items-center gap-2">
                            <i class="fa-solid fa-pills text-emerald-600"></i>
                            <span>Medicines Purchased (Multi-Item)</span>
                        </h4>
                        <p class="text-xs text-slate-400 mb-0">Add multiple pharmaceutical drugs to this customer invoice.</p>
                    </div>
                    <button type="button" id="btnAddMedicineRow" class="btn btn-light btn-sm text-xs font-semibold rounded-xl px-3 py-1.5 border border-slate-200 text-emerald-700 hover:bg-emerald-50 flex items-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-plus text-emerald-600"></i>
                        <span>+ Add Another Medicine</span>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0" id="medicineItemsTable">
                        <thead class="text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th style="width: 45%;">Medicine & Category</th>
                                <th style="width: 15%;" class="text-center">Stock</th>
                                <th style="width: 15%;">Unit Price</th>
                                <th style="width: 15%;">Quantity</th>
                                <th style="width: 15%;" class="text-end">Line Total</th>
                                <th style="width: 5%;" class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="medicineItemsBody" class="divide-y divide-slate-100">
                            <!-- Rows injected dynamically via JS -->
                        </tbody>
                    </table>
                </div>

                <div id="noItemsWarning" class="p-6 text-center text-slate-400 border border-dashed border-slate-200 rounded-xl mt-3 hidden">
                    <i class="fa-solid fa-basket-shopping text-2xl mb-1 text-slate-300"></i>
                    <p class="text-xs mb-2">No medicines selected yet.</p>
                    <button type="button" class="btn btn-sm btn-emerald font-semibold rounded-xl" onclick="addMedicineRow()">
                        + Add Medicine Row
                    </button>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: INVOICE TOTALS & PAYMENT -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="app-card p-5 sm:p-6 sticky top-24">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-3 mb-4 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                    <span>Payment & Billing</span>
                </h4>

                <!-- Invoice Number -->
                <div class="mb-4">
                    <label for="invoice_no" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Invoice Reference #
                    </label>
                    <input type="text" 
                           name="invoice_no" 
                           id="invoice_no" 
                           value="<?php echo html_escape($suggested_invoice); ?>" 
                           class="form-control text-xs font-mono font-bold text-slate-800 bg-slate-50 rounded-xl border-slate-200" 
                           readonly>
                </div>

                <!-- Payment Method -->
                <div class="mb-4">
                    <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Payment Method <span class="text-rose-500">*</span>
                    </label>
                    <select name="payment_method" id="payment_method" class="form-select text-xs sm:text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                        <option value="cash">Cash In Hand</option>
                        <option value="card">Credit / Debit Card</option>
                        <option value="upi">UPI / Mobile Payment</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="other">Other / Insurance</option>
                    </select>
                </div>

                <!-- Payment Status -->
                <div class="mb-4">
                    <label for="payment_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Payment Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="payment_status" id="payment_status" class="form-select text-xs sm:text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                        <option value="paid">Fully Paid</option>
                        <option value="unpaid">Pending Payment</option>
                    </select>
                </div>

                <!-- Financial Calculation Breakdown -->
                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100 space-y-3 mb-5">
                    <!-- Subtotal -->
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Items Subtotal:</span>
                        <span class="font-mono font-bold text-slate-900" id="displaySubtotal">$0.00</span>
                    </div>

                    <!-- Discount -->
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Discount ($):</span>
                        <div class="w-24">
                            <input type="number" 
                                   name="discount" 
                                   id="discountInput" 
                                   min="0" 
                                   step="0.01" 
                                   value="0.00" 
                                   class="form-control form-control-sm text-xs text-end font-mono rounded-lg border-slate-200" 
                                   oninput="calculateTotals()">
                        </div>
                    </div>

                    <!-- Tax -->
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Tax / GST ($):</span>
                        <div class="w-24">
                            <input type="number" 
                                   name="tax" 
                                   id="taxInput" 
                                   min="0" 
                                   step="0.01" 
                                   value="0.00" 
                                   class="form-control form-control-sm text-xs text-end font-mono rounded-lg border-slate-200" 
                                   oninput="calculateTotals()">
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Grand Total:</span>
                        <span class="text-lg font-extrabold font-mono text-emerald-700" id="displayGrandTotal">$0.00</span>
                    </div>
                </div>

                <!-- Notes / Prescription Remarks -->
                <div class="mb-5">
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Doctor / Prescription Remarks
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="2" 
                              class="form-control text-xs rounded-xl border-slate-200 focus:border-emerald-500" 
                              placeholder="Prescribing physician, dosage instructions, or notes..."></textarea>
                </div>

                <!-- Action Button -->
                <button type="submit" class="btn btn-emerald w-full py-3 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Record Sale & Deduct Stock</span>
                </button>
            </div>

        </div>

    </div>

</form>

<!-- Pass Medicines Master JSON to JavaScript -->
<script>
const availableMedicines = <?php echo json_encode($medicines ?? []); ?>;

document.addEventListener('DOMContentLoaded', function() {
    // 1. Handle Customer Auto-Fill on Select
    const customerSelect = document.getElementById('customer_id');
    const nameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('customer_phone');

    if (customerSelect) {
        customerSelect.addEventListener('change', function() {
            const selectedOpt = customerSelect.options[customerSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                nameInput.value = selectedOpt.getAttribute('data-name') || '';
                phoneInput.value = selectedOpt.getAttribute('data-phone') || '';
            } else {
                nameInput.value = '';
                phoneInput.value = '';
            }
        });

        // Trigger on load if pre-selected
        if (customerSelect.value) {
            customerSelect.dispatchEvent(new Event('change'));
        }
    }

    // 2. Add Button Listener
    const btnAdd = document.getElementById('btnAddMedicineRow');
    if (btnAdd) {
        btnAdd.addEventListener('click', addMedicineRow);
    }

    // Initialize with 1 empty row
    addMedicineRow();
});

let rowCounter = 0;

function addMedicineRow() {
    const tbody = document.getElementById('medicineItemsBody');
    if (!tbody) return;

    rowCounter++;
    const rowId = `item-row-${rowCounter}`;

    let medOptions = '<option value="">-- Select Medicine --</option>';
    availableMedicines.forEach(m => {
        medOptions += `<option value="${m.id}" data-price="${m.price}" data-stock="${m.stock_quantity}">
            ${m.medicine_name} (${m.category_name || 'General'}) - $${parseFloat(m.price).toFixed(2)} [Stock: ${m.stock_quantity}]
        </option>`;
    });

    const tr = document.createElement('tr');
    tr.id = rowId;
    tr.className = 'hover:bg-slate-50/60 transition';
    tr.innerHTML = `
        <td class="py-2.5">
            <select name="medicine_id[]" class="form-select form-select-sm text-xs rounded-xl border-slate-200 medicine-select focus:border-emerald-500" required onchange="onMedicineChange('${rowId}', this)">
                ${medOptions}
            </select>
        </td>
        <td class="py-2.5 text-center">
            <span class="badge bg-slate-100 text-slate-700 text-[11px] font-mono stock-badge px-2 py-1 rounded-lg">--</span>
        </td>
        <td class="py-2.5">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-slate-400 text-xs font-mono">$</span>
                <input type="number" step="0.01" min="0" name="unit_price[]" class="form-control form-control-sm pl-5 text-xs font-mono rounded-lg border-slate-200 unit-price-input" value="0.00" oninput="calculateRowTotal('${rowId}')" required>
            </div>
        </td>
        <td class="py-2.5">
            <input type="number" min="1" max="9999" name="quantity[]" class="form-control form-control-sm text-xs text-center font-bold rounded-lg border-slate-200 qty-input" value="1" oninput="calculateRowTotal('${rowId}')" required>
        </td>
        <td class="py-2.5 text-end font-mono font-bold text-slate-800 text-xs line-total">
            $0.00
        </td>
        <td class="py-2.5 text-center">
            <button type="button" class="btn btn-sm btn-light text-rose-500 hover:bg-rose-50 p-1.5 rounded-lg border-0" title="Remove" onclick="removeMedicineRow('${rowId}')">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </td>
    `;

    tbody.appendChild(tr);
    checkWarningVisibility();
}

function removeMedicineRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
        calculateTotals();
        checkWarningVisibility();
    }
}

function onMedicineChange(rowId, selectElem) {
    const row = document.getElementById(rowId);
    if (!row) return;

    const selectedOpt = selectElem.options[selectElem.selectedIndex];
    const stockBadge = row.querySelector('.stock-badge');
    const priceInput = row.querySelector('.unit-price-input');
    const qtyInput = row.querySelector('.qty-input');

    if (selectedOpt && selectedOpt.value) {
        const price = parseFloat(selectedOpt.getAttribute('data-price')) || 0.00;
        const stock = parseInt(selectedOpt.getAttribute('data-stock')) || 0;

        priceInput.value = price.toFixed(2);
        stockBadge.textContent = `${stock} in stock`;
        stockBadge.className = stock <= 5 
            ? 'badge bg-amber-100 text-amber-800 text-[11px] font-mono stock-badge px-2 py-1 rounded-lg' 
            : 'badge bg-emerald-100 text-emerald-800 text-[11px] font-mono stock-badge px-2 py-1 rounded-lg';

        qtyInput.max = stock;
        if (parseInt(qtyInput.value) > stock) {
            qtyInput.value = stock;
        }
    } else {
        priceInput.value = '0.00';
        stockBadge.textContent = '--';
        stockBadge.className = 'badge bg-slate-100 text-slate-700 text-[11px] font-mono stock-badge px-2 py-1 rounded-lg';
    }

    calculateRowTotal(rowId);
}

function calculateRowTotal(rowId) {
    const row = document.getElementById(rowId);
    if (!row) return;

    const price = parseFloat(row.querySelector('.unit-price-input').value) || 0;
    const qtyInput = row.querySelector('.qty-input');
    let qty = parseInt(qtyInput.value) || 0;
    const maxStock = parseInt(qtyInput.max);

    if (maxStock && qty > maxStock) {
        qty = maxStock;
        qtyInput.value = maxStock;
        if (typeof window.showToast === 'function') {
            window.showToast(`Maximum available stock is ${maxStock}.`, 'warning');
        }
    }

    const total = qty * price;
    row.querySelector('.line-total').textContent = `$${total.toFixed(2)}`;
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0.00;
    const rows = document.querySelectorAll('#medicineItemsBody tr');

    rows.forEach(r => {
        const price = parseFloat(r.querySelector('.unit-price-input')?.value) || 0;
        const qty = parseInt(r.querySelector('.qty-input')?.value) || 0;
        subtotal += (qty * price);
    });

    const discount = parseFloat(document.getElementById('discountInput')?.value) || 0.00;
    const tax = parseFloat(document.getElementById('taxInput')?.value) || 0.00;
    const grandTotal = Math.max(0.00, subtotal - discount + tax);

    document.getElementById('displaySubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('displayGrandTotal').textContent = `$${grandTotal.toFixed(2)}`;
}

function checkWarningVisibility() {
    const rowsCount = document.querySelectorAll('#medicineItemsBody tr').length;
    const warning = document.getElementById('noItemsWarning');
    if (warning) {
        warning.classList.toggle('hidden', rowsCount > 0);
    }
}
</script>
