<?php

require "../config/database/connection.php";

function create($name, $email, $password, $cpf) {
  try {
    $password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO cliente (nome, email, senha, cpf) VALUES (?, ?, ?, ?)";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$name, $email, $password, $cpf]);
    $lastInsertId = $GLOBALS['database']->lastInsertId();

    return [
      'status' => 'success',
      'message' => 'Cadastro realizado com sucesso!',
      'data' => ['id' => $lastInsertId]
    ];
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => 'Erro ao cadastrar-se: ' . $error->getMessage(),
      'data' => null
    ];
  }
}

function listAll() {
  try {
    $sql = "SELECT nome, email, telefone, cpf FROM cliente";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return [
      'status' => 'success',
      'message' => 'Clientes listados com sucesso!',
      'data' => $result
    ];
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => $error->getMessage(),
      'data' => null
    ];
  }
}

function listByID($id) {
  try {
    $sql = "SELECT nome, email, telefone, cpf FROM cliente WHERE codigo_cliente = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    return [
      'status' => 'success',
      'message' => 'Cliente listados com sucesso!',
      'data' => $result
    ];
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => $error->getMessage(),
      'data' => null
    ];
  }
}