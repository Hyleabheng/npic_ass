<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.table{
border:1px solid black;
}

.table th,
.table td{
border:1px solid black;
text-align:center;
vertical-align:middle;
}

.product-img{
width:65px;
height:65px;
object-fit:cover;
border-radius:8px;
border:1px solid #ccc;
}

.table-hover tbody tr:hover{
background:#f5f5f5;
}
</style>

<div class="container mt-5">

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="fw-bold">Product List</h4>

<a href="./?page=product/create" class="btn btn-success btn-sm">
<i class="fa fa-plus"></i> Add Product
</a>
</div>

<!-- Card -->
<div class="card shadow border-0">
<div class="card-body bg-white">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-light">
<tr>
<th>ID</th>
<th>Name</th>
<th>Slug</th>
<th>Price</th>
<th>Qty</th>
<th>Short Des</th>
<th>Image</th>
<th>Category</th>
<th width="180">Action</th>
</tr>
</thead>

<tbody>

<?php
$manage_products = getProducts();
if ($manage_products !== null) {
while ($row = $manage_products->fetch_object()) {
?>

<tr>

<td><?php echo $row->id_product ?></td>

<td class="fw-semibold">
<?php echo $row->name ?>
</td>

<td class="text-muted">
<?php echo $row->slug ?>
</td>

<td class="text-success fw-bold">
$<?php echo $row->price ?>
</td>

<td>
<?php echo $row->qty ?>
</td>

<td style="max-width:180px">
<?php echo $row->short_des ?>
</td>

<td>
<img src="<?php echo $row->image ?>" class="product-img">
</td>

<td>

<?php
$categories = getProductCategories($row->id_product);
if ($categories !== null) {
while ($category = $categories->fetch_object()) {
echo '<span class="badge bg-primary me-1">'.$category->name.'</span>';
}
} else {
echo '<span class="text-muted">None</span>';
}
?>

</td>

<td>

<div class="d-flex justify-content-center gap-2">

<a class="btn btn-sm btn-outline-primary"
href="./?page=product/update&id=<?php echo $row->id_product ?>">
<i class="fa fa-pen"></i> Update
</a>

<a class="btn btn-sm btn-outline-danger"
href="./?page=product/delete&id=<?php echo $row->id_product ?>">
<i class="fa fa-trash"></i> Delete
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