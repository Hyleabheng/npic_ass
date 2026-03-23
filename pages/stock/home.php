<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Stock List</h4>
        <a href="./?page=stock/create" class="btn btn-success btn-sm">
            Add Stock
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle app-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:90px;">ID</th>
                            <th>Product</th>
                            <th style="width:120px;">Qty</th>
                            <th style="width:160px;">Date</th>
                            <th style="width:180px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $manage_stocks = getStocks();
                        if ($manage_stocks !== null) {
                            while ($row = $manage_stocks->fetch_object()) {
                                $product = getProductByID($row->id_product);
                        ?>
                                <tr>
                                    <td class="text-muted"><?php echo $row->id_stock ?></td>
                                    <td class="fw-semibold"><?php echo $product ? $product->name : 'Unknown' ?></td>
                                    <td>
                                        <span class="badge text-bg-primary"><?php echo $row->qty ?></span>
                                    </td>
                                    <td class="text-muted"><?php echo $row->date ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-sm btn-outline-primary"
                                                href="./?page=stock/update&id=<?php echo $row->id_stock ?>">
                                                Update
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger"
                                                href="./?page=stock/delete&id=<?php echo $row->id_stock ?>">
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