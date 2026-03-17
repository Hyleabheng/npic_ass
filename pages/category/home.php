<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <h3>Category List</h3>
        <div><a href="./?page=category/create" class="btn btn-success">Add Category</a></div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Action</th>
                </tr>
                <?php
                $manage_categories = getCategories();
                if ($manage_categories !== null) {
                    while ($row = $manage_categories->fetch_object()) {
                ?>
                        <tr>
                            <td><?php echo $row->id_category ?></td>
                            <td><?php echo $row->name ?></td>
                            <td><?php echo $row->slug ?></td>
                            <td>
                                <a class="btn btn-primary" href="./?page=category/update&id=<?php echo $row->id_category ?>">update</a>
                                <a class="btn btn-danger" href="./?page=category/delete&id=<?php echo $row->id_category ?>">delete</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>