<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <h3>Cart List</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Action</th>
                </tr>
                <?php
                $manage_cart_details = getPendingCartDetails();
                if ($manage_cart_details !== null) {
                    while ($row = $manage_cart_details->fetch_object()) {

                        $product = getProductByID($row->id_product)
                ?>
                        <tr>
                            <td><?php echo $product->name ?></td>
                            <td><?php echo $product->price  ?>$</td>
                            <td>
                                <input class="w-50 form-control" type="number" value="1">
                            </td>
                            <td>
                                <a class="btn btn-danger" href="./?page=cart/delete&id=<?php echo $row->id_cart_detail ?>">delete</a>
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