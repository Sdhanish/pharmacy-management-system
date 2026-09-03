<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('stock'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Record Stock Purchase</h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Add a supplier purchase consignment. Medicine inventory will automatically increase.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('stock'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="stockPurchaseForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>Confirm & Add Stock</span>
        </button>
    </div>
</div>

<!-- Validation Errors Alert Box -->
<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="font-bold mb-1.5 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <?php echo validation_errors('<p class="mb-0 text-xs">- ', '</p>'); ?>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- MAIN FORM IN TAILWIND CONTAINER -->
<form action="<?php echo base_url('stock/store'); ?>" method="POST" id="stockPurchaseForm" class="needs-validation" novalidate>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- LEFT 2 COLS: PURCHASE & MEDICINE SELECTION -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-emerald-600"></i>
                    <span>Purchase Information</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    
                    <!-- Medicine Dropdown -->
                    <div class="sm:col-span-2">
                        <label for="medicine_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Select Medicine to Restock <span class="text-rose-500">*</span>
                        </label>
                        <select name="medicine_id" id="medicine_id" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('medicine_id') ? 'is-invalid' : ''; ?>" required onchange="updateMedicineSelection(this)">
                            <option value="">-- Choose Medicine Catalog Item --</option>
                            <?php if (isset($medicines) && !empty($medicines)): ?>
                                <?php foreach ($medicines as $m): ?>
                                    <option value="<?php echo $m['id']; ?>" 
                                            data-stock="<?php echo $m['stock_quantity']; ?>" 
                                            data-supplier="<?php echo $m['supplier_id']; ?>"
                                            <?php echo (set_value('medicine_id') == $m['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($m['medicine_name']); ?> &bull; (Current: <?php echo $m['stock_quantity']; ?> units)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback text-xs">Please select a medicine.</div>
                    </div>

                    <!-- Supplier Dropdown -->
                    <div>
                        <label for="supplier_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Supplier / Manufacturer <span class="text-rose-500">*</span>
                        </label>
                        <select name="supplier_id" id="supplier_id" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('supplier_id') ? 'is-invalid' : ''; ?>" required>
                            <option value="">-- Select Supplier --</option>
                            <?php if (isset($suppliers) && !empty($suppliers)): ?>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?php echo $sup['id']; ?>" <?php echo (set_value('supplier_id') == $sup['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($sup['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback text-xs">Please choose a supplier.</div>
                    </div>

                    <!-- Purchase Date -->
                    <div>
                        <label for="purchase_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Purchase Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" 
                               name="purchase_date" 
                               id="purchase_date" 
                               value="<?php echo set_value('purchase_date', date('Y-m-d')); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('purchase_date') ? 'is-invalid' : ''; ?>" 
                               required>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Purchase Quantity (Units) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               min="1" 
                               name="quantity" 
                               id="quantity" 
                               value="<?php echo set_value('quantity', '50'); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('quantity') ? 'is-invalid' : ''; ?>" 
                               placeholder="e.g. 50" 
                               required 
                               oninput="calculateTotal()">
                        <div class="invalid-feedback text-xs">Quantity must be greater than 0.</div>
                    </div>

                    <!-- Purchase Price per Unit -->
                    <div>
                        <label for="purchase_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Unit Purchase Price ($) <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 text-slate-500 rounded-l-xl border-slate-200">$</span>
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01" 
                                   name="purchase_price" 
                                   id="purchase_price" 
                                   value="<?php echo set_value('purchase_price', '6.50'); ?>" 
                                   class="form-control font-mono text-sm rounded-r-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('purchase_price') ? 'is-invalid' : ''; ?>" 
                                   placeholder="0.00" 
                                   required 
                                   oninput="calculateTotal()">
                        </div>
                        <div class="invalid-feedback text-xs">Purchase price must be greater than 0.</div>
                    </div>

                </div>

                <!-- Notes / Consignment Reference -->
                <div>
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Purchase Order Reference & Notes
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3" 
                              class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                              placeholder="Invoice number, batch number, delivery voucher, or receiving notes..."><?php echo set_value('notes'); ?></textarea>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: SUMMARY & AUTO-STOCK INCREASE NOTICE -->
        <div class="space-y-6">
            
            <!-- Summary Card -->
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-calculator text-emerald-600"></i>
                    <span>Order Calculation</span>
                </h4>

                <div class="space-y-3 text-xs mb-4">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Purchased Units:</span>
                        <span class="font-mono font-bold text-slate-800" id="summaryQty">50 units</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Unit Cost:</span>
                        <span class="font-mono font-bold text-slate-800" id="summaryPrice">$6.50</span>
                    </div>
                    <div class="flex justify-between py-2 border-t border-slate-200 text-sm">
                        <span class="font-bold text-slate-800">Total Purchase Cost:</span>
                        <span class="font-mono font-extrabold text-emerald-700 text-base" id="summaryTotal">$325.00</span>
                    </div>
                </div>

                <!-- Automatic Stock Increase Notice -->
                <div class="p-3 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-emerald-900 text-xs">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0"></i>
                        <div>
                            <strong class="block mb-0.5">Automated Stock Increase</strong>
                            <span>Submitting will instantly credit this medicine's inventory by the purchased quantity.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action Card -->
            <div class="app-card p-5 bg-gradient-to-br from-slate-50 to-white">
                <button type="submit" class="btn btn-emerald w-full py-3 rounded-xl font-bold text-sm shadow-md shadow-emerald-600/30 hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Confirm & Restock</span>
                </button>
                <a href="<?php echo base_url('stock'); ?>" class="btn btn-light w-full py-2.5 rounded-xl font-semibold text-xs border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none block text-center mt-2.5">
                    Discard
                </a>
            </div>

        </div>

    </div>
</form>

<script>
function calculateTotal() {
    const qty = parseInt(document.getElementById('quantity').value) || 0;
    const price = parseFloat(document.getElementById('purchase_price').value) || 0;
    const total = qty * price;

    document.getElementById('summaryQty').textContent = qty + ' units';
    document.getElementById('summaryPrice').textContent = '$' + price.toFixed(2);
    document.getElementById('summaryTotal').textContent = '$' + total.toFixed(2);
}

function updateMedicineSelection(select) {
    const option = select.options[select.selectedIndex];
    if (option) {
        const supId = option.getAttribute('data-supplier');
        const supSelect = document.getElementById('supplier_id');
        if (supId && supSelect && !supSelect.value) {
            supSelect.value = supId;
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    calculateTotal();
});
</script>
