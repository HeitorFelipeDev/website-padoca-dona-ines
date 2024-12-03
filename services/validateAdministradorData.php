<?php


function validateLogin($id, $password)
{
  $errors = [];

  if (empty($id)) {
    $errors[] = ['field' => 'id', 'error' => 'ID é obrigatório'];
  } 

  if (empty($password)) {
    $errors[] = ['field' => 'password', 'error' => 'Senha é obrigatória'];
  }

  if (count($errors) > 0) {
    return [
      "status" => "error",
      "invalid_fields" => $errors
    ];
  }

  return ["status" => "success"];
}