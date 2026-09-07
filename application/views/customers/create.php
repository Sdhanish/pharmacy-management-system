<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('customers'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Add New Customer</h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Register a new patient or pharmacy customer profile for dispensing and prescription records.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('customers'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="createCustomerForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Register Customer</span>
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
            <form action="<?php echo base_url('customers/store'); ?>" method="POST" id="createCustomerForm" class="needs-validation" novalidate>
                
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-user text-emerald-600"></i>
                    <span>Customer Details</span>
                </h4>

                <!-- 1. Customer Name -->
                <div class="mb-5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="<?php echo set_value('name'); ?>" 
                           class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                           placeholder="e.g. Robert Johnson" 
                           required 
                           autofocus>
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('name') ?: 'Please enter the customer\'s full name.'; ?>
                    </div>
                </div>

                <!-- 2. Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="<?php echo set_value('email'); ?>" 
                           class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                           placeholder="e.g. robert.j@example.com" 
                           required>
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('email') ?: 'Please enter a valid unique email address.'; ?>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 mb-0">Must be unique across all registered pharmacy accounts.</p>
                </div>

                <!-- 3. Phone Number -->
                <div class="mb-5">
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Phone Number
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           value="<?php echo set_value('phone'); ?>" 
                           class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('phone') ? 'is-invalid' : ''; ?>" 
                           placeholder="e.g. +1 555-0188">
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('phone'); ?>
                    </div>
                </div>

                <!-- 4. Physical Address -->
                <div class="mb-5">
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Residential / Delivery Address
                    </label>
                    <textarea name="address" 
                              id="address" 
                              rows="3" 
                              class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('address') ? 'is-invalid' : ''; ?>" 
                              placeholder="Street address, apartment, city, state, postal code..."><?php echo set_value('address'); ?></textarea>
                    <div class="invalid-feedback text-xs">
                        <?php echo form_error('address'); ?>
                    </div>
                </div>

                <!-- 5. Account Status -->
                <div class="mb-6">
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Account Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                        <option value="active" <?php echo (set_value('status', 'active') === 'active') ? 'selected' : ''; ?>>Active (Eligible for sales & prescriptions)</option>
                        <option value="inactive" <?php echo (set_value('status') === 'inactive') ? 'selected' : ''; ?>>Inactive (Suspended / Archived)</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="<?php echo base_url('customers'); ?>" class="btn btn-light text-xs font-semibold rounded-xl px-4 py-2.5 text-slate-600 border border-slate-200 hover:bg-slate-100 text-decoration-none">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-emerald text-xs font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Register Customer</span>
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
                <span>Customer Guidelines</span>
            </h5>
            <ul class="text-xs text-slate-600 space-y-2.5 pl-4 mb-0 list-disc">
                <li><strong>Active Status:</strong> Active customers can be selected when dispensing medicines at the POS or billing prescriptions.</li>
                <li><strong>Inactive Status:</strong> Temporarily suspends the account without deleting previous purchase audit records.</li>
                <li><strong>Unique Email:</strong> Every customer profile requires a unique email for authentication and prescription tracking.</li>
            </ul>
        </div>
    </div>
</div>
