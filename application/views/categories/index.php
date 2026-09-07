<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-2xl font-bold text-slate-900 mb-0">Medicine Categories</h2>
            <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-xs">
                <?php echo number_format($total_rows ?? 0); ?> Total
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Organize and manage pharmaceutical classifications, active molecule groups, and therapeutic categories.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="<?php echo base_url('categories/create'); ?>" class="btn btn-emerald text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-md shadow-emerald-600/20 hover:shadow-lg transition text-decoration-none">
            <i class="fa-solid fa-plus"></i>
            <span>Add Category</span>
        </a>
    </div>
</div>

<!-- Flash Notifications -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-emerald-200 bg-emerald-50 text-emerald-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span><?php echo $this->session->flashdata('success'); ?></span>
        </div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-6 p-4" role="alert">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
        </div>
        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- SUMMARY METRIC CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="app-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Total Categories</p>
            <h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format($summary['total_categories'] ?? 0); ?></h3>
        </div>
    </div>

    <div class="app-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Active Status</p>
            <h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format($summary['active_categories'] ?? 0); ?></h3>
        </div>
    </div>

    <div class="app-card p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-pills"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Catalog Medicines</p>
            <h3 class="text-xl font-bold text-slate-900 mb-0"><?php echo number_format($summary['total_medicines'] ?? 0); ?> Units</h3>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER BAR -->
<div class="app-card p-4 sm:p-5 mb-6">
    <form action="<?php echo base_url('categories'); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
        <div class="sm:col-span-10">
            <label for="search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Search Categories
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="<?php echo html_escape($search ?? ''); ?>" 
                       class="form-control form-control-sm pl-9 text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" 
                       placeholder="Search by category name, description, slug...">
            </div>
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit" class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 w-full text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Filter</span>
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?php echo base_url('categories'); ?>" class="btn btn-light btn-sm rounded-xl px-3 py-2 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-decoration-none" title="Reset Search">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- CATEGORIES TABLE -->
<div class="app-card p-0 overflow-hidden mb-6">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-xs sm:text-sm" id="categoriesTable">
            <thead class="table-light text-[11px] text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="sortable py-3.5 pl-5" data-sort="number">#</th>
                    <th class="sortable py-3.5" data-sort="text">Category Name</th>
                    <th class="sortable py-3.5" data-sort="text">Description</th>
                    <th class="sortable py-3.5 text-center" data-sort="number">Linked Medicines</th>
                    <th class="sortable py-3.5 text-center" data-sort="text">Status</th>
                    <th class="py-3.5 text-end pr-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (isset($categories) && !empty($categories)): ?>
                    <?php foreach ($categories as $index => $cat): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 pl-5 font-mono text-slate-400 text-xs">
                                <?php echo ($offset ?? 0) + $index + 1; ?>
                            </td>
                            <td class="py-3 font-semibold text-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i class="fa-solid fa-tag"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block"><?php echo html_escape($cat['name']); ?></span>
                                        <?php if (!empty($cat['slug'])): ?>
                                            <span class="text-[11px] text-slate-400 font-mono"><?php echo html_escape($cat['slug']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-slate-600 max-w-xs">
                                <?php if (!empty($cat['description'])): ?>
                                    <span class="line-clamp-2 text-xs"><?php echo html_escape($cat['description']); ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400 italic text-xs">No description provided</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php $med_count = (int)($cat['total_medicines'] ?? 0); ?>
                                <?php if ($med_count > 0): ?>
                                    <a href="<?php echo base_url('medicines?category_id=' . $cat['id']); ?>" 
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition text-decoration-none">
                                        <i class="fa-solid fa-pills text-[10px]"></i>
                                        <span><?php echo $med_count; ?> <?php echo ($med_count === 1) ? 'Medicine' : 'Medicines'; ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500">
                                        0 Medicines
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if (($cat['status'] ?? 'active') === 'active'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Active</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Inactive</span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-end pr-5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="<?php echo base_url('categories/edit/' . $cat['id']); ?>" 
                                       class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" 
                                       title="Edit Category">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <a href="<?php echo base_url('categories/delete/' . $cat['id']); ?>" 
                                       class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" 
                                       title="Delete Category"
                                       onclick="return confirm('Are you sure you want to delete category &quot;<?php echo html_escape(addslashes($cat['name'])); ?>&quot;?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-tags text-4xl text-slate-300 mb-3 block"></i>
                            <p class="text-sm font-semibold text-slate-600 mb-1">No Categories Found</p>
                            <p class="text-xs text-slate-400 mb-4">
                                <?php echo !empty($search) ? 'No categories matched your search criteria "' . html_escape($search) . '".' : 'Get started by creating your first medicine category.'; ?>
                            </p>
                            <a href="<?php echo base_url('categories/create'); ?>" class="btn btn-emerald btn-sm rounded-xl px-4 py-2 text-xs font-semibold inline-flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i>
                                <span>Create Category</span>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <?php if (isset($pagination_links) && !empty($pagination_links)): ?>
        <div class="px-5 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <p class="text-xs text-slate-500 mb-0 font-medium">
                Showing <?php echo min($total_rows, ($offset ?? 0) + 1); ?> to <?php echo min($total_rows, ($offset ?? 0) + count($categories)); ?> of <?php echo $total_rows; ?> categories
            </p>
            <div>
                <?php echo $pagination_links; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

