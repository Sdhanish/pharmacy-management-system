<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$user = isset($current_user) ? $current_user : array(
    'name' => 'Dr. Dhanish S',
    'role' => 'Administrator',
    'avatar' => ''
);
?>

<!-- Top Navigation Bar -->
<header id="topbar" class="bg-white/95 border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between shadow-xs">
    <!-- Left Section: Toggle Button & Breadcrumbs -->
    <div class="flex items-center gap-3 lg:gap-4">
        <!-- Sidebar Toggle (Mobile / Tablet) -->
        <button id="sidebar-toggle" class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500" aria-label="Open Sidebar">
            <i class="fa-solid fa-bars-staggered text-lg"></i>
        </button>

        <!-- Dynamic Title & Breadcrumbs -->
        <div class="hidden sm:block">
            <h1 class="text-lg font-bold text-slate-900 mb-0 leading-tight">
                <?php echo isset($page_title) ? html_escape($page_title) : 'Dashboard'; ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center gap-1.5 text-xs text-slate-500 mb-0 font-medium">
                    <li><a href="<?php echo base_url('dashboard'); ?>" class="text-slate-400 hover:text-emerald-600 transition-colors text-decoration-none"><i class="fa-solid fa-house-chimney text-[11px] mr-1"></i>Home</a></li>
                    <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $crumb_name => $crumb_url): ?>
                            <li class="text-slate-300">/</li>
                            <?php if ($crumb_url): ?>
                                <li><a href="<?php echo base_url($crumb_url); ?>" class="text-slate-400 hover:text-emerald-600 transition-colors text-decoration-none"><?php echo html_escape($crumb_name); ?></a></li>
                            <?php else: ?>
                                <li class="text-emerald-700 font-semibold" aria-current="page"><?php echo html_escape($crumb_name); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Center Section: Search Bar -->
    <div class="hidden md:flex items-center flex-1 max-w-md mx-4">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="global-search-input" 
                   class="w-full pl-9 pr-14 py-2 bg-slate-100/80 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-all" 
                   placeholder="Quick search medicines, batches, SKU...">
            <div class="absolute inset-y-0 right-0 flex items-center pr-2.5">
                <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded shadow-xs">Ctrl+K</kbd>
            </div>
        </div>
    </div>

    <!-- Right Section: Status, Notifications & User Dropdown -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Live System Status Badge -->
        <div class="hidden xl:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-800">
            <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-emerald"></span>
            <span>Pharmacy Online</span>
        </div>

        <!-- MINI CART COUNTER IN NAVBAR -->
        <?php
        $cart_count = isset($cart_summary['total_items']) ? (int)$cart_summary['total_items'] : 0;
        $cart_subtotal = isset($cart_summary['subtotal']) ? (float)$cart_summary['subtotal'] : 0.00;
        $mini_cart_items = isset($cart_summary['items']) ? array_slice($cart_summary['items'], 0, 3) : array();
        ?>
        <div class="dropdown">
            <button class="relative p-2 rounded-xl text-slate-500 hover:text-emerald-600 hover:bg-slate-100 transition-colors focus:outline-none" 
                    type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Shopping Cart">
                <i class="fa-solid fa-cart-shopping text-lg"></i>
                <?php if ($cart_count > 0): ?>
                    <span id="navCartBadge" class="position-absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-emerald-600 rounded-full border-2 border-white">
                        <?php echo $cart_count; ?>
                    </span>
                <?php else: ?>
                    <span id="navCartBadge" class="position-absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-emerald-600 rounded-full border-2 border-white hidden">
                        0
                    </span>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-xl border border-slate-100 rounded-2xl p-3 w-80 mt-2" aria-labelledby="cartDropdown">
                <div class="px-2 py-1.5 border-b border-slate-100 flex items-center justify-between mb-2">
                    <span class="font-bold text-xs text-slate-800 uppercase tracking-wider">Shopping Cart</span>
                    <span class="badge bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full font-bold">
                        <?php echo $cart_count; ?> <?php echo ($cart_count === 1) ? 'Item' : 'Items'; ?>
                    </span>
                </div>

                <?php if (!empty($mini_cart_items)): ?>
                    <div class="space-y-2 max-h-56 overflow-y-auto mb-2">
                        <?php foreach ($mini_cart_items as $c_item): ?>
                            <div class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                    <img src="<?php echo html_escape($c_item['image_url'] ?: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=80&auto=format&fit=crop&q=80'); ?>" 
                                         alt="Medicine" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate mb-0">
                                        <?php echo html_escape($c_item['medicine_name']); ?>
                                    </p>
                                    <p class="text-[11px] text-slate-400 mb-0 font-mono">
                                        <?php echo $c_item['quantity']; ?> &times; $<?php echo number_format($c_item['unit_price'], 2); ?>
                                    </p>
                                </div>
                                <span class="text-xs font-bold font-mono text-emerald-700">
                                    $<?php echo number_format($c_item['subtotal'], 2); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs mb-3 px-1">
                        <span class="text-slate-500 font-medium">Subtotal:</span>
                        <span class="font-mono font-bold text-emerald-700 text-sm">$<?php echo number_format($cart_subtotal, 2); ?></span>
                    </div>

                    <a href="<?php echo base_url('cart'); ?>" class="btn btn-emerald w-full py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm text-decoration-none">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span>View Cart & Checkout</span>
                    </a>
                <?php else: ?>
                    <div class="py-6 text-center text-slate-400">
                        <i class="fa-solid fa-cart-shopping text-2xl mb-1 text-slate-300"></i>
                        <p class="text-xs mb-0">Your cart is currently empty.</p>
                        <a href="<?php echo base_url('medicines'); ?>" class="text-[11px] text-emerald-600 font-semibold hover:underline block mt-2">Browse Catalog</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notification Bell with Dropdown -->
        <div class="dropdown">
            <button class="relative p-2 rounded-xl text-slate-500 hover:text-emerald-600 hover:bg-slate-100 transition-colors focus:outline-none" 
                    type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="position-absolute top-1 right-1 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-slate-100 rounded-2xl p-2 w-80 mt-2" aria-labelledby="notificationDropdown">
                <li class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-xs text-slate-800 uppercase tracking-wider">Notifications</span>
                    <span class="badge bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full">2 New</span>
                </li>
                <li>
                    <a class="dropdown-item py-2.5 px-3 rounded-xl flex items-start gap-3 hover:bg-slate-50" href="<?php echo base_url('stock'); ?>">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-800 mb-0.5">Low Stock Alert</p>
                            <p class="text-[11px] text-slate-500 mb-0">Paracetamol 500mg has 8 strips remaining.</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2.5 px-3 rounded-xl flex items-start gap-3 hover:bg-slate-50" href="<?php echo base_url('stock-history'); ?>">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-box text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-800 mb-0.5">New Stock Received</p>
                            <p class="text-[11px] text-slate-500 mb-0">Batch #AMX-2026 received from MedSupply.</p>
                        </div>
                    </a>
                </li>
                <li class="pt-2 border-t border-slate-100 text-center">
                    <a href="<?php echo base_url('reports'); ?>" class="text-[11px] text-emerald-600 font-semibold hover:underline">View All Alerts</a>
                </li>
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none" 
                    type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-emerald-400 p-0.5 shadow-sm">
                    <img class="w-full h-full object-cover rounded-[10px]" 
                         src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=120&auto=format&fit=crop&q=80" 
                         alt="<?php echo html_escape($user['name']); ?>">
                </div>
                <div class="hidden md:block text-left">
                    <div class="text-xs font-bold text-slate-800 leading-tight"><?php echo html_escape($user['name']); ?></div>
                    <div class="text-[10px] font-semibold text-emerald-600 leading-none mt-0.5"><?php echo html_escape($user['role']); ?></div>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden md:block"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-slate-100 rounded-2xl p-2 w-56 mt-2" aria-labelledby="userProfileDropdown">
                <li class="px-3 py-2 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-900 mb-0"><?php echo html_escape($user['name']); ?></p>
                    <p class="text-[11px] text-slate-400 mb-0 truncate"><?php echo isset($user['email']) ? html_escape($user['email']) : 'admin@pharmacare.com'; ?></p>
                </li>
                <li>
                    <a class="dropdown-item py-2 px-3 rounded-lg text-xs flex items-center gap-2.5 text-slate-700 hover:bg-slate-50 mt-1" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa-regular fa-user text-slate-400 w-4"></i> Profile Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2 px-3 rounded-lg text-xs flex items-center gap-2.5 text-slate-700 hover:bg-slate-50" href="<?php echo base_url('reports'); ?>">
                        <i class="fa-solid fa-shield-halved text-slate-400 w-4"></i> Security & Audit
                    </a>
                </li>
                <li class="my-1 border-t border-slate-100"></li>
                <li>
                    <a class="dropdown-item py-2 px-3 rounded-lg text-xs flex items-center gap-2.5 text-rose-600 hover:bg-rose-50 font-semibold" 
                       href="<?php echo base_url('logout'); ?>" 
                       onclick="return confirm('Are you sure you want to log out?');">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Sign Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
