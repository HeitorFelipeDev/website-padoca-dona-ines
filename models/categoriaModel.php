<?php

require "../config/database/connection.php";

function create($name, $description, $imagePath)
{
  try {
    $sql = "INSERT INTO categoria_produto (nome, descricao, imagem) VALUES (?, ?, ?)";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$name, $description, $imagePath]);
    $lastInsertId = $GLOBALS['database']->lastInsertId();

    return [
      'status' => 'success',
      'message' => 'Cadastro realizado com sucesso!',
      'data' => ['id' => $lastInsertId]
    ];
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => 'Erro ao cadastrar categoria: ' . $error->getMessage(),
      'data' => null
    ];
  }
}

function listAll()
{
  try {
    $sql = "SELECT * FROM categoria_produto";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return [
      'status' => 'success',
      'message' => 'Categorias listadas com sucesso!',
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

function listByID($id)
{
  try {
    $sql = "SELECT * FROM categoria_produto WHERE codigo_categoria = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    return [
      'status' => 'success',
      'message' => 'Categorias listadas com sucesso!',
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

function listProdutosByCategoria($categoria)
{
  try {
    $sql = "SELECT cp.nome AS categoria, COUNT(p.codigo_produto) AS quantidade_produtos FROM Produto p INNER JOIN Categoria_Produto cp ON p.codigo_categoria = cp.codigo_categoria WHERE  cp.nome = ? GROUP BY  cp.nome";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$categoria]);
    $result = $stmt->fetch();

    return [
      'status' => 'success',
      'message' => 'Produtos listadas com sucesso!',
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

function update($id, $name, $description, $imagePath)
{
  try {
    $sql = "UPDATE categoria_produto SET nome = ?, descricao = ?, imagem = ? WHERE codigo_categoria = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$name, $description, $imagePath, $id]);

    return [
      'status' => 'success',
      'message' => 'Categoria atualizada com sucesso!',
      'data' => null
    ];
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => 'Erro ao atualizar categoria: ' . $error->getMessage(),
      'data' => null
    ];
  }
}

function deleteC($id)
{
  try {
    $sql = "DELETE FROM categoria_produto WHERE codigo_categoria = ?";
    $stmt = $GLOBALS['database']->prepare($sql);
    $stmt->execute([$id]);

    return [
      'status' => 'success',
      'message' => 'Categoria excluída com sucesso!',
      'data' => null
    ];
  } catch (PDOException $error) {
    return [
      'status' => 'error',
      'message' => $error->getMessage(),
      'data' => null
    ];
  }
}