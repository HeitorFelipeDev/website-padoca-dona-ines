<?php

include_once "../models/clienteModel.php";
include_once "../services/clienteService.php";
$inputJSON = file_get_contents('php://input');
$requestData = json_decode($inputJSON, true);

switch ($requestData["operation"]) {
  case "create":
    createCliente($requestData["name"] ?? "",  $requestData["email"] ?? "", $requestData["password"] ?? "", $requestData["confirmPassword"] ?? "", $requestData["cpf"] ?? "");
    break;
  case "login":
    loginCliente($requestData["email"], $requestData["password"]);
    break;
  case "list":
    listCliente($requestData["id"] ?? "");
    break;
  default:
    echo json_encode(array(
      'status' => 'error',
      'message' => 'Operação inválida',
      'data' => null
    ));
    break;
}

function createCliente($name, $email, $password, $confirmPassword, $cpf)
{
  $validationResult = validateClienteData($name, $email, $password, $confirmPassword, $cpf);

  if ($validationResult["status"] == "error") {
    http_response_code(400);
    echo json_encode($validationResult);
    return;
  }

  $result = create($name, $email, $password, $cpf);

  if ($result["status"] == "success") {
    http_response_code(201);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}

function listCliente($id)
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

function loginCliente($email, $password)
{
  $validationResult = validateClienteLogin($email, $password);

  if ($validationResult["status"] == "error") {
    http_response_code(400);
    echo json_encode($validationResult);
    return;
  }

  $result = login($email, $password);

  if ($result["status"] == "success") {
    http_response_code(200);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}