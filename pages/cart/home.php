<?php
$user = LoggedInUser();
$manage_cart_details = null;
if ($user && function_exists('getPendingCartDetailsForUser')) {
    $manage_cart_details = getPendingCartDetailsForUser((int)$user->id_user, 100);
} else if (function_exists('getPendingCartDetails')) {
    $manage_cart_details = getPendingCartDetails();
}

$items = [];
$subtotal = 0.0;
if ($manage_cart_details !== null) {
    while ($row = $manage_cart_details->fetch_object()) {
        $product = getProductByID($row->id_product);
        $price = (float)($product ? $product->price : 0);
        $qty = (int)($row->qty ?? 1);
        if ($qty < 1) $qty = 1;

        $items[] = [
            'row' => $row,
            'product' => $product,
            'price' => $price,
            'qty' => $qty,
            'line_total' => $price * $qty,
        ];
        $subtotal += $price * $qty;
    }
}
?>

<div class="container py-4 py-md-5">
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
        <div>
            <div class="text-muted small">Cart</div>
            <h4 class="fw-bold mb-0">Your cart</h4>
        </div>
        <a href="./" class="btn btn-outline-secondary btn-sm rounded-3">Continue shopping</a>
    </div>

    <?php if (!$items) { ?>
        <div class="app-card p-4 p-md-5 text-center">
            <div class="mx-auto cart-empty-icon mb-3">🛒</div>
            <div class="fw-bold fs-5">Your cart is empty</div>
            <div class="text-muted mt-1">Browse products and add items to your cart.</div>
            <div class="d-flex justify-content-center gap-2 mt-4">
                <a class="btn btn-primary rounded-3 px-4" href="./?page=product/home">Shop now</a>
                <a class="btn btn-light rounded-3 px-4" href="./">Back to home</a>
            </div>
        </div>
    <?php } else { ?>
        <div class="row g-3 g-lg-4">
            <div class="col-lg-8">
                <div class="app-card">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end" style="width:140px;">Price</th>
                                    <th class="text-center" style="width:120px;">Qty</th>
                                    <th class="text-end" style="width:140px;">Total</th>
                                    <th class="text-end" style="width:120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $it) {
                                    $row = $it['row'];
                                    $product = $it['product'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img class="cart-item-img" src="<?php echo $product ? $product->image : 'assets/images/slide-1.svg' ?>" alt="Product">
                                                <div class="min-w-0">
                                                    <div class="fw-semibold text-truncate" style="max-width: 380px;">
                                                        <?php echo $product ? $product->name : 'Unknown product' ?>
                                                    </div>
                                                    <div class="text-muted small text-truncate" style="max-width: 380px;">
                                                        <?php echo $product ? ($product->short_des ?? '') : '' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end fw-semibold">$<?php echo number_format((float)$it['price'], 2) ?></td>
                                        <td class="text-center">
                                            <span class="badge text-bg-light border text-dark px-3 py-2 rounded-pill"><?php echo (int)$it['qty'] ?></span>
                                        </td>
                                        <td class="text-end fw-bold">$<?php echo number_format((float)$it['line_total'], 2) ?></td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-danger rounded-3"
                                                href="./?page=cart/delete&id=<?php echo $row->id_cart_detail ?>">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="app-card p-3 p-md-4 cart-summary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">Order summary</div>
                        <span class="badge text-bg-primary rounded-pill"><?php echo count($items) ?> items</span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">Subtotal</div>
                        <div class="fw-semibold">$<?php echo number_format((float)$subtotal, 2) ?></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="text-muted">Tax</div>
                        <div class="fw-semibold">$0.00</div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="text-muted">Shipping</div>
                        <div class="fw-semibold">$0.00</div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold">Total</div>
                        <div class="fw-bold fs-5">$<?php echo number_format((float)$subtotal, 2) ?></div>
                    </div>
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary rounded-3" type="button" disabled>Checkout</button>
                        <div class="small text-muted mt-2 text-center">Checkout UI coming soon.</div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>