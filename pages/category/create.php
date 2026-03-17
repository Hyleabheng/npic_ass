<?php

$name_err = $slug_err = '';
if (isset($_POST['name']) && isset($_POST['slug'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    if (empty($name)) {
        $name_err = 'Name is required';
    } else {
        if (categoryNameExists($name)) {
            $name_err = 'Name already exists';
        }
    }
    if (empty($slug)) {
        $slug_err = 'Slug is required';
    } else {
        if (categorySlugExists($slug)) {
            $slug_err = 'Slug already exists';
        }
    }

    if (empty($name_err) && empty($slug_err)) {
        if (createCategory($name, $slug)) {
            echo '<div class="alert alert-success" role="alert">
            Category Created Successfully. <a href="./?page=category/home">Category page</a>
            </div>';
            $name_err = $slug_err = '';
            unset($_POST['name']);
            unset($_POST['slug']);
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Category Created Failed
            </div>';
        }
    }
}
?>
<form action="./?page=category/create" method="post" class="w-50 mx-auto">
    <h1>Create Cetegory</h1>
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control <?php echo $name_err !== '' ?  'is-invalid' : ' ' ?>" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">
        <div class="invalid-feedback">
            <?php echo $name_err ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control <?php echo $slug_err !== '' ?  'is-invalid' : ' ' ?>" value="<?php echo isset($_POST['slug']) ? $_POST['slug'] : '' ?>">
        <div class="invalid-feedback">
            <?php echo $slug_err ?>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a role="button" href="./?page=category/home" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Create</button>
    </div>
</form>