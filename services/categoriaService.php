<?php
include_once "../models/categoriaModel.php";

function validateCategoriaData($name, $description, $image)
{
  $errors = [];

  // Validação do nome
  if (empty($name)) {
    $errors[] = ['field' => 'name', 'error' => 'Nome da categoria é obrigatório' . $name . " aaaaa"];
  }

  // Validação da descrição
  if (empty($description)) {
    $errors[] = ['field' => 'description', 'error' => 'Descrição da categoria é obrigatória'];
  }

  // Validação da imagem (upload)
  if (empty($description)) {
    $errors[] = ['field' => 'description', 'error' => 'A descrição é obrigatória'];
  }

  if (empty($image['name']) && empty($existingImagePath)) {
    $errors[] = ['field' => 'image', 'error' => 'A imagem é obrigatória'];
  } elseif (!empty($image['name'])) {
    // Validar extensão da nova imagem enviada
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
      $errors[] = ['field' => 'image', 'error' => 'Formato de imagem inválido.'];
    }
  }

  // Retornar erros se houver
  if (count($errors) > 0) {
    return [
      "status" => "error",
      "invalid_fields" => $errors
    ];
  }
  return ["status" => "success"];
}

function validateCategoriaUpdateData($name, $description, $image)
{
  $errors = [];

  // Validação do nome
  if (empty($name)) {
    $errors[] = ['field' => 'name', 'error' => 'Nome da categoria é obrigatório'];
  }

  // Validação da descrição
  if (empty($description)) {
    $errors[] = ['field' => 'description', 'error' => 'Descrição da categoria é obrigatória'];
  }

  // Validação da imagem (upload)
  if (!empty($image['name'])) {
    // Validar extensão da nova imagem enviada
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
      $errors[] = ['field' => 'image', 'error' => 'Formato de imagem inválido.'];
    }
  }
  
  // Retornar erros se houver
  if (count($errors) > 0) {
    return [
      "status" => "error",
      "invalid_fields" => $errors
    ];
  }
  return ["status" => "success"];
}

function uploadImage($image, $name)
{
  $uploadDir = '../assets/uploads/categorias/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // Cria o diretório se não existir
  }

  $uniqueName = uniqid() . '_' . basename(strtolower($name) . "." . strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)));
  $uploadFilePath = $uploadDir . $uniqueName;

  if (move_uploaded_file($image['tmp_name'], $uploadFilePath)) {
    return $uploadFilePath; // Retorna o caminho da imagem para salvar no banco
  }

  return false; // Falha no upload
}

function deleteImage($imagePath)
{
  if (file_exists($imagePath)) {
    // Apaga o arquivo
    if (unlink($imagePath)) {
      return ["status" => "success", "message" => "Imagem excluída com sucesso"];
    } else {
      return ["status" => "error", "message" => "Falha ao excluir a imagem"];
    }
  } else {
    return ["status" => "error", "message" => "Arquivo não encontrado"];
  }
}