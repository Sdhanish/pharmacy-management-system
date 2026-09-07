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
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Add New Category</h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Register a new drug classification or medicine category to organize the catalog.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('categories'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="createCategoryForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>Save Category</span>
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
            <form action="<?php echo base_url('categories/store'); ?>" method="POST" id="createCategoryForm" class="needs-validation" novalidate>
                
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-tag text-emerald-600"></i>
                    <span>Category Information</span>
                </h4>

                <!-- 1. Category Name -->
                <div class="mb-5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="<?php echo set_value('name'); ?>" 
                           class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                           placeholder="e.g. Antibiotics, Cardiovascular, Ophthalmic" 
                           required 
                           autofocus>
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('name') ?: 'Please enter a valid unique category name (at least 2 characters).'; ?>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Must be unique across all existing medicine categories.</p>
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
                              placeholder="Brief description of the therapeutic category, common usage, or drug class guidelines..."><?php echo set_value('description'); ?></textarea>
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
                        <option value="active" <?php echo (set_value('status', 'active') === 'active') ? 'selected' : ''; ?>>Active (Visible in medicine forms)</option>
                        <option value="inactive" <?php echo (set_value('status') === 'inactive') ? 'selected' : ''; ?>>Inactive (Archived)</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="<?php echo base_url('categories'); ?>" class="btn btn-light text-xs font-semibold rounded-xl px-4 py-2.5 text-slate-600 border border-slate-200 hover:bg-slate-100 text-decoration-none">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-emerald text-xs font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Category</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- RIGHT SIDEBAR HELPER -->
    <div class="lg:col-span-1 space-y-5">
        <div class="app-card p-5 bg-emerald-50/50 border-emerald-100">
            <h5 class="text-xs font-bold uppercase tracking-wider text-emerald-900 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-emerald-600"></i>
                <span>Category Guidelines</span>
            </h5>
            <ul class="text-xs text-slate-600 space-y-2.5 pl-4 mb-0 list-disc">
                <li><strong>Unique Names:</strong> Each category must have a distinct name to avoid prescription ambiguities.</li>
                <li><strong>Dynamic Dropdown:</strong> Once added, this category is immediately available in the <em>Add Medicine</em> and <em>Edit Medicine</em> dropdowns.</li>
                <li><strong>Slug Generation:</strong> A URL-friendly slug is automatically created from the category name.</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('createCategoryForm');
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
