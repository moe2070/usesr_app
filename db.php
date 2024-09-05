<?php
$con = new mysqli('localhost', 'moe12', 'MO12OS', 'users_app1');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
