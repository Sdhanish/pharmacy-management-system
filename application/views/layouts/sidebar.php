<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$current_menu = isset($active_menu) ? $active_menu : $this->uri->segment(1, 'dashboard');
?>

<!-- Mobile Sidebar Overlay Backdrop -->
<div id="sidebar-backdrop"></div>

<!-- Admin Sidebar Navigation -->
<aside id="sidebar" class="bg-white border-r border-slate-200 flex flex-col justify-between shadow-sm">
    <!-- Brand Header -->
    <div>
        <div class="h-[70px] flex items-center justify-between px-5 border-b border-slate-100 bg-white">
            <a href="<?php echo base_url('dashboard'); ?>" class="flex items-center gap-3 text-decoration-none group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-staff-snake text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-lg text-slate-900 tracking-tight">Pharma<span class="text-emerald-600">Care</span></span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">PRO</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium mb-0 leading-none">Management v2.0</p>
                </div>
            </a>
            
            <!-- Mobile Close Button -->
            <button id="sidebar-close" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none" aria-label="Close Sidebar">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="px-3 py-4 space-y-1 overflow-y-auto max-h-[calc(100vh-190px)]">
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Main Menu
            </div>

            <!-- 1. Dashboard -->
            <a href="<?php echo base_url('dashboard'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
                <span>Dashboard</span>
            </a>

            <!-- 2. Medicines -->
            <a href="<?php echo base_url('medicines'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'medicines') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-pills"></i>
                </span>
                <span>Medicines</span>
            </a>

            <!-- 3. Categories -->
            <a href="<?php echo base_url('categories'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'categories') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-tags"></i>
                </span>
                <span>Categories</span>
            </a>

            <div class="px-3 pt-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Inventory & Stock
            </div>

            <!-- 4. Stock Management -->
            <a href="<?php echo base_url('stock'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'stock' || $current_menu === 'stock-management') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </span>
                <span>Stock Management</span>
            </a>

            <!-- 5. Stock History -->
            <a href="<?php echo base_url('stock-history'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'stock_history' || $current_menu === 'stock-history') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
                <span>Stock History</span>
            </a>

            <!-- 6. Customers -->
            <a href="<?php echo base_url('customers'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'customers') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-users"></i>
                </span>
                <span>Customers</span>
            </a>

            <div class="px-3 pt-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Analytics & System
            </div>

            <!-- 7. Reports -->
            <a href="<?php echo base_url('reports'); ?>" 
               class="nav-link-custom <?php echo ($current_menu === 'reports') ? 'active' : ''; ?>">
                <span class="nav-icon w-5 text-center text-base">
                    <i class="fa-solid fa-chart-line"></i>
                </span>
                <span>Reports</span>
            </a>
        </div>
    </div>

    <!-- Bottom Actions / User & Logout -->
    <div class="p-3 border-t border-slate-100 bg-slate-50/70">
        <!-- 7. Logout -->
        <a href="<?php echo base_url('logout'); ?>" 
           class="nav-link-custom nav-link-logout flex items-center justify-between"
           onclick="return confirm('Are you sure you want to log out from PharmaCare?');">
            <div class="flex items-center gap-3">
                <span class="w-5 text-center text-base">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </span>
                <span class="font-semibold">Logout</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs opacity-60"></i>
        </a>
    </div>
</aside>
