<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

    <!-- Admin Footer -->
    <footer class="mt-auto py-4 px-4 lg:px-8 border-t border-slate-200 bg-white text-xs text-slate-500">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-700">Pharma<span class="text-emerald-600">Care</span></span>
                <span>&copy; <?php echo date('Y'); ?> All rights reserved.</span>
                <span class="text-slate-300">|</span>
                <span class="badge bg-slate-100 text-slate-600 border border-slate-200 font-mono text-[10px]">v2.0.0</span>
            </div>
            
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Database: Connected</span>
                </span>
                <a href="<?php echo base_url('reports'); ?>" class="text-slate-500 hover:text-emerald-600 transition-colors text-decoration-none">Documentation</a>
                <a href="mailto:support@pharmacare.com" class="text-slate-500 hover:text-emerald-600 transition-colors text-decoration-none">Help & Support</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom Main JS -->
    <script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
</body>
</html>
