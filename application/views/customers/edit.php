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
            <h2 class="text-2xl font-bold text-slate-900 mb-0">
                Edit Customer: <?php echo html_escape($customer->name); ?>
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Update customer contact credentials, shipping address, or account status.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('customers'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Cancel
        </a>
        <button type="submit" form="editCustomerForm" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>Update Profile</span>
        </button>
    </div>
</div>

<!-- Validation Errors Alert Box -->
<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="font-bold mb-1.5 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span>Please correct the errors below:</span>
        </div>
        <?php echo validation_errors('<p class="mb-0 text-xs">- ', '</p>'); ?>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- EDIT FORM -->
<form action="<?php echo base_url('customers/update/' . $customer->id); ?>" method="POST" id="editCustomerForm" class="needs-validation" novalidate>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- LEFT 2 COLS: CUSTOMER CONTACT DETAILS -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-emerald-600"></i>
                    <span>Personal & Contact Information</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    
                    <!-- Customer Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Customer Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="<?php echo set_value('name', $customer->name); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                               required>
                        <div class="invalid-feedback text-xs">Please provide the customer name.</div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="<?php echo set_value('email', $customer->email); ?>" 
                               class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500 <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                               required>
                        <div class="invalid-feedback text-xs">Please provide a valid, unique email address.</div>
                    </div>

                    <!-- Phone Number -->
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Phone / Mobile Number
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 text-slate-500 rounded-l-xl border-slate-200">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </span>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="<?php echo set_value('phone', $customer->phone); ?>" 
                                   class="form-control text-sm rounded-r-xl border-slate-200 focus:border-emerald-500" 
                                   placeholder="e.g. +1 555-0182">
                        </div>
                    </div>

                </div>

                <!-- Physical Address -->
                <div>
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Delivery & Physical Address
                    </label>
                    <textarea name="address" 
                              id="address" 
                              rows="3" 
                              class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" 
                              placeholder="Street address, city, state, postal code..."><?php echo set_value('address', $customer->address); ?></textarea>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: ACCOUNT STATUS & ACTIONS -->
        <div class="space-y-6">
            
            <div class="app-card p-5 sm:p-6">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                    <span>Account Settings</span>
                </h4>

                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Account Status
                        </label>
                        <select name="status" id="status" class="form-select text-sm rounded-xl border-slate-200 focus:border-emerald-500">
                            <option value="active" <?php echo (set_value('status', $customer->status) === 'active') ? 'selected' : ''; ?>>Active (Enabled)</option>
                            <option value="inactive" <?php echo (set_value('status', $customer->status) === 'inactive') ? 'selected' : ''; ?>>Inactive (Disabled)</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1 mb-0">Inactive customers are restricted from ordering prescriptions.</p>
                    </div>

                    <!-- Meta Information -->
                    <div class="pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-500">
                        <div class="flex justify-between">
                            <span>Customer ID:</span>
                            <span class="font-mono font-bold text-slate-700">#CUS-<?php echo str_pad($customer->id, 4, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Registered On:</span>
                            <span class="font-mono text-slate-700"><?php echo date('M d, Y', strtotime($customer->created_at)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="app-card p-5 bg-gradient-to-br from-slate-50 to-white">
                <button type="submit" class="btn btn-emerald w-full py-3 rounded-xl font-bold text-sm shadow-md shadow-emerald-600/30 hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Customer Changes</span>
                </button>
                <a href="<?php echo base_url('customers'); ?>" class="btn btn-light w-full py-2.5 rounded-xl font-semibold text-xs border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none block text-center mt-2.5">
                    Discard Changes
                </a>
            </div>

        </div>

    </div>
</form>
