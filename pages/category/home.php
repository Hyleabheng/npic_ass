<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Category List</h4>
        <a href="./?page=category/create" class="btn btn-success btn-sm">
            Add Category
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle app-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:90px;">ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th style="width:180px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $manage_categories = getCategories();
                        if ($manage_categories !== null) {
                            while ($row = $manage_categories->fetch_object()) {
                        ?>
                                <tr>
                                    <td class="text-muted"><?php echo $row->id_category ?></td>
                                    <td class="fw-semibold"><?php echo $row->name ?></td>
                                    <td class="text-muted"><?php echo $row->slug ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-sm btn-outline-primary"
                                                href="./?page=category/update&id=<?php echo $row->id_category ?>">
                                                Update
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger"
                                                href="./?page=category/delete&id=<?php echo $row->id_category ?>">
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