<?php
function addProductToCart($id_product)
{
    global $db;
    $cart = null;
    $user = LoggedInUser();

    $query = $db->query("SELECT * FROM tbl_cart WHERE id_user = $user->id_user  AND status = 'pending'");
    if ($query->num_rows) {
        $cart = $query->fetch_object();
    } else {
        $query = $db->prepare("INSERT INTO tbl_cart (id_user, status) VALUES (?,'pending')");
        $query->bind_param('i', $user->id_user);
        $query->execute();
        if ($db->affected_rows) {
            $cart = $db->query("SELECT * FROM tbl_cart WHERE id_cart = $query->insert_id")->fetch_object();
        }
    }


    if ($cart) {
        $query = $db->query("SELECT * FROM tbl_cart_detail WHERE id_cart = $cart->id_cart AND id_product = $id_product");
        if ($query->num_rows) {
            return true;
        }

        $query = $db->prepare("INSERT INTO tbl_cart_detail (id_cart, id_product, qty) VALUES (?, ?, 1)");
        $query->bind_param('ii', $cart->id_cart, $id_product);
        $query->execute();
        if ($db->affected_rows) {
            return true;
        }
    }
    return null;
}

function getPendingCartProductCount()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_cart_detail INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart WHERE status = 'pending'");
    return $query->num_rows;
}

function getPendingCartDetails()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_cart_detail INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart WHERE status = 'pending'");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}
