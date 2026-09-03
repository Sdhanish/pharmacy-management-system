<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$stock = (int) ($medicine->stock_quantity ?? 0);
$exp_date = $medicine->expiry_date;
$is_expired = ($exp_date && strtotime($exp_date) < strtotime(date('Y-m-d')));
$is_expiring_soon = ($exp_date && !$is_expired && strtotime($exp_date) <= strtotime('+30 days'));

$image_src = !empty($medicine->image_url) ? $medicine->image_url : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop&q=80';
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light btn-sm rounded-lg p-1.5 text-slate-500 hover:text-emerald-700 text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 mb-0">
                <?php echo html_escape($medicine->medicine_name); ?>
            </h2>
            <?php if ($medicine->status === 'active'): ?>
                <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">Active</span>
            <?php else: ?>
                <span class="badge bg-slate-100 text-slate-600 font-semibold px-2.5 py-1 rounded-full text-xs">Inactive</span>
            <?php endif; ?>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">
            Registered in category <span class="font-semibold text-slate-700"><?php echo html_escape($medicine->category_name ?? 'General'); ?></span> &bull; 
            Product ID #<?php echo $medicine->id; ?>
        </p>
    </div>
    <div class="flex items-center gap-2.5">
        <form action="<?php echo base_url('cart/add'); ?>" method="POST" class="inline m-0">
            <input type="hidden" name="medicine_id" value="<?php echo $medicine->id; ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center gap-2" <?php echo ($stock <= 0) ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-cart-plus"></i>
                <span>Add to Cart</span>
            </button>
        </form>
        <a href="<?php echo base_url('medicines/edit/' . $medicine->id); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit</span>
        </a>
        <button type="button" 
                class="btn btn-outline-danger text-xs sm:text-sm font-semibold rounded-xl px-3.5 py-2.5" 
                data-bs-toggle="modal" 
                data-bs-target="#deleteMedicineModal">
            <i class="fa-solid fa-trash-can mr-1"></i>
        </button>
        <a href="<?php echo base_url('medicines'); ?>" class="btn btn-light text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-100 text-decoration-none">
            Back to List
        </a>
    </div>
</div>

