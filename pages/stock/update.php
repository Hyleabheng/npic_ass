<?php

$id = $_GET['id'] ?? null;
$stock = null;

if (!$id || !is_numeric($id) || ($stock = getStockByID((int)$id)) === null) {
    header('Location: ./?page=stock/home');
    exit();
}

$qty_err = $date_err = '';

// Prefill defaults (GET load)
if (!isset($_POST['qty']) && !isset($_POST['date']) && !isset($_POST['id_product'])) {
    $_POST['id_product'] = $stock->id_product;
    $_POST['qty'] = $stock->qty;
    $_POST['date'] = $stock->date;
}

if (isset($_POST['id_product']) && isset($_POST['qty']) && isset($_POST['date'])) {
    $id_product = (int)$_POST['id_product'];
    $qty = $_POST['qty'];
    $date = $_POST['date'];

    if ($qty === '' || $qty === null) {
        $qty_err = 'Qty is required';
    } else if (!is_numeric($qty)) {
        $qty_err = 'Qty must be a number';
    } else if ((int)$qty < 0) {
        $qty_err = 'Qty must not be lower than zero';
    } else {
        $qty = (int)$qty;
    }

    if (empty($date)) {
        $date_err = 'Date is required';
    }

    if (empty($qty_err) && empty($date_err)) {
        $updated = updateStock((int)$id, $id_product, $qty, $date);
        if ($updated) {
            header('Location: ./?page=stock/home');
            exit;
        } else {
            echo '<div class="container py-3" style="max-width:700px;">
                <div class="alert alert-danger" role="alert">
                    Stock update failed.
                </div>
            </div>';
        }
    }
}

?>

<form action="./?page=stock/update&id=<?php echo (int)$id ?>" method="post" class="container py-5" style="max-width:600px;">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0">Update Stock</h4>
        </div>

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="id_product" class="form-label fw-bold">Product</label>
                <select id="id_product" name="id_product" class="form-select">
                    <?php
                    $products = getProducts();
                    $selected_product_id = $_POST['id_product'] ?? $stock->id_product;
                    if ($products !== null) {
                        while ($row = $products->fetch_object()) {
                            $selected = ((string)$selected_product_id === (string)$row->id_product) ? 'selected' : '';
                    ?>
                            <option value="<?php echo $row->id_product ?>" <?php echo $selected ?>>
                                <?php echo $row->name ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="qty" class="form-label fw-bold">Qty</label>
                <input id="qty" type="number" name="qty" class="form-control <?php echo $qty_err ? 'is-invalid' : '' ?>"
                    placeholder="Enter quantity"
                    value="<?php echo $_POST['qty'] ?? $stock->qty ?>">
                <div class="invalid-feedback"><?php echo $qty_err ?></div>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label fw-bold">Date</label>
                <input id="date" type="date" name="date" class="form-control <?php echo $date_err ? 'is-invalid' : '' ?>"
                    value="<?php echo $_POST['date'] ?? $stock->date ?>">
                <div class="invalid-feedback"><?php echo $date_err ?></div>
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between">
            <a role="button" href="./?page=stock/home" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                Update Stock
            </button>
        </div>

    </div>

</form>
