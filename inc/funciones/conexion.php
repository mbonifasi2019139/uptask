<?php

$conn = new mysqli('localhost', 'root', 'admin2020', 'dbuptask');

if ($conn->connect_error) {
    echo $conn->connect_error;
}

$conn->set_charset('utf8');
