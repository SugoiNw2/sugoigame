<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (!isset($_POST["login"], $_POST["senha"])) {
    header("location:../../login.php");
    exit();
}

$login = strtolower(trim($_POST["login"]));
$senha = trim($_POST["senha"]);

if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
    header("location:../../login.php?erro=1");
    exit();
}

$result = $connection->run(
    "SELECT conta_id, senha, ativacao, tripulacao_id, beta 
     FROM tb_conta 
     WHERE email = ? 
     LIMIT 1",
    "s",
    [$login]
);

if (!$result->count()) {
    header("location:../../login.php?erro=1");
    exit();
}

$conta = $result->fetch();

if (!password_verify($senha, $conta["senha"])) {
    header("location:../../login.php?erro=1");
    exit();
}

if (defined('IS_BETA') && IS_BETA && isset($conta['beta']) && $conta['beta'] != 1) {
    header("location:../../login.php?erro=2");
    exit();
}

$userDetails->set_authentication($conta["conta_id"]);

if (!empty($conta["tripulacao_id"])) {
    header("location:../../?ses=home");
} else {
    header("location:../../?ses=seltrip");
}

exit();
