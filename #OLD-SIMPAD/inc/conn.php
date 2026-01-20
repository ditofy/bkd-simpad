<?php

require_once __DIR__ . '/function.php';

function conn() {
    $host = '173.16.6.2';
    $port = '5432';
    $database = 'simpad';
    $username = 'simpad';
    $password = 'simpad1!2@';

    $dsn = "pgsql:host=$host;port=$port;dbname=$database";

    try {
        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
        ]);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
}

function select($conn, $query, $params = null) {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function insert($conn, $query, $params = null) {
    $stmt = $conn->prepare($query);
    return $stmt->execute($params);
}

function update($conn, $query, $params = null) {
    $stmt = $conn->prepare($query);
    return $stmt->execute($params);
}

function delete($conn, $query, $params = null) {
    $stmt = $conn->prepare($query);
    return $stmt->execute($params);
}
