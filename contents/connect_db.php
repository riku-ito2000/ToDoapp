<?php

function connectDB()
{
    $dbn = 'mysql:dbname=TODOapp;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    try {
        $dbh = new PDO($dbn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (Exception $e) {
        die("データベースに接続できません: " . $e->getMessage());
    }
}