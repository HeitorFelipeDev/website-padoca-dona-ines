<?php

include_once "../models/categoriaModel.php";
include_once "../services/categoriaService.php";
$inputJSON = file_get_contents('php://input');
$requestData = json_decode($inputJSON, true);

if (isset($_POST['operation']) && $_POST['operation'] === "create") {
  $operation = "create";
  $name = $_POST['name'] ?? "";
  $description = $_POST['description'] ?? "";
  $image = $_FILES['image'] ?? null;
} else if (isset($_POST['operation']) && $_POST['operation'] === "edit") {
  $operation = "edit";
  $id = $_POST['id'] ?? "";
  $name = $_POST['name'] ?? "";
  $description = $_POST['description'] ?? "";
  $image = $_FILES['image'] ?? null;
} else {
  $operation = $requestData["operation"] ?? null;
}

switch ($operation) {
  case "create":
    createCategoria($name,  $description, $image);
    break;
  case "list":
    listCategoria($requestData["id"] ?? "");
    break;
  case "listProducts":
    listProdutos($requestData["category"] ?? "");
    break;
  case "edit":
    updateCategoria($id, $name,  $description, $image);
    break;
  case "delete":
    deleteCategory($requestData["id"] ?? "");
    break;
  default:
    echo json_encode(array(
      'status' => 'error',
      'message' => 'Operação inválida',
      'data' => null
    ));
    break;
}

function createCategoria($name, $description, $image)
{
  $validationResult = validateCategoriaData($name, $description, $image);

  if ($validationResult["status"] == "error") {
    http_response_code(400);
    echo json_encode($validationResult);
    return;
  }

  $image = uploadImage($image, $name);

  $result = create($name, $description, $image);

  if ($result["status"] == "success") {
    http_response_code(201);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}

function listCategoria($id)
{
  if (empty($id)) {
    $result = listAll();
  } else {
    $result = listByID($id);
  }

  if ($result["status"] == "success") {
    http_response_code(200);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}


function listProdutos($categoria)
{
  $result = listProdutosByCategoria($categoria);

  if ($result["status"] == "success") {
    http_response_code(200);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}

function updateCategoria($id, $name, $description, $image)
{

  $category = listByID($id);
  $prevImage = $category["data"]["imagem"];

  $imagePath = $prevImage;

  if($image) {
    $imagePath = uploadImage($image, $name);
    deleteImage($prevImage);
  }

  $resultValid = validateCategoriaUpdateData($name, $description, $image);

  if ($resultValid["status"] == "error") {
    http_response_code(400);
    echo json_encode($resultValid);
    return;
  }

  $result = update($id, $name, $description, $imagePath);

  if ($result["status"] == "success") {
    http_response_code(200); // Sucesso na atualização
    echo json_encode($result);
  } else {
    http_response_code(500); // Erro na atualização
    echo json_encode([
      'status' => 'error',
      'message' => 'Erro ao atualizar categoria.'
    ]);
  }
}



function deleteCategory($id)
{
  if (!$id) {
    http_response_code(500);
  }

  $result = listByID($id);
  $imagePath = $result["data"]["imagem"];

  $resDeleteImage = deleteImage($imagePath);

  if ($resDeleteImage["status"] == "success") {
    http_response_code(200);
  } else {
    http_response_code(500);
  }

  $result = deleteC($id);

  if ($result["status"] == "success") {
    http_response_code(200);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}