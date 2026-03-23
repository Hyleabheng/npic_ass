<?php


function usernameExists($username)
{
    global $db;
    $query = $db->prepare("SELECT id_user FROM tbl_user WHERE username = ?");
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}

function registerUser($name, $username, $passwd)
{
    $name = trim((string)$name);
    $username = trim((string)$username);
    $passwd = (string)$passwd;

    if ($name === '' || $username === '' || $passwd === '') {
        return false;
    }
    if (usernameExists($username)) {
        return false;
    }

    // NOTE: Current system uses plain-text passwords (see logUserIn).
    // Keep consistent to avoid breaking login.
    return createUser($name, $username, $passwd);
}
function logUserIn($username, $passwd)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_user WHERE username = ? AND passwd = ?");
    $query->bind_param('ss', $username, $passwd);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        $_SESSION['id_user'] = $result->fetch_object()->id_user;
        return true;
    }
    return false;
}

function LoggedInUser()
{
    global $db;
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $query = $db->query("SELECT id_user,user_label FROM tbl_user WHERE id_user = '$id_user'");
        if ($query->num_rows) {
            return $query->fetch_object();
        } else {
            return false;
        }
    } else {
        return false; // $db->close();
    }
}

function isAdmin()
{
    global $db;
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $query = $db->query("SELECT id_user FROM tbl_user WHERE id_user = '$id_user' AND level ='Admin'");
        if ($query->num_rows) {
            return true;
        }
        return false;
    }
    return false;
}

function isUser()
{
    global $db;
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $query = $db->query("SELECT id_user FROM tbl_user WHERE id_user = '$id_user' AND level ='User'");
        if ($query->num_rows) {
            return true;
        }
        return false;
    }
    return false;
}
