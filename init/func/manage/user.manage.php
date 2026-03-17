<?php
function getUsers()
{
    //admin // user
    global $db;
    $query = $db->query("SELECT id_user,user_label,level FROM tbl_user WHERE level = 'User'");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}

function createUser($user_label, $username, $passwd)
{
    global $db;
    $query = $db->prepare("INSERT INTO tbl_user(user_label,username,passwd,level) VALUES (?,?,?,'User')");
    $query->bind_param('sss', $user_label, $username, $passwd);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}


function getUserByID($id)
{
    global $db;
    $query = $db->prepare("SELECT id_user,user_label,level FROM tbl_user WHERE id_user = ? AND level = 'User'");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function updateUser($id, $user_label, $username, $passwd)
{
    global $db;

    // if (empty($username)) {
    //     $username_query = "";
    // } else {
    //     $username_query = ", username = '$username'";
    // }

    $username_query = empty($username) ?  "" : ", username = '$username'";

    // if (empty($passwd)) {
    //     $passwd_query = "";
    // } else {
    //     $passwd_query = ", passwd = '$passwd'";
    // }

    $passwd_query = empty($passwd) ?  "" : ", passwd = '$passwd'";


    // $db->query("UPDATE tbl_user SET user_label = '$user_label', username = '$username', passwd = '$passwd' WHERE id_user = '$id'");

    $db->query("UPDATE tbl_user SET user_label = '$user_label'" . $username_query . $passwd_query . " WHERE id_user = '$id'");

    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function deleteUser($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM tbl_user WHERE id_user =?");
    $query->bind_param('i', $id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
