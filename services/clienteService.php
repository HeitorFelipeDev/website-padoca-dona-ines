<?php
include_once "../models/clienteModel.php";

function validateClienteData($name, $email, $password, $confirmPassword, $cpf)
{
  $errors = [];

  if (empty($name)) {
    $errors[] = ['field' => 'name', 'error' => 'Nome é obrigatório'];
  }

  if (empty($email)) {
    $errors[] = ['field' => 'email', 'error' => 'E-mail é obrigatório'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = ['field' => 'email', 'error' => 'Email inválido'];
  } elseif (findByEmail($email)) {
    $errors[] = ['field' => 'email', 'error' => 'E-mail já cadastrado'];
  }

  if (empty($password)) {
    $errors[] = ['field' => 'password', 'error' => 'Senha é obrigatória'];
  }

  if (empty($confirmPassword)) {
    $errors[] = ['field' => 'confirm-password', 'error' => 'Confirmar senha é obrigatório'];
  } else {
    if ($password !== $confirmPassword) {
      $errors[] = ['field' => 'confirm-password', 'error' => 'Senhas não coincidem'];
    }
  }

  if (empty($cpf)) {
    $errors[] = ['field' => 'cpf', 'error' => 'CPF é obrigatório'];
  } elseif (!isValidCpf($cpf)) {
    $errors[] = ['field' => 'cpf', 'error' => 'CPF inválido'];
  }

  if (count($errors) > 0) {
    return [
      "status" => "error",
      "invalid_fields" => $errors
    ];
  }

  return ["status" => "success"];
}

function validateClienteLogin($email, $password)
{
  $errors = [];

  if (empty($email)) {
    $errors[] = ['field' => 'email', 'error' => 'E-mail é obrigatório'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = ['field' => 'email', 'error' => 'Email inválido'];
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

function isValidCpf($cpf)
{
  // Remove caracteres não numéricos
  $cpf = preg_replace('/\D+/', '', $cpf);

  // Verifica o comprimento do CPF
  if (strlen($cpf) != 11) {
    return false;
  }

  // Verifica se todos os dígitos são iguais (sequencial)
  if (preg_match('/^(\d)\1{10}$/', $cpf)) {
    return false;
  }

  // Calcula o primeiro dígito verificador
  $cpfParcial = substr($cpf, 0, 9);
  $penultimoDigito = createDigit($cpfParcial);

  // Calcula o segundo dígito verificador
  $ultimoDigito = createDigit($cpfParcial . $penultimoDigito);

  // Monta o novo CPF com os dígitos verificadores calculados
  $novoCpf = $cpfParcial . $penultimoDigito . $ultimoDigito;

  // Retorna verdadeiro se o CPF é válido, falso caso contrário
  return $novoCpf === $cpf;
}

function createDigit($cpfParcial)
{
  $soma = 0;
  $multiplicador = strlen($cpfParcial) + 1;

  for ($i = 0; $i < strlen($cpfParcial); $i++) {
    $soma += $multiplicador * (int)$cpfParcial[$i];
    $multiplicador--;
  }

  $digito = 11 - ($soma % 11);
  return ($digito > 9) ? '0' : (string)$digito;
}