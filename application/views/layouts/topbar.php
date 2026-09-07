<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$user = isset($current_user) ? $current_user : array(
    'name' => 'Administrator',
    'role' => 'Admin',
    'avatar' => ''
);
$inventory_notifications = isset($inventory_notifications) && is_array($inventory_notifications) ? $inventory_notifications : array();
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

    <!-- Right Section: Status, Notifications & User Dropdown -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Live System Status Badge -->
        <div class="hidden xl:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-800">
            <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-emerald"></span>
            <span>Pharmacy Online</span>
        </div>

        <!-- Quick Action: New Customer Sale (POS) -->
        <a href="<?php echo base_url('sales/create'); ?>" 
           class="btn btn-emerald btn-sm rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-1.5 shadow-sm hover:shadow transition text-decoration-none" 
           title="Record Customer Purchase">
            <i class="fa-solid fa-cart-flatbed"></i>
            <span class="hidden md:inline">+ New Sale</span>
        </a>

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
                    <span class="badge bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full"><?php echo count($inventory_notifications); ?> New</span>
                </li>
                <?php if (empty($inventory_notifications)): ?>
                    <li class="px-3 py-4 text-center text-[11px] text-slate-500">No inventory alerts</li>
                <?php else: ?>
                    <?php foreach ($inventory_notifications as $notification): ?>
                        <?php $is_out_of_stock = $notification['alert_type'] === 'out_of_stock'; ?>
                        <li>
                            <a class="dropdown-item py-2.5 px-3 rounded-xl flex items-start gap-3 hover:bg-slate-50" href="<?php echo base_url($is_out_of_stock ? 'stock' : 'expiry/expired'); ?>">
                                <div class="w-8 h-8 rounded-lg <?php echo $is_out_of_stock ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600'; ?> flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid <?php echo $is_out_of_stock ? 'fa-triangle-exclamation' : 'fa-calendar-xmark'; ?> text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 mb-0.5"><?php echo $is_out_of_stock ? 'Out of Stock' : 'Expired Medicine'; ?></p>
                                    <p class="text-[11px] text-slate-500 mb-0 truncate"><?php echo html_escape($notification['medicine_name']); ?><?php echo $is_out_of_stock ? ' has no units remaining.' : ' expired on ' . date('M d, Y', strtotime($notification['expiry_date'])) . '.'; ?></p>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li class="pt-2 border-t border-slate-100 text-center">
                    <a href="<?php echo base_url('expiry'); ?>" class="text-[11px] text-emerald-600 font-semibold hover:underline">View All Alerts</a>
                </li>
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none" 
                    type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-sm text-sm font-bold" aria-hidden="true">
                    A
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
                    <a class="dropdown-item py-2 px-3 rounded-lg text-xs flex items-center gap-2.5 text-slate-700 hover:bg-slate-50 mt-1" href="<?php echo base_url('profile'); ?>">
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
