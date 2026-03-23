<?php
if (!isset($_GET['id']) || getCategoryByID($_GET['id']) === null) {
    header('Location: ./?page=category/home');
    exit;
}

$manage_category = getCategoryByID($_GET['id']);

$name_err = $slug_err = '';
if (isset($_POST['name']) && isset($_POST['slug'])) {
    $id_category = $_GET['id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    if (empty($name)) {
        $name_err = 'Name is required';
    } else {
        if ($name !== $manage_category->name && categoryNameExists($name)) {
            $name_err = 'Slug already exists';
        }
    }

    if (empty($slug)) {
        $slug_err = 'Slug is required';
    } else {
        if ($slug !== $manage_category->slug && categorySlugExists($slug)) {
            $slug_err = 'Slug already exists';
        }
    }

    if (empty($name_err) && empty($slug_err)) {
        $manage_category = updateCategory($id_category, $name, $slug);
        if ($manage_category !== false) {
            header('Location: ./?page=category/home');
            exit;
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Category Update Failed
            </div>';
        }
    }
}

?>
<form action="./?page=category/update&id=<?php echo $_GET['id'] ?>" method="post" class="container py-5"
    style="max-width:600px;">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0">Update Category</h4>
        </div>

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Name</label>
                <input id="name" type="text" name="name" class="form-control <?php echo $name_err ? 'is-invalid' : '' ?>"
                    placeholder="Enter category name"
                    value="<?php echo $_POST['name'] ?? $manage_category->name ?>">
                <div class="invalid-feedback"><?php echo $name_err ?></div>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label fw-bold">Slug</label>
                <input id="slug" type="text" name="slug" class="form-control <?php echo $slug_err ? 'is-invalid' : '' ?>"
                    placeholder="category-slug"
                    value="<?php echo $_POST['slug'] ?? $manage_category->slug ?>">
                <div class="invalid-feedback"><?php echo $slug_err ?></div>
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between">
            <a role="button" href="./?page=category/home" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                Update Category
            </button>
        </div>

    </div>

</form>
