<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaCare | Modern Pharmacy Management</title>
    <meta name="description" content="Manage medicines, stock, customers, and pharmacy operations from a modern dashboard.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            300: '#86EFAC',
                            400: '#4ADE80',
                            500: '#22C55E',
                            600: '#16A34A',
                            700: '#15803D',
                            800: '#166534',
                            900: '#14532D',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #f0fdf4 100%);
        }

        .hero-glow {
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.18), transparent 35%),
                        radial-gradient(circle at bottom right, rgba(21, 128, 61, 0.18), transparent 30%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
        }

        .stat-pill {
            background: rgba(240, 253, 244, 0.9);
            border: 1px solid rgba(187, 247, 208, 0.9);
            color: #166534;
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen">
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="<?php echo base_url(); ?>" class="flex items-center gap-3 text-decoration-none">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                        <i class="fa-solid fa-staff-snake text-xl"></i>
                    </div>
                    <div>
                        <div class="font-extrabold text-xl tracking-tight text-slate-900">
                            Pharma<span class="text-emerald-600">Care</span>
                        </div>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="#features" class="hover:text-emerald-600 transition">Features</a>
                    <a href="#solutions" class="hover:text-emerald-600 transition">Solutions</a>
                    <a href="#about" class="hover:text-emerald-600 transition">About</a>
                    <a href="#contact" class="hover:text-emerald-600 transition">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    <a href="<?php echo site_url('login'); ?>" class="hidden sm:inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-emerald-300 hover:text-emerald-700 transition">
                        Sign In
                    </a>
                    <a href="<?php echo site_url('login'); ?>" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-xl hover:shadow-emerald-600/30 transition">
                        Get Started
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-glow relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-semibold mb-6">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Pharmacy operations made simple
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                            Grow your pharmacy with a smarter, faster workflow.
                        </h1>

                        <p class="mt-6 max-w-xl text-lg text-slate-600 leading-8">
                            Track medicines, monitor stock, streamline sales, and simplify billing with a secure system built for modern healthcare businesses.
                        </p>

                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo site_url('login'); ?>" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-xl hover:shadow-emerald-600/30 transition">
                                Login to Dashboard
                                <i class="fa-solid fa-arrow-right ml-2"></i>
                            </a>
                            <a href="#features" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold hover:border-emerald-300 hover:text-emerald-700 transition">
                                Explore Features
                            </a>
                        </div>

                        <div class="mt-10 flex flex-wrap gap-4 text-sm text-slate-600">
                            <span class="stat-pill px-3 py-2 rounded-full font-medium">24/7 stock visibility</span>
                            <span class="stat-pill px-3 py-2 rounded-full font-medium">Smart inventory alerts</span>
                            <span class="stat-pill px-3 py-2 rounded-full font-medium">Secure pharmacy access</span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="glass-card rounded-[32px] p-4 shadow-xl shadow-slate-200/60">
                            <div class="rounded-[26px] bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 p-5 text-white">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <div class="text-xs uppercase tracking-[0.2em] text-emerald-200">Overview</div>
                                        <h3 class="mt-2 text-xl font-bold">Pharmacy Control Center</h3>
                                    </div>
                                    <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center">
                                        <i class="fa-solid fa-chart-line text-emerald-300"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                        <div class="text-emerald-200 text-xs uppercase tracking-wide">Total Medicines</div>
                                        <div class="mt-2 text-3xl font-extrabold">2,480</div>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                        <div class="text-emerald-200 text-xs uppercase tracking-wide">Low Stock</div>
                                        <div class="mt-2 text-3xl font-extrabold">32</div>
                                    </div>
                                </div>

                                <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm text-slate-200">Inventory health</span>
                                        <span class="text-sm font-semibold text-emerald-300">92%</span>
                                    </div>
                                    <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full w-[92%] bg-gradient-to-r from-emerald-400 to-emerald-300 rounded-full"></div>
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-3 gap-3 text-center text-xs">
                                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                                        <div class="text-emerald-200">Sales</div>
                                        <div class="mt-2 font-bold text-base">$12.4K</div>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                                        <div class="text-emerald-200">Orders</div>
                                        <div class="mt-2 font-bold text-base">120</div>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                                        <div class="text-emerald-200">Returns</div>
                                        <div class="mt-2 font-bold text-base">4</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Features</div>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Built for pharmacy teams that need clarity and speed.</h2>
                </div>

                <div class="mt-12 grid md:grid-cols-3 gap-8">
                    <div class="group bg-slate-50 rounded-3xl p-7 border border-slate-200 hover:border-emerald-200 hover:shadow-xl transition">
                        <div class="feature-icon flex items-center justify-center mb-5">
                            <i class="fa-solid fa-pills text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Medicine Inventory</h3>
                        <p class="mt-3 text-slate-600 leading-7">
                            Keep stock records updated, track expiry dates, and manage medicine availability in real time.
                        </p>
                    </div>

                    <div class="group bg-slate-50 rounded-3xl p-7 border border-slate-200 hover:border-emerald-200 hover:shadow-xl transition">
                        <div class="feature-icon flex items-center justify-center mb-5">
                            <i class="fa-solid fa-boxes-stacked text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Stock Management</h3>
                        <p class="mt-3 text-slate-600 leading-7">
                            Monitor purchase history, low stock warnings, and replenishment planning from one place.
                        </p>
                    </div>

                    <div class="group bg-slate-50 rounded-3xl p-7 border border-slate-200 hover:border-emerald-200 hover:shadow-xl transition">
                        <div class="feature-icon flex items-center justify-center mb-5">
                            <i class="fa-solid fa-user-group text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Customer & Billing</h3>
                        <p class="mt-3 text-slate-600 leading-7">
                            Manage customers, maintain billing records, and deliver a better purchase experience.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="solutions" class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Why PharmaCare</div>
                        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">Everything you need to run a reliable pharmacy operation.</h2>
                        <p class="mt-5 text-lg text-slate-600 leading-8">
                            From purchase workflows to stock alerts and sales reporting, the platform helps you stay in control while keeping your customers served quickly and accurately.
                        </p>

                        <div class="mt-8 space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-900">Real-time monitoring</h4>
                                    <p class="mt-1 text-slate-600">See stock movement and pharmacy performance as it happens.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-900">Secure access</h4>
                                    <p class="mt-1 text-slate-600">Protect your pharmacy system with authenticated user sessions.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-chart-column"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-900">Actionable insights</h4>
                                    <p class="mt-1 text-slate-600">Get better visibility into sales, inventory levels, and trends.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-[32px] p-5 shadow-xl shadow-slate-200/60">
                        <div class="rounded-[28px] border border-slate-200 bg-white p-5">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                                <div>
                                    <div class="text-xs uppercase text-slate-500 tracking-[0.18em]">Dashboard</div>
                                    <h4 class="mt-2 text-xl font-bold text-slate-900">Stock Snapshot</h4>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Live</span>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50">
                                    <span class="text-slate-600">Amoxicillin 500mg</span>
                                    <span class="font-bold text-emerald-700">142 units</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50">
                                    <span class="text-slate-600">Vitamin C Tablets</span>
                                    <span class="font-bold text-amber-600">12 low</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50">
                                    <span class="text-slate-600">Cough Syrup</span>
                                    <span class="font-bold text-rose-600">Expiring soon</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Trusted by pharmacies</div>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900">A cleaner way to manage medicine and stock decisions.</h2>
                </div>

                <div class="mt-12 grid md:grid-cols-3 gap-8 text-center">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                        <div class="text-4xl font-extrabold text-emerald-600">1.2K+</div>
                        <div class="mt-3 text-slate-600">Medicine items tracked</div>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                        <div class="text-4xl font-extrabold text-emerald-600">98%</div>
                        <div class="mt-3 text-slate-600">Inventory accuracy</div>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                        <div class="text-4xl font-extrabold text-emerald-600">24/7</div>
                        <div class="mt-3 text-slate-600">Operational visibility</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-20 bg-gradient-to-br from-emerald-600 to-emerald-800">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Ready to manage your pharmacy more efficiently?</h2>
                <p class="mt-5 text-lg text-emerald-50/90 max-w-2xl mx-auto">
                    Streamline stock management, keep medication records organized, and support better customer service with one central platform.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="<?php echo site_url('login'); ?>" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-white text-emerald-700 font-semibold shadow-lg hover:shadow-xl transition">
                        Login Now
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl border border-white/40 bg-transparent text-white font-semibold hover:bg-white/10 transition">
                        View Features
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white">
                        <i class="fa-solid fa-staff-snake"></i>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white">Pharma<span class="text-emerald-400">Care</span></div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-6 text-sm text-slate-300">
                    <a href="#features" class="hover:text-white transition">Features</a>
                    <a href="#solutions" class="hover:text-white transition">Solutions</a>
                    <a href="#about" class="hover:text-white transition">About</a>
                    <a href="#contact" class="hover:text-white transition">Contact</a>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-700 pt-6 text-sm text-slate-400 flex flex-col md:flex-row justify-between gap-4">
                <p>© <?php echo date('Y'); ?> PharmaCare. All rights reserved.</p>
                <p>Modern pharmacy management for smarter operations.</p>
            </div>
        </div>
    </footer>
</body>
</html>

