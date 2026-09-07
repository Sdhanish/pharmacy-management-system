<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-1">Add New Supplier</h3>
            <p class="text-muted mb-0">Register a new medicine supplier.</p>
        </div>

        <a href="<?= site_url('suppliers'); ?>" class="btn btn-outline-secondary">
            Back to Suppliers
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form action="<?= site_url('suppliers/store'); ?>" method="POST">

                <div class="row g-4">

                    <!-- Supplier Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier Name</label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Enter supplier/company name"
                               value="<?= set_value('name'); ?>">

                        <small class="text-danger"><?= form_error('name'); ?></small>
                    </div>

                    <!-- Contact Person -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Person</label>

                        <input type="text"
                               name="contact_person"
                               class="form-control"
                               placeholder="Enter contact person"
                               value="<?= set_value('contact_person'); ?>">

                        <small class="text-danger"><?= form_error('contact_person'); ?></small>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address</label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="supplier@example.com"
                               value="<?= set_value('email'); ?>">

                        <small class="text-danger"><?= form_error('email'); ?></small>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number</label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               placeholder="+91 XXXXX XXXXX"
                               value="<?= set_value('phone'); ?>">

                        <small class="text-danger"><?= form_error('phone'); ?></small>
                    </div>

                    <!-- Address -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>

                        <textarea name="address"
                                  rows="3"
                                  class="form-control"
                                  placeholder="Enter supplier address"><?= set_value('address'); ?></textarea>

                        <small class="text-danger"><?= form_error('address'); ?></small>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>

                        <select name="status" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a href="<?= site_url('suppliers'); ?>" class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-success">
                        Save Supplier
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>