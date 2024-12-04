<?php

require "../config/database/connection.php";

function listByID($id) {
  try {
    $sql = "SELECT nome, email FROM administrador WHERE codigo_administrador= ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    return [
      'status' => 'success',
      'message' => 'Administradores listados com sucesso!',
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

function findByEmail($email) {
  try {
    $sql = "SELECT * FROM administrador WHERE email = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    return $result ? true : false;
  } catch (PDOException $error) {
    return false;
  }
}

function login($id, $password) {
  try {
    $sql = "SELECT codigo_administrador, nome, email, senha FROM administrador WHERE codigo_administrador = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user) {
      if ($user['senha'] == $password || password_verify($password, $user['senha'])) {
        return [
          'status' => 'success',
          'message' => 'Login realizado com sucesso!',
          'data' => [
            'id' => $user['codigo_administrador'],
            'nome' => $user['nome'],
            'email' => $user['email']
          ]
        ];
      } else {
        return [
          'status' => 'error',
          'message' => 'ID ou senha inválidos.',
          'data' => [
            'id' => $user['codigo_administrador'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'senha' => $user['senha'],
            'senha2' => $password,
          ]
        ];
      }
    } else {
      return [
        'status' => 'error',
        'message' => 'ID ou senha inválidos.',
        'data' => null
      ];
    }
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => 'Erro ao realizar login: ' . $error->getMessage(),
      'data' => null
    ];
  }
}