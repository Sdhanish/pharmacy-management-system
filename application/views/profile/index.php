<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-1">Profile Settings</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Update the name, email, or password used for your account.</p>
    </div>
    <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold shadow-md shadow-emerald-600/20" aria-label="Profile avatar">A</div>
</div>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <?php echo validation_errors('<p class="mb-1 text-xs">', '</p>'); ?>
    </div>
<?php endif; ?>

<form action="<?php echo base_url('profile/update'); ?>" method="post" class="max-w-3xl">
    <div class="app-card p-5 sm:p-6 mb-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-user-gear text-emerald-600"></i>
            Account Information
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Display Name</label>
                <input type="text" name="name" id="name" value="<?php echo set_value('name', $user->name); ?>" class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" required maxlength="100">
                <p class="text-[11px] text-slate-400 mt-1 mb-0">Use a name such as Admin or Owner.</p>
            </div>
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                <input type="email" name="email" id="email" value="<?php echo set_value('email', $user->email); ?>" class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" required maxlength="100">
            </div>
        </div>
    </div>

    <div class="app-card p-5 sm:p-6 mb-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-key text-emerald-600"></i>
            Change Password
        </h3>
        <p class="text-xs text-slate-500 mb-4">Leave both fields blank to keep your current password.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">New Password</label>
                <input type="password" name="password" id="password" class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" minlength="6" autocomplete="new-password">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control text-sm rounded-xl border-slate-200 focus:border-emerald-500" minlength="6" autocomplete="new-password">
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-2.5">
        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-light text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 text-decoration-none">Cancel</a>
        <button type="submit" class="btn btn-emerald text-sm font-semibold rounded-xl px-5 py-2.5 shadow-md shadow-emerald-600/20 flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            Save Changes
        </button>
    </div>
</form>