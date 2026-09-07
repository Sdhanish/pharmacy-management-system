<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success">Supplier Details</h3>
            <p class="text-muted mb-0">View complete supplier information.</p>
        </div>

        <a href="<?= site_url('suppliers'); ?>" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-bordered align-middle">

                <tr>
                    <th width="220">Supplier Name</th>
                    <td><?= htmlspecialchars($supplier->name); ?></td>
                </tr>

                <tr>
                    <th>Contact Person</th>
                    <td><?= htmlspecialchars($supplier->contact_person); ?></td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($supplier->email); ?></td>
                </tr>

                <tr>
                    <th>Phone</th>
                    <td><?= htmlspecialchars($supplier->phone); ?></td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td><?= nl2br(htmlspecialchars($supplier->address)); ?></td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <?php if ($supplier->status == 'active') : ?>
                            <span class="badge bg-success">Active</span>
                        <?php else : ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td><?= date('d M Y, h:i A', strtotime($supplier->created_at)); ?></td>
                </tr>

                <tr>
                    <th>Last Updated</th>
                    <td><?= date('d M Y, h:i A', strtotime($supplier->updated_at)); ?></td>
                </tr>

            </table>

        </div>
    </div>

</div>