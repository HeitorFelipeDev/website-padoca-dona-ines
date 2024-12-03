<?php

include_once "../models/administradorModel.php";
include_once "../services/validateAdministradorData.php";
$inputJSON = file_get_contents('php://input');
$requestData = json_decode($inputJSON, true);

switch ($requestData["operation"]) {
  case "login":
    loginAdministrador($requestData["id"], $requestData["password"]);
    break;
  default:
    echo json_encode(array(
      'status' => 'error',
      'message' => 'Operação inválida',
      'data' => null
    ));
    break;
}

function loginAdministrador($id, $password)
{
  $validationResult = validateLogin($id, $password);

  if ($validationResult["status"] == "error") {
    http_response_code(400);
    echo json_encode($validationResult);
    return;
  }

  $result = login($id, $password);

  if ($result["status"] == "success") {
    http_response_code(200);
  } else {
    http_response_code(500);
  }

  echo json_encode($result);
}