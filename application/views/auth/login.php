<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In &mdash; PharmaCare Pharmacy Management</title>
    <meta name="description" content="Sign in to your PharmaCare account to manage medicines, stock inventory, and sales.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Tailwind CSS (Play CDN with Emerald Palette) -->
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
                            600: '#16A34A', // Primary Emerald Theme
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

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen flex flex-col justify-between">

    <!-- Top Minimal Navigation -->
    <header class="py-4 px-6 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="<?php echo base_url('login'); ?>" class="flex items-center gap-2.5 text-decoration-none">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white shadow-md shadow-emerald-600/20">
                    <i class="fa-solid fa-staff-snake text-lg"></i>
                </div>
                <div>
                    <span class="font-bold text-lg text-slate-900 tracking-tight">Pharma<span class="text-emerald-600">Care</span></span>
                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-semibold bg-emerald-100 text-emerald-800 ml-1">v2.0</span>
                </div>
            </a>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-emerald"></span>
                <span class="hidden sm:inline font-medium">Pharmacy System Secure Portal</span>
            </div>
        </div>
    </header>

    <!-- Main Login Content -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        <!-- Decorative Ambient Background Blobs -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-300/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Login Card -->
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-9 shadow-xl shadow-slate-200/50">
                <!-- Card Header -->
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i class="fa-solid fa-user-lock text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">Welcome Back</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mb-0">Sign in to access your pharmacy workspace</p>
                </div>

                <!-- Alert Messages -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-5 p-3 flex items-start gap-2.5" role="alert">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0"></i>
                        <div class="flex-1">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-emerald-200 bg-emerald-50 text-emerald-800 mb-5 p-3 flex items-start gap-2.5" role="alert">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 shrink-0"></i>
                        <div class="flex-1">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-xs sm:text-sm rounded-2xl border-rose-200 bg-rose-50 text-rose-800 mb-5 p-3" role="alert">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Please correct the errors below:</span>
                        </div>
                        <?php echo validation_errors('<p class="mb-0 text-xs">- ', '</p>'); ?>
                        <button type="button" class="btn-close text-xs" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form with Bootstrap Validation -->
                <form action="<?php echo site_url('login'); ?>" method="POST" class="needs-validation space-y-4" novalidate id="loginForm">
                    <!-- CSRF Token if enabled -->
                    <?php if ($this->config->item('csrf_protection')): ?>
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <?php endif; ?>

                    <!-- Username / Email Field -->
                    <div>
                        <label for="identity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Username or Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </span>
                            <input type="text" 
                                   name="identity" 
                                   id="identity" 
                                   value="<?php echo set_value('identity', 'admin@pharmacare.com'); ?>" 
                                   class="form-control form-control-lg pl-10 pr-4 text-sm rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 <?php echo form_error('identity') ? 'is-invalid' : ''; ?>" 
                                   placeholder="e.g. admin@pharmacare.com" 
                                   required 
                                   autocomplete="username">
                            <div class="invalid-feedback text-xs">
                                Please enter your username or email address.
                            </div>
                        </div>
                    </div>

                    <!-- Password Field with Toggle Visibility -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-0">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <a href="javascript:void(0)" onclick="alert('For demo purposes, please use password: admin123');" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 text-decoration-none">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   value="<?php echo set_value('password', 'admin123'); ?>"
                                   class="form-control form-control-lg pl-10 pr-11 text-sm rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 <?php echo form_error('password') ? 'is-invalid' : ''; ?>" 
                                   placeholder="Enter your secure password" 
                                   required 
                                   autocomplete="current-password">
                            <button type="button" id="togglePasswordBtn" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                            </button>
                            <div class="invalid-feedback text-xs">
                                Please enter your password.
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <div class="form-check">
                            <input class="form-check-input text-emerald-600 focus:ring-emerald-500 rounded" type="checkbox" name="remember" id="remember" checked>
                            <label class="form-check-label text-xs font-medium text-slate-600" for="remember">
                                Keep me signed in
                            </label>
                        </div>
                    </div>

                    <!-- Sign In Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-sm shadow-md shadow-emerald-600/30 hover:shadow-lg hover:shadow-emerald-600/40 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Sign In to Dashboard</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>

                <!-- Demo Quick Fill Badge -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-400 mb-2 font-medium">Demo Testing Credentials:</p>
                    <button type="button" onclick="fillDemoCredentials('admin@pharmacare.com', 'admin123')" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-mono font-semibold transition">
                        <i class="fa-solid fa-key text-emerald-600"></i>
                        <span>admin@pharmacare.com / admin123</span>
                    </button>
                </div>
            </div>

            <!-- Security Footer Note -->
            <div class="text-center mt-6 text-xs text-slate-400">
                <p class="mb-0 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                    <span>256-Bit Encrypted Session Authentication</span>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-3 px-6 text-center text-xs text-slate-400 bg-white/60 border-t border-slate-200/60">
        PharmaCare Management System &copy; <?php echo date('Y'); ?> &bull; Version 2.0.0
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Client-side Validation & Interactions -->
    <script>
        // Bootstrap 5 client-side form validation
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Password visibility toggle
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                if (type === 'text') {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        }

        // Quick demo filler
        function fillDemoCredentials(identity, password) {
            document.getElementById('identity').value = identity;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
