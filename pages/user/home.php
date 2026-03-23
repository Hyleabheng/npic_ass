<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">User List</h4>
        <a href="./?page=user/create" class="btn btn-success btn-sm">
            Add User
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle app-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:90px;">ID</th>
                            <th>User Label</th>
                            <th style="width:120px;">Level</th>
                            <th style="width:180px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $manage_users = getUsers();
                        if ($manage_users !== null) {
                            while ($row = $manage_users->fetch_object()) {
                        ?>
                                <tr>
                                    <td class="text-muted"><?php echo $row->id_user ?></td>
                                    <td class="fw-semibold"><?php echo $row->user_label ?></td>
                                    <td>
                                        <span class="badge text-bg-secondary"><?php echo $row->level ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-sm btn-outline-primary"
                                                href="./?page=user/update&id=<?php echo $row->id_user ?>">
                                                Update
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger"
                                                href="./?page=user/delete&id=<?php echo $row->id_user ?>">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>