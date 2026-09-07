<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success">Edit Supplier</h3>
            <p class="text-muted mb-0">Update supplier information.</p>
        </div>

        <a href="<?= site_url('suppliers'); ?>" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form action="<?= site_url('suppliers/update/'.$supplier->id); ?>" method="POST">

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier Name</label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="<?= set_value('name', $supplier->name); ?>">

                        <small class="text-danger"><?= form_error('name'); ?></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Person</label>

                        <input type="text"
                               name="contact_person"
                               class="form-control"
                               value="<?= set_value('contact_person', $supplier->contact_person); ?>">

                        <small class="text-danger"><?= form_error('contact_person'); ?></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?= set_value('email', $supplier->email); ?>">

                        <small class="text-danger"><?= form_error('email'); ?></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="<?= set_value('phone', $supplier->phone); ?>">

                        <small class="text-danger"><?= form_error('phone'); ?></small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>

                        <textarea name="address"
                                  rows="3"
                                  class="form-control"><?= set_value('address', $supplier->address); ?></textarea>

                        <small class="text-danger"><?= form_error('address'); ?></small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>

                        <select name="status" class="form-select">

                            <option value="active"
                                <?= ($supplier->status == 'active') ? 'selected' : ''; ?>>
                                Active
                            </option>

                            <option value="inactive"
                                <?= ($supplier->status == 'inactive') ? 'selected' : ''; ?>>
                                Inactive
                            </option>

                        </select>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a href="<?= site_url('suppliers'); ?>" class="btn btn-light">
                        Cancel
                    </a>

                    <button class="btn btn-success" type="submit">
                        Update Supplier
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>