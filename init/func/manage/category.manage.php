<?php
function categoryNameExists($name)
{
    global $db;
    $query = $db->prepare("SELECT id_category FROM tbl_category WHERE name = ?");
    $query->bind_param('s', $name);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}
function categorySlugExists($slug)
{
    global $db;
    $query = $db->prepare("SELECT id_category FROM tbl_category WHERE slug = ?");
    $query->bind_param('s', $slug);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}

function getCategories()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_category");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}

function createCategory($name, $slug)
{
    global $db;
    $query = $db->prepare("INSERT INTO tbl_category (name,slug) VALUES (?,?)");
    $query->bind_param('ss', $name, $slug);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function getCategoryByID($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_category WHERE id_category = ?");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function updateCategory($id, $name, $slug)
{
    global $db;
    $query = $db->prepare("UPDATE tbl_category SET name = ?, slug = ? WHERE id_category = ?");
    $query->bind_param('ssi', $name, $slug, $id);
    if ($query->execute()) {
        return getCategoryByID($id);
    }
    return false;
}

function deleteCategory($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM tbl_category WHERE id_category =?");
    $query->bind_param('i', $id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
