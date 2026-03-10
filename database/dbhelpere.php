<?php
require_once ('config.php');

function execute($sql){
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("SQL Error: " . mysqli_error($conn));
    }

    mysqli_close($conn);
    return true;
}

function executeResult($sql, $isSingle = false) {
    $conn = mysqli_connect('localhost', 'root', '', 'webbanhang');
    mysqli_set_charset($conn, 'utf8');

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("SQL Error: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    mysqli_close($conn);

    if ($isSingle) {
        return count($data) > 0 ? $data[0] : null;
    }
    return $data;
}