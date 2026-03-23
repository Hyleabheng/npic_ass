<?php
function getStocks()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_stock");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}

function getTotalStockQty()
{
    global $db;
    $query = $db->query("SELECT COALESCE(SUM(qty),0) AS total_qty FROM tbl_stock");
    $row = $query ? $query->fetch_assoc() : null;
    return (int)($row['total_qty'] ?? 0);
}

function getProductStockSummary($limit = 20)
{
    // Returns: id_product, name, image, total_qty
    global $db;
    $limit = (int)$limit;
    if ($limit <= 0) $limit = 20;
    if ($limit > 200) $limit = 200;

    $query = $db->query("
        SELECT p.id_product, p.name, p.image, COALESCE(SUM(s.qty), 0) AS total_qty
        FROM tbl_product p
        LEFT JOIN tbl_stock s ON s.id_product = p.id_product
        GROUP BY p.id_product, p.name, p.image
        ORDER BY total_qty ASC, p.id_product DESC
        LIMIT $limit
    ");
    if ($query && $query->num_rows) return $query;
    return null;
}

function createStock($id_product, $qty, $date)
{
    global $db;
    $query = $db->prepare("INSERT INTO tbl_stock (id_product,qty,date) VALUES (?,?,?)");
    $query->bind_param('iis', $id_product, $qty, $date);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function getStockByID($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_stock WHERE id_stock = ?");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function deleteStock($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM tbl_stock WHERE id_stock = ?");
    $query->bind_param('i', $id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function updateStock($id_stock, $id_product, $qty, $date)
{
    global $db;
    $query = $db->prepare("UPDATE tbl_stock SET id_product = ?, qty = ?, date = ? WHERE id_stock = ?");
    $query->bind_param('iisi', $id_product, $qty, $date, $id_stock);
    $query->execute();
    if ($db->affected_rows) {
        return getStockByID($id_stock);
    }
    // allow "no changes" updates to be treated as success
    return getStockByID($id_stock);
}
