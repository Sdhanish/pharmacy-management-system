<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Master Layout
 * Loads Header, Sidebar, Topbar, Dynamic Content and Footer.
 * Passes all available view variables to child layout files.
 */

// Pass all variables received from MY_Controller to child views.
$layout_data = get_defined_vars();

// Header
$this->load->view('layouts/header', $layout_data);

// Sidebar
$this->load->view('layouts/sidebar', $layout_data);
?>

<!-- Main Content Wrapper -->
<div id="main-wrapper" class="flex-1 flex flex-col bg-slate-50 min-h-screen">

    <!-- Top Navigation -->
    <?php $this->load->view('layouts/topbar', $layout_data); ?>

    <!-- Main Page Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto w-full">

            <!-- Success Flash Message -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-2xl mb-6 flex items-center gap-3" role="alert">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <div class="flex-1">
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Error Flash Message -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-2xl mb-6 flex items-center gap-3" role="alert">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>

                    <div class="flex-1">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Dynamic Content View -->
            <?php
            if (!empty($content_view)) {
                $this->load->view($content_view, $layout_data);
            } else {
                echo '<div class="alert alert-warning rounded-2xl">No content view specified.</div>';
            }
            ?>

        </div>
    </main>

    <!-- Footer -->
    <?php $this->load->view('layouts/footer', $layout_data); ?>

</div>