<?php
require_once 'config.php';

function connectDB() {
    // 接続情報を使用してデータベースに接続
    try {
        // var_dump("mysql:host=".databaseHost.";dbname=".databaseName);
        $pdo = new PDO("mysql:host=".databaseHost.";dbname=".databaseName, databaseUsername, databasePassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("データベースに接続できません: " . $e->getMessage());
    }
}
