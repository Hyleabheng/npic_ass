<?php
$user = LoggedInUser();

$products = [];
$manage_products = function_exists('getRecentProducts') ? getRecentProducts(12) : getProducts();
if ($manage_products !== null) {
    while ($row = $manage_products->fetch_object()) {
        $products[] = [
            'id_product' => $row->id_product,
            'name' => $row->name,
            'price' => $row->price,
            'qty' => $row->qty ?? null,
            'short_des' => $row->short_des,
            'image' => $row->image,
        ];
    }
}
?>

<div class="container py-4 py-md-5">
    <div id="home-react"></div>

    <noscript>
        <div class="alert alert-warning mt-3">
            This page works best with JavaScript enabled.
        </div>
    </noscript>
</div>

<script>
    window.__HOME_USER__ = <?php echo json_encode([
        'loggedIn' => (bool)$user,
        'isUser' => function_exists('isUser') ? (bool)isUser() : false,
    ], JSON_UNESCAPED_SLASHES); ?>;
    window.__HOME_PRODUCTS__ = <?php echo json_encode($products, JSON_UNESCAPED_SLASHES); ?>;
</script>
<script src="assets/js/home.react.js"></script>