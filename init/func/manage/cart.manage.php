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

function getPendingCartProductCountForUser($id_user)
{
    global $db;
    $query = $db->prepare("
        SELECT COUNT(*) AS c
        FROM tbl_cart_detail
        INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart
        WHERE tbl_cart.status = 'pending' AND tbl_cart.id_user = ?
    ");
    $query->bind_param('i', $id_user);
    $query->execute();
    $result = $query->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    return (int)($row['c'] ?? 0);
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

function getPendingCartDetailsForUser($id_user, $limit = 50)
{
    global $db;
    $limit = (int)$limit;
    if ($limit <= 0) $limit = 50;
    if ($limit > 200) $limit = 200;

    $query = $db->prepare("
        SELECT tbl_cart_detail.*
        FROM tbl_cart_detail
        INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart
        WHERE tbl_cart.status = 'pending' AND tbl_cart.id_user = ?
        ORDER BY tbl_cart_detail.id_cart_detail DESC
        LIMIT $limit
    ");
    $query->bind_param('i', $id_user);
    $query->execute();
    $result = $query->get_result();
    if ($result && $result->num_rows) return $result;
    return null;
}
