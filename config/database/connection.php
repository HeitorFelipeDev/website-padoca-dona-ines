<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "root";
$dbname = "padocadonaines";

try {
  $database = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
  $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {

  die("Erro ao conectar com o Banco de Dados: " . $err->getMessage());
}