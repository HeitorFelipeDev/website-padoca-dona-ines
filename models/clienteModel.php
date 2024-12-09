<?php

require "../config/database/connection.php";

session_start();

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

function findByEmail($email) {
  try {
    $sql = "SELECT * FROM cliente WHERE email = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    return $result ? true : false;
  } catch (PDOException $error) {
    return false;
  }
}

function login($email, $password) {
  try {
    $sql = "SELECT codigo_cliente, nome, email, senha FROM cliente WHERE email = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
      if (password_verify($password, $user['senha'])) {
        
        $_SESSION["codigo_cliente"] = $user['codigo_cliente']; // para salvar o id do cliente em Session, mantenha

        return [
          'status' => 'success',
          'message' => 'Login realizado com sucesso!',
          'data' => [
            'id' => $user['codigo_cliente'],
            'nome' => $user['nome'],
            'email' => $user['email']
          ]
        ];

      } else {
        return [
          'status' => 'error',
          'message' => 'E-mail ou senha inválidos.',
          'data' => null
        ];
      }
    } else {
      return [
        'status' => 'error',
        'message' => 'E-mail ou senha inválidos.',
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