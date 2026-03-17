<?php

$name_err = $slug_err = $price_err = $short_des_err = $long_des_err = $image_err = '';
if (isset($_POST['name']) && isset($_POST['slug']) && isset($_POST['price']) && isset($_POST['short_des']) && isset($_POST['long_des']) && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $price = $_POST['price'];
    $short_des = $_POST['short_des'];
    $long_des = $_POST['long_des'];
    $image = $_FILES['image'];
    $id_categories = isset($_POST['id_categories']) ? $_POST['id_categories'] : [];


    if (empty($name)) {
        $name_err = 'Name is required';
    } else {
        if (productNameExists($name)) {
            $name_err = 'Name already exists';
        }
    }
    if (empty($slug)) {
        $slug_err = 'Slug is required';
    } else {
        if (productSlugExists($slug)) {
            $slug_err = 'Slug already exists';
        }
    }
    if (empty($price)) {
        $price_err = 'Price is required';
    } else {
        if ($price < 0) {
            $price_err = 'Price must not be lower than zero';
        }
    }
    if (empty($short_des)) {
        $short_des_err = 'Short description is required';
    }
    if (empty($long_des)) {
        $long_des_err = 'Long description is required';
    }

    if (empty($name_err) && empty($slug_err) && empty($price_err) && empty($short_des_err) && empty($long_des_err)) {
        try {
            if (createProduct($name, $slug, $price, $short_des, $long_des, $image, $id_categories)) {
                echo '<div class="alert alert-success" role="alert">
                Product Created Successfully. <a href="./?page=product/home">Product page</a>
                </div>';
                $name_err = $slug_err = $price_err = $short_des_err = $long_des_err = '';
                unset($_POST['name']);
                unset($_POST['slug']);
                unset($_POST['price']);
                unset($_POST['short_des']);
                unset($_POST['long_des']);
                unset($_POST['id_categories']);
            } else {
                echo '<div class="alert alert-danger" role="alert">
                    Product Created Failed
                    </div>';
            }
        } catch (Exception $th) {
            $image_err = $th->getMessage();
        }
    }
}


?>
<form action="./?page=product/create" method="post" enctype="multipart/form-data"
class="container py-5" style="max-width:600px;">

<div class="card shadow-lg border-0 rounded-4">

<!-- Header -->
<div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
<h4 class="mb-0">Create New Product</h4>
</div>

<div class="card-body p-4">

<!-- Name -->
<div class="mb-3">
<label class="form-label fw-bold">Product Name</label>
<input type="text" name="name"
class="form-control <?php echo $name_err ? 'is-invalid':'' ?>"
placeholder="Enter product name"
value="<?php echo $_POST['name'] ?? '' ?>">
<div class="invalid-feedback"><?php echo $name_err ?></div>
</div>

<!-- Slug -->
<div class="mb-3">
<label class="form-label fw-bold">Slug</label>
<input type="text" name="slug"
class="form-control <?php echo $slug_err ? 'is-invalid':'' ?>"
placeholder="product-slug"
value="<?php echo $_POST['slug'] ?? '' ?>">
<div class="invalid-feedback"><?php echo $slug_err ?></div>
</div>

<!-- Price -->
<div class="mb-3">
<label class="form-label fw-bold">Price ($)</label>
<input type="number" name="price"
class="form-control <?php echo $price_err ? 'is-invalid':'' ?>"
placeholder="Enter price"
value="<?php echo $_POST['price'] ?? '' ?>">
<div class="invalid-feedback"><?php echo $price_err ?></div>
</div>

<!-- Short Description -->
<div class="mb-3">
<label class="form-label fw-bold">Short Description</label>
<textarea name="short_des" rows="2"
class="form-control <?php echo $short_des_err ? 'is-invalid':'' ?>"
placeholder="Short description"><?php echo $_POST['short_des'] ?? '' ?></textarea>
<div class="invalid-feedback"><?php echo $short_des_err ?></div>
</div>

<!-- Long Description -->
<div class="mb-3">
<label class="form-label fw-bold">Long Description</label>
<textarea name="long_des" rows="4"
class="form-control <?php echo $long_des_err ? 'is-invalid':'' ?>"
placeholder="Full product description"><?php echo $_POST['long_des'] ?? '' ?></textarea>
<div class="invalid-feedback"><?php echo $long_des_err ?></div>
</div>

<!-- Image -->
<div class="mb-3">
<label class="form-label fw-bold">Product Image</label>
<input type="file" name="image"
class="form-control <?php echo $image_err ? 'is-invalid':'' ?>">
<div class="invalid-feedback"><?php echo $image_err ?></div>
</div>

<!-- Categories -->
<div class="mb-3">
<label class="form-label fw-bold">Categories</label>

<div class="border rounded p-3 bg-light" style="max-height:140px; overflow-y:auto;">

<?php
$manage_categories = getCategories();
if ($manage_categories !== null) {
while ($row = $manage_categories->fetch_object()) {

$checked = isset($_POST['id_categories']) && in_array($row->id_category,$_POST['id_categories']) ? 'checked' : '';
?>

<div class="form-check">
<input class="form-check-input"
type="checkbox"
name="id_categories[]"
value="<?php echo $row->id_category ?>"
<?php echo $checked ?>>

<label class="form-check-label">
<?php echo $row->name ?>
</label>
</div>

<?php }} ?>

</div>
</div>

</div>

<!-- Footer -->
<div class="card-footer bg-white d-flex justify-content-between">

<a href="./?page=product/home" class="btn btn-outline-secondary">
Cancel
</a>

<button type="submit" class="btn btn-primary">
Create Product
</button>

</div>

</div>

</form>