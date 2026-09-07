<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('categories'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Edit Category: <?php echo html_escape($category->name); ?></h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Update category details, therapeutic notes, and active status.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('categories'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="editCategoryForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>Update Category</span>
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

<!-- Flash Error -->
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
        </div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- FORM CONTAINER -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2">
        <div class="app-card p-6 sm:p-7">
            <form action="<?php echo base_url('categories/update/' . $category->id); ?>" method="POST" id="editCategoryForm" class="needs-validation" novalidate>
                
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-600"></i>
                    <span>Category Specifications</span>
                </h4>

                <!-- 1. Category Name -->
                <div class="mb-5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="<?php echo set_value('name', $category->name); ?>" 
                           class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                           placeholder="e.g. Antibiotics, Cardiovascular" 
                           required>
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('name') ?: 'Please enter a valid unique category name (at least 2 characters).'; ?>
                    </div>
                    <?php if (!empty($category->slug)): ?>
                        <p class="text-[11px] text-slate-400 mt-1 font-mono">Current slug: <?php echo html_escape($category->slug); ?></p>
                    <?php endif; ?>
                </div>

                <!-- 2. Description -->
                <div class="mb-5">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('description') ? 'is-invalid' : ''; ?>" 
                              placeholder="Brief description of the therapeutic category..."><?php echo set_value('description', $category->description); ?></textarea>
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('description'); ?>
                    </div>
                </div>

                <!-- 3. Status -->
                <div class="mb-6">
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                        <option value="active" <?php echo (set_value('status', $category->status) === 'active') ? 'selected' : ''; ?>>Active (Visible in medicine forms)</option>
                        <option value="inactive" <?php echo (set_value('status', $category->status) === 'inactive') ? 'selected' : ''; ?>>Inactive (Archived)</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="<?php echo base_url('categories'); ?>" class="btn btn-light text-xs font-semibold rounded-xl px-4 py-2.5 text-slate-600 border border-slate-200 hover:bg-slate-100 text-decoration-none">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-emerald text-xs font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Update Category</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- RIGHT SIDEBAR HELPER -->
    <div class="lg:col-span-1 space-y-5">
        <!-- Linked Medicines Status Card -->
        <div class="app-card p-5 bg-slate-50/70 border-slate-200">
            <h5 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-pills text-emerald-600"></i>
                <span>Assigned Medicines</span>
            </h5>
            <?php $med_count = (int)($category->total_medicines ?? 0); ?>
            <div class="p-3 bg-white rounded-xl border border-slate-100 mb-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Total Products Linked:</span>
                    <span class="font-bold text-slate-800 text-sm"><?php echo $med_count; ?></span>
                </div>
            </div>
            <?php if ($med_count > 0): ?>
                <a href="<?php echo base_url('medicines?category_id=' . $category->id); ?>" class="btn btn-outline-success btn-sm rounded-xl w-full text-xs font-semibold flex items-center justify-center gap-1.5 text-decoration-none">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>View Linked Products</span>
                </a>
            <?php else: ?>
                <p class="text-[11px] text-slate-400 mb-0 italic">No medicines currently linked to this category.</p>
            <?php endif; ?>
        </div>

        <div class="app-card p-5 bg-emerald-50/50 border-emerald-100">
            <h5 class="text-xs font-bold uppercase tracking-wider text-emerald-900 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-emerald-600"></i>
                <span>Note</span>
            </h5>
            <p class="text-xs text-slate-600 mb-0">
                Updating the category name will also automatically keep its unique URL slug synchronized while preserving all existing medicine relations.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editCategoryForm');
    if (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
});
</script>