<!-- MAIN DETAILS LAYOUT -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- LEFT 1 COL: MEDICINE IMAGE & QUICK STATS -->
    <div class="space-y-6">
        <!-- Image Card -->
        <div class="app-card p-5 text-center overflow-hidden">
            <div class="w-full h-56 rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden mb-4 shadow-inner">
                <img src="<?php echo html_escape($image_src); ?>" 
                     alt="<?php echo html_escape($medicine->medicine_name); ?>" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop&q=80'">
            </div>
            
            <h4 class="text-base font-bold text-slate-900 mb-1">
                <?php echo html_escape($medicine->medicine_name); ?>
            </h4>
            <span class="badge bg-slate-100 text-slate-700 font-medium text-xs px-2.5 py-1 rounded-md">
                <?php echo html_escape($medicine->category_name ?? 'General'); ?>
            </span>
        </div>

        <!-- Supplier Information -->
        <div class="app-card p-5">
            <h5 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100">
                Supplier & Manufacturer
            </h5>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-building text-base"></i>
                </div>
                <div>
                    <h6 class="text-sm font-bold text-slate-900 mb-0">
                        <?php echo html_escape($medicine->supplier_name ?? 'Direct Distribution'); ?>
                    </h6>
                    <p class="text-[11px] text-slate-400 mb-0">Verified Pharmaceutical Supplier</p>
                </div>
            </div>

            <?php if (!empty($medicine->supplier_contact) || !empty($medicine->supplier_phone)): ?>
                <div class="space-y-1.5 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <?php if (!empty($medicine->supplier_contact)): ?>
                        <p class="mb-0 flex items-center gap-2">
                            <i class="fa-regular fa-user text-slate-400 w-4"></i>
                            <span><?php echo html_escape($medicine->supplier_contact); ?></span>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($medicine->supplier_phone)): ?>
                        <p class="mb-0 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-slate-400 w-4"></i>
                            <span class="font-mono"><?php echo html_escape($medicine->supplier_phone); ?></span>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT 2 COLS: SPECIFICATIONS, PRICING & EXPIRY STATUS -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- 3 KPI Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <!-- Price -->
            <div class="app-card p-5 border-l-4 border-l-emerald-500">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Unit Price</span>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-2xl font-bold text-slate-900 mb-0">
                        $<?php echo number_format((float)$medicine->price, 2); ?>
                    </h3>
                    <span class="text-xs text-slate-400">/ unit</span>
                </div>
                <p class="text-xs text-slate-400 mt-1 mb-0">Retail inventory value</p>
            </div>

            <!-- Stock Quantity -->
            <div class="app-card p-5 border-l-4 <?php echo ($stock <= 10) ? 'border-l-rose-500' : 'border-l-cyan-500'; ?>">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Stock Quantity</span>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-2xl font-bold <?php echo ($stock <= 10) ? 'text-rose-600' : 'text-slate-900'; ?> mb-0">
                        <?php echo $stock; ?>
                    </h3>
                    <span class="text-xs text-slate-500">units</span>
                </div>
                <p class="text-xs mt-1 mb-0 font-medium <?php echo ($stock <= 10) ? 'text-rose-500' : 'text-slate-400'; ?>">
                    <?php echo ($stock <= 10) ? 'Low stock alert!' : 'In stock'; ?>
                </p>
            </div>

            <!-- Expiry Date -->
            <div class="app-card p-5 border-l-4 <?php echo $is_expired ? 'border-l-rose-500' : ($is_expiring_soon ? 'border-l-purple-500' : 'border-l-emerald-500'); ?>">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Expiry Date</span>
                <h3 class="text-base font-bold font-mono <?php echo $is_expired ? 'text-rose-600' : ($is_expiring_soon ? 'text-purple-700' : 'text-slate-800'); ?> mb-0">
                    <?php echo date('M d, Y', strtotime($exp_date)); ?>
                </h3>
                <p class="text-xs mt-1 mb-0 font-medium <?php echo $is_expired ? 'text-rose-600 font-bold' : ($is_expiring_soon ? 'text-purple-600 font-semibold' : 'text-emerald-600'); ?>">
                    <?php 
                    if ($is_expired) echo 'Quarantine: Expired';
                    elseif ($is_expiring_soon) echo 'Attention: ≤ 30 days';
                    else echo 'Optimal status';
                    ?>
                </p>
            </div>

        </div>

        <!-- Description & Specifications Card -->
        <div class="app-card p-5 sm:p-6">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-3 mb-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-medical text-emerald-600"></i>
                <span>Medical Description & Details</span>
            </h4>

            <?php if (!empty($medicine->description)): ?>
                <div class="text-sm text-slate-700 leading-relaxed bg-slate-50/75 p-4 rounded-2xl border border-slate-100 mb-6">
                    <?php echo nl2br(html_escape($medicine->description)); ?>
                </div>
            <?php else: ?>
                <p class="text-xs text-slate-400 italic mb-6">No specific clinical description entered for this product.</p>
            <?php endif; ?>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-0">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium mb-1">Category</dt>
                    <dd class="font-bold text-slate-800 mb-0"><?php echo html_escape($medicine->category_name ?? 'N/A'); ?></dd>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium mb-1">Supplier / Brand</dt>
                    <dd class="font-bold text-slate-800 mb-0"><?php echo html_escape($medicine->supplier_name ?? 'Direct'); ?></dd>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium mb-1">System Record Created</dt>
                    <dd class="font-mono text-slate-700 mb-0"><?php echo date('M d, Y &bull; h:i A', strtotime($medicine->created_at)); ?></dd>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium mb-1">Last Updated</dt>
                    <dd class="font-mono text-slate-700 mb-0"><?php echo date('M d, Y &bull; h:i A', strtotime($medicine->updated_at)); ?></dd>
                </div>
            </dl>
        </div>

    </div>

</div>

<!-- BOOTSTRAP 5 DELETE MODAL -->
<div class="modal fade" id="deleteMedicineModal" tabindex="-1" aria-labelledby="deleteMedicineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1" id="deleteMedicineModalLabel">Delete Medicine</h4>
                <p class="text-xs text-slate-500 mb-4">
                    Are you sure you want to delete <strong><?php echo html_escape($medicine->medicine_name); ?></strong>?
                </p>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-600 border border-slate-200" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="<?php echo base_url('medicines/delete/' . $medicine->id); ?>" class="btn btn-danger rounded-xl px-4 py-2.5 text-xs font-semibold shadow-md shadow-rose-600/20 text-white text-decoration-none">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Yes, Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
