<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Header Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-1">Reports & Analytics</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-0">Generate inventory audits, sales trends, fast-moving items, and expiry loss reports.</p>
    </div>
    <div class="flex items-center gap-2.5">
        <button class="btn btn-emerald text-xs sm:text-sm font-medium rounded-xl px-4 py-2.5 flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Generate Report</span>
        </button>
    </div>
</div>

<!-- Report Cards Grid Preview -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    <div class="app-card app-card-hover p-5">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
            <i class="fa-solid fa-chart-column text-lg"></i>
        </div>
        <h4 class="text-base font-bold text-slate-800 mb-1">Sales & Revenue Report</h4>
        <p class="text-xs text-slate-400 mb-4">Daily, weekly, and monthly sales breakdowns and profit margins.</p>
        <button class="btn btn-outline-success btn-sm rounded-lg text-xs font-semibold w-full">View Report</button>
    </div>

    <div class="app-card app-card-hover p-5">
        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
            <i class="fa-solid fa-calendar-xmark text-lg"></i>
        </div>
        <h4 class="text-base font-bold text-slate-800 mb-1">Expiry & Low Stock Audit</h4>
        <p class="text-xs text-slate-400 mb-4">Identify stock approaching expiration within 30, 60, or 90 days.</p>
        <button class="btn btn-outline-warning btn-sm rounded-lg text-xs font-semibold w-full">View Report</button>
    </div>

    <div class="app-card app-card-hover p-5">
        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3">
            <i class="fa-solid fa-cubes text-lg"></i>
        </div>
        <h4 class="text-base font-bold text-slate-800 mb-1">Stock Movement Velocity</h4>
        <p class="text-xs text-slate-400 mb-4">Analyze fastest moving medicines and reorder point forecasts.</p>
        <button class="btn btn-outline-primary btn-sm rounded-lg text-xs font-semibold w-full">View Report</button>
    </div>
</div>
