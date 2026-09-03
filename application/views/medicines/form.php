<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$is_edit = ($mode === 'edit' && isset($medicine) && $medicine);
$form_action = $is_edit ? base_url('medicines/update/' . $medicine->id) : base_url('medicines/store');
$btn_label = $is_edit ? 'Update Medicine' : 'Save & Register Medicine';
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">
                <?php echo $is_edit ? 'Edit Medicine: ' . html_escape($medicine->name) : 'Add New Medicine'; ?>
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">
            <?php echo $is_edit ? 'Update medicine specifications, pricing, and active batch threshold.' : 'Register a new pharmaceutical drug, initial batch quantity, and sales pricing.'; ?>
        </p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="medicineForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span><?php echo $btn_label; ?></span>
        </button>
    </div>
</div>

<!-- Validation Errors Alert Box -->
<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="font-bold mb-1.5 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span>Please correct the validation errors below:</span>
        </div>
        <?php echo validation_errors('<p class="mb-0 text-xs">- ', '</p>'); ?>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- MAIN FORM CARD -->
<form action="<?php echo $form_action; ?>" method="POST" id="medicineForm" class="needs-validation" novalidate>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- LEFT 2 COLS: MEDICINE DETAILS & PRICING -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- SECTION 1: BASIC INFORMATION -->
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-pills text-emerald-600"></i>
                    <span>1. Basic Drug Information</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <!-- Medicine Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Medicine Brand / Trade Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="<?php echo set_value('name', $is_edit ? $medicine->name : ''); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                               placeholder="e.g. Amoxicillin Trihydrate, Lipitor" 
                               required>
                        <div class="invalid-feedback text-xs">Please provide the medicine name.</div>
                    </div>

                    <!-- Generic Name -->
                    <div>
                        <label for="generic_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Generic Active Molecule
                        </label>
                        <input type="text" 
                               name="generic_name" 
                               id="generic_name" 
                               value="<?php echo set_value('generic_name', $is_edit ? $medicine->generic_name : ''); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="e.g. Amoxicillin, Atorvastatin">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Therapeutic Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('category_id') ? 'is-invalid' : ''; ?>" required>
                            <option value="">-- Select Category --</option>
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo (set_value('category_id', $is_edit ? $medicine->category_id : '') == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback text-xs">Please select a category.</div>
                    </div>

                    <!-- Brand / Supplier -->
                    <div>
                        <label for="supplier_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Brand / Manufacturer / Supplier
                        </label>
                        <select name="supplier_id" id="supplier_id" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                            <option value="">-- Select Manufacturer / Supplier --</option>
                            <?php if (isset($suppliers) && !empty($suppliers)): ?>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?php echo $sup['id']; ?>" <?php echo (set_value('supplier_id', $is_edit ? $medicine->supplier_id : '') == $sup['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($sup['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- SKU / Item Code -->
                    <div>
                        <label for="sku" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            SKU / Item Code <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   name="sku" 
                                   id="sku" 
                                   value="<?php echo set_value('sku', $is_edit ? $medicine->sku : 'MED-' . strtoupper(substr(uniqid(), 7, 5))); ?>" 
                                   class="form-control font-mono text-sm rounded-l-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('sku') ? 'is-invalid' : ''; ?>" 
                                   placeholder="e.g. MED-AMX-500" 
                                   required>
                            <button type="button" class="btn btn-light border border-slate-200 text-xs text-slate-500 rounded-r-xl" onclick="generateRandomSKU()" title="Generate Random SKU">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback text-xs">Unique SKU code is required.</div>
                    </div>

                    <!-- Dosage Form -->
                    <div>
                        <label for="dosage_form" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Dosage Form <span class="text-rose-500">*</span>
                        </label>
                        <select name="dosage_form" id="dosage_form" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500" required>
                            <?php
                            $dosage_options = array('Tablet', 'Capsule', 'Syrup', 'Injection', 'Ointment', 'Cream', 'Inhaler', 'Drops', 'Effervescent', 'Suspension', 'Gel', 'Powder');
                            $current_form = set_value('dosage_form', $is_edit ? $medicine->dosage_form : 'Tablet');
                            foreach ($dosage_options as $opt):
                            ?>
                                <option value="<?php echo $opt; ?>" <?php echo ($current_form === $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Strength -->
                    <div>
                        <label for="strength" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Strength / Concentration
                        </label>
                        <input type="text" 
                               name="strength" 
                               id="strength" 
                               value="<?php echo set_value('strength', $is_edit ? $medicine->strength : ''); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="e.g. 500mg, 10ml, 1%, 250mcg">
                    </div>

                    <!-- Packaging Unit -->
                    <div>
                        <label for="unit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Packaging Unit <span class="text-rose-500">*</span>
                        </label>
                        <select name="unit" id="unit" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500" required>
                            <?php
                            $unit_options = array('Strip (10s)', 'Strip (15s)', 'Box (100s)', 'Bottle (60ml)', 'Bottle (100ml)', 'Vial', 'Ampoule', 'Tube (30g)', 'Inhaler (200 doses)', 'Piece', 'Sachet');
                            $current_unit = set_value('unit', $is_edit ? $medicine->unit : 'Strip (10s)');
                            foreach ($unit_options as $u_opt):
                            ?>
                                <option value="<?php echo $u_opt; ?>" <?php echo ($current_unit === $u_opt) ? 'selected' : ''; ?>><?php echo $u_opt; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label for="barcode" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Barcode / EAN-13
                        </label>
                        <input type="text" 
                               name="barcode" 
                               id="barcode" 
                               value="<?php echo set_value('barcode', $is_edit ? $medicine->barcode : ''); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="e.g. 890123456701">
                    </div>
                </div>

                <!-- Description / Medical Notes -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Description & Medical Indications
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                              placeholder="Key indications, contraindications, side effects, or warehouse storage notes..."><?php echo set_value('description', $is_edit ? $medicine->description : ''); ?></textarea>
                </div>
            </div>

            <!-- SECTION 2: PRICING & PROFIT MARGIN -->
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-tag text-emerald-600"></i>
                    <span>2. Pricing & Profit Margin</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                    <!-- Buy Price -->
                    <div>
                        <label for="buy_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Purchase Price (₹) <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 text-slate-500 rounded-l-xl border-slate-200">₹</span>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   name="buy_price" 
                                   id="buy_price" 
                                   value="<?php echo set_value('buy_price', $is_edit ? $medicine->buy_price : '5.00'); ?>" 
                                   class="form-control font-mono text-sm rounded-r-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('buy_price') ? 'is-invalid' : ''; ?>" 
                                   required 
                                   oninput="calculateMargin()">
                        </div>
                    </div>

                    <!-- Sell Price -->
                    <div>
                        <label for="sell_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Selling Price (₹) <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 text-slate-500 rounded-l-xl border-slate-200">₹</span>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   name="sell_price" 
                                   id="sell_price" 
                                   value="<?php echo set_value('sell_price', $is_edit ? $medicine->sell_price : '10.00'); ?>" 
                                   class="form-control font-mono text-sm rounded-r-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('sell_price') ? 'is-invalid' : ''; ?>" 
                                   required 
                                   oninput="calculateMargin()">
                        </div>
                    </div>

                    <!-- Calculated Margin Card -->
                    <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 block">Est. Profit Margin</span>
                        <div class="text-xl font-extrabold text-emerald-700" id="marginDisplay">50.0%</div>
                        <span class="text-[10px] text-emerald-600 block" id="marginDiff">Profit: +₹5.00</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: INVENTORY BATCH & SETTINGS -->
        <div class="space-y-6">
            
            <!-- SECTION 3: STOCK & BATCH CONTROLS -->
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-emerald-600"></i>
                    <span>3. Stock & Expiry Batch</span>
                </h4>

                <div class="space-y-4">
                    <!-- Minimum Stock Alert -->
                    <div>
                        <label for="min_stock_alert" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Minimum Stock Alert <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               min="0" 
                               name="min_stock_alert" 
                               id="min_stock_alert" 
                               value="<?php echo set_value('min_stock_alert', $is_edit ? $medicine->min_stock_alert : '15'); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('min_stock_alert') ? 'is-invalid' : ''; ?>" 
                               required>
                        <p class="text-[11px] text-slate-400 mt-1 mb-0">Triggers low stock warning when inventory drops below this number.</p>
                    </div>

                    <!-- Initial / Primary Batch Number -->
                    <div>
                        <label for="batch_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Batch Number
                        </label>
                        <input type="text" 
                               name="batch_number" 
                               id="batch_number" 
                               value="<?php echo set_value('batch_number', $is_edit ? ($medicine->batch_number ?? '') : 'BATCH-' . date('Y') . '-' . strtoupper(substr(uniqid(), 8, 3))); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="e.g. AMX-2026-01">
                    </div>

                    <!-- Quantity Units -->
                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            <?php echo $is_edit ? 'Stock Quantity (Units)' : 'Initial Opening Stock (Units)'; ?>
                        </label>
                        <input type="number" 
                               min="0" 
                               name="quantity" 
                               id="quantity" 
                               value="<?php echo set_value('quantity', $is_edit ? ($medicine->stock_quantity ?? 0) : '50'); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               placeholder="Available units">
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Batch Expiry Date
                        </label>
                        <input type="date" 
                               name="expiry_date" 
                               id="expiry_date" 
                               value="<?php echo set_value('expiry_date', $is_edit ? ($medicine->nearest_expiry_date ?? date('Y-m-d', strtotime('+1 year'))) : date('Y-m-d', strtotime('+18 months'))); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                    </div>

                    <!-- Purchase Date -->
                    <?php if (!$is_edit): ?>
                        <div>
                            <label for="purchase_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Purchase / Receipt Date
                            </label>
                            <input type="date" 
                                   name="purchase_date" 
                                   id="purchase_date" 
                                   value="<?php echo set_value('purchase_date', date('Y-m-d')); ?>" 
                                   class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                        </div>
                    <?php endif; ?>

                    <!-- Active Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Catalog Status
                        </label>
                        <select name="status" id="status" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                            <option value="active" <?php echo (set_value('status', $is_edit ? $medicine->status : 'active') === 'active') ? 'selected' : ''; ?>>Active (Enabled)</option>
                            <option value="inactive" <?php echo (set_value('status', $is_edit ? $medicine->status : '') === 'inactive') ? 'selected' : ''; ?>>Inactive (Disabled)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Action Card -->
            <div class="app-card p-5 bg-gradient-to-br from-slate-50 to-white">
                <button type="submit" class="btn btn-emerald w-full py-3 rounded-xl font-bold text-sm shadow-md shadow-emerald-600/30 hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span><?php echo $btn_label; ?></span>
                </button>
                <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light w-full py-2.5 rounded-xl font-semibold text-xs border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none block text-center mt-2.5">
                    Discard Changes
                </a>
            </div>

        </div>

    </div>
</form>

<!-- Helper Javascript for Margin Calculation & Validation -->
<script>
function calculateMargin() {
    const buy = parseFloat(document.getElementById('buy_price').value) || 0;
    const sell = parseFloat(document.getElementById('sell_price').value) || 0;
    const marginDisplay = document.getElementById('marginDisplay');
    const marginDiff = document.getElementById('marginDiff');

    if (sell > 0) {
        const profit = sell - buy;
        const marginPct = (profit / sell) * 100;
        marginDisplay.textContent = marginPct.toFixed(1) + '%';
        marginDiff.textContent = 'Profit: ' + (profit >= 0 ? '+₹' : '-₹') + Math.abs(profit).toFixed(2);
        
        if (profit < 0) {
            marginDisplay.className = 'text-xl font-extrabold text-rose-600';
        } else {
            marginDisplay.className = 'text-xl font-extrabold text-emerald-700';
        }
    } else {
        marginDisplay.textContent = '0.0%';
        marginDiff.textContent = 'Profit: ₹0.00';
    }
}

function generateRandomSKU() {
    const random = Math.floor(1000 + Math.random() * 9000);
    document.getElementById('sku').value = 'MED-GEN-' + random;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    calculateMargin();
});
</script>
