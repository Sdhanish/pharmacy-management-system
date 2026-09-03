<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-1">Medicine Categories</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Group medicines by therapeutic classification, brand, and type.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <button class="btn btn-emerald text-xs sm:text-sm font-medium rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Add Category</span>
        </button>
    </div>
</div>

<!-- Category Grid Preview -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    <?php if (isset($categories) && is_array($categories)): ?>
        <?php foreach ($categories as $cat): ?>
            <div class="app-card app-card-hover p-5 flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid <?php echo html_escape($cat['icon']); ?> text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5"><?php echo html_escape($cat['name']); ?></h4>
                        <p class="text-xs text-slate-400 mb-0"><?php echo $cat['count']; ?> Products listed</p>
                    </div>
                </div>
                <span class="text-slate-300 hover:text-emerald-600 transition">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="app-card p-6 text-center bg-slate-50/50 border-dashed">
    <div class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500">
        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
        <span>Categories View Layout Successfully Bound to CI3 Master Template</span>
    </div>
</div>
