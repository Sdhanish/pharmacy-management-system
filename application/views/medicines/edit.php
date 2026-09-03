<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">
                Edit Medicine: <?php echo html_escape($medicine->medicine_name); ?>
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Update pricing, inventory stock level, expiry date, or product image.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="editMedicineForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>Update Medicine</span>
        </button>
    </div>
</div>

<!-- Server-side Validation Errors Alert Box -->
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

<!-- EDIT FORM -->
<form action="<?php echo base_url('medicines/update/' . $medicine->id); ?>" method="POST" id="editMedicineForm" class="needs-validation" novalidate>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- LEFT 2 COLS: ATTRIBUTES & PRICING -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-pills text-emerald-600"></i>
                    <span>Medicine Details</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <!-- 1. Medicine Name -->
                    <div class="sm:col-span-2">
                        <label for="medicine_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Medicine Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="medicine_name" 
                               id="medicine_name" 
                               value="<?php echo set_value('medicine_name', $medicine->medicine_name); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('medicine_name') ? 'is-invalid' : ''; ?>" 
                               required>
                        <div class="invalid-feedback text-xs">Please provide the medicine name.</div>
                    </div>

                    <!-- 2. Category Dropdown -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('category_id') ? 'is-invalid' : ''; ?>" required>
                            <option value="">-- Select Category --</option>
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo (set_value('category_id', $medicine->category_id) == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback text-xs">Please select a valid category.</div>
                    </div>

                    <!-- 3. Supplier Dropdown -->
                    <div>
                        <label for="supplier_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Supplier / Manufacturer
                        </label>
                        <select name="supplier_id" id="supplier_id" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('supplier_id') ? 'is-invalid' : ''; ?>">
                            <option value="">-- Select Supplier --</option>
                            <?php if (isset($suppliers) && !empty($suppliers)): ?>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?php echo $sup['id']; ?>" <?php echo (set_value('supplier_id', $medicine->supplier_id) == $sup['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($sup['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- 4. Price -->
                    <div>
                        <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Price (₹) <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 text-slate-500 rounded-l-xl border-slate-200">₹</span>
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01" 
                                   name="price" 
                                   id="price" 
                                   value="<?php echo set_value('price', $medicine->price); ?>" 
                                   class="form-control font-mono text-sm rounded-r-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('price') ? 'is-invalid' : ''; ?>" 
                                   required>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1 mb-0">Must be greater than 0.</p>
                        <div class="invalid-feedback text-xs">Price must be greater than 0.</div>
                    </div>

                    <!-- 5. Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Stock Quantity <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               min="0" 
                               name="stock_quantity" 
                               id="stock_quantity" 
                               value="<?php echo set_value('stock_quantity', $medicine->stock_quantity); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('stock_quantity') ? 'is-invalid' : ''; ?>" 
                               required>
                        <p class="text-[11px] text-slate-400 mt-1 mb-0">Cannot be negative (&ge; 0).</p>
                        <div class="invalid-feedback text-xs">Stock quantity cannot be negative.</div>
                    </div>
                </div>

                <!-- 6. Description -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Description & Medical Indications
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500"><?php echo set_value('description', $medicine->description); ?></textarea>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: EXPIRY, IMAGE & STATUS -->
        <div class="space-y-6">
            
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-emerald-600"></i>
                    <span>Inventory & Status</span>
                </h4>

                <div class="space-y-4">
                    <!-- 7. Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Expiry Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" 
                               name="expiry_date" 
                               id="expiry_date" 
                               value="<?php echo set_value('expiry_date', $medicine->expiry_date); ?>" 
                               class="form-control font-mono text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('expiry_date') ? 'is-invalid' : ''; ?>" 
                               required>
                    </div>

                    <!-- 8. Image URL -->
                    <div>
                        <label for="image_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Medicine Image URL
                        </label>
                        <input type="url" 
                               name="image_url" 
                               id="image_url" 
                               value="<?php echo set_value('image_url', $medicine->image_url); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                               oninput="updateImagePreview(this.value)">
                        
                        <!-- Image Preview Box -->
                        <div class="mt-2.5 p-2 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Image Preview</span>
                            <div class="w-24 h-24 rounded-xl bg-white border border-slate-200 mx-auto overflow-hidden shadow-xs">
                                <img id="previewImage" 
                                     src="<?php echo html_escape($medicine->image_url ?: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&auto=format&fit=crop&q=80'); ?>" 
                                     alt="Preview" 
                                     class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- 9. Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Status
                        </label>
                        <select name="status" id="status" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                            <option value="active" <?php echo (set_value('status', $medicine->status) === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo (set_value('status', $medicine->status) === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="app-card p-5 bg-gradient-to-br from-slate-50 to-white">
                <button type="submit" class="btn btn-emerald w-full py-3 rounded-xl font-bold text-sm shadow-md shadow-emerald-600/30 hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Changes</span>
                </button>
                <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light w-full py-2.5 rounded-xl font-semibold text-xs border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none block text-center mt-2.5">
                    Discard Changes
                </a>
            </div>

        </div>

    </div>
</form>

<script>
function updateImagePreview(url) {
    const preview = document.getElementById('previewImage');
    if (preview && url) {
        preview.src = url;
    }
}
</script>
