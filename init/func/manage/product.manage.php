<?php
function getProducts()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_product");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}

function productNameExists($name)
{
    global $db;
    $query = $db->prepare("SELECT id_product FROM tbl_product WHERE name = ?");
    $query->bind_param('s', $name);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}
function productSlugExists($slug)
{
    global $db;
    $query = $db->prepare("SELECT id_product FROM tbl_product WHERE slug = ?");
    $query->bind_param('s', $slug);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}

function createProduct($name, $slug, $price, $short_des, $long_des, $image, $id_categories)
{
    $image_path = uploadProductImage($image);
    global $db;
    $db->begin_transaction();
    try {
        $query = $db->prepare("INSERT INTO tbl_product (name,slug,price,qty,short_des,long_des,image) VALUES (?,?,?,0,?,?,?)");
        $query->bind_param('ssdsss', $name, $slug, $price, $short_des, $long_des, $image_path);
        $query->execute();
        if ($db->affected_rows) {
            $id_product = $query->insert_id;
            foreach ($id_categories as $id_category) {
                $query1 = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUES (?,?)");
                $query1->bind_param('ii', $id_category, $id_product);
                $query1->execute();
            }
            $db->commit();
            return true;
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $db->rollback();
        return false;
    }
}

function getProductByID($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_product WHERE id_product = ?");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function deleteProduct($id)
{
    global $db;

    $product = getProductByID($id);
    // $db->query("DELETE FROM tbl_product_category WHERE id_product ='$id'");
    $query = $db->prepare("DELETE FROM tbl_product WHERE id_product =?");
    $query->bind_param('i', $id);
    $query->execute();
    if ($db->affected_rows) {
        unlink($product->image); // path + filename
        return true;
    }
    return false;
}

function getProductCategories($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_category INNER JOIN tbl_product_category ON tbl_category.id_category = tbl_product_category.id_category WHERE id_product = ?");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result;
    }
    return null;
}

function updateProduct($id, $name, $slug, $price, $short_des, $long_des, $image, $new_id_categories)
{
    $product = getProductByID($id);
    $image_path = $product->image;

    if ($image['error'] === 0) {
        $image_path = uploadProductImage($image);
        if ($image_path) {
            unlink($product->image);
        }
    }

    global $db;
    $db->begin_transaction();
    try {

        $old_id_categories = [];
        $product_categories = getProductCategories($id);
        if($product_categories !== null){
            while ($row = $product_categories->fetch_object()) {
                $old_id_categories[] = $row->id_category;
            }
        }

        $query = $db->prepare("UPDATE tbl_product SET name = ?, slug = ?, price = ?, short_des = ?, long_des = ?, image = ? WHERE id_product = ?");
        $query->bind_param('ssdsssi', $name, $slug, $price, $short_des, $long_des, $image_path, $id);
        $query->execute();

        foreach ($old_id_categories as $old_id_category) {
            if (!in_array($old_id_category, $new_id_categories)) {
                $query1 = $db->prepare("DELETE FROM tbl_product_category WHERE id_product = ? AND id_category = ?");
                $query1->bind_param('ii', $id, $old_id_category);
                $query1->execute();
            }
        }

        foreach ($new_id_categories as $new_id_category) {
            if (!in_array($new_id_category, $old_id_categories)) {
                $query1 = $db->prepare("INSERT INTO tbl_product_category (id_product, id_category) VALUES (?, ?)");
                $query1->bind_param('ii', $id, $new_id_category);
                $query1->execute();
            }
        }

        $db->commit();
        return getProductByID($id);
    } catch (Exception $e) {
        $db->rollback();
        return false;
    }
}


function uploadProductImage($image)
{
    $img_name = $image['name'];
    $img_size = $image['size'];
    $tmp_name = $image['tmp_name'];
    $error = $image['error'];

    $dir = './assets/images/';

    $allow_exs = ['pjg', 'png', 'jpeg'];
    if ($error !== 0) {
        throw new Exception('Unknown error occurred!');
    }

    if ($img_size > 500000) {
        throw new Exception('File size is too large!');
    }

    $image_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $image_lowercase_ex = strtolower($image_ex);

    if (!in_array($image_lowercase_ex, $allow_exs)) {
        throw new Exception('File extension is not allowed!');
    }

    $new_image_name = uniqid("PI-") . '.' . $image_lowercase_ex;
    $image_path = $dir . $new_image_name;
    move_uploaded_file($tmp_name, $image_path);
    return $image_path; //string
}
