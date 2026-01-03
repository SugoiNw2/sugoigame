<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

$login = $_POST["login"] ?? null;
$senha = $_POST["senha"] ?? null;

if (!$login || !$senha) {
    header("location:../../login.php?erro=1");
    exit();
}

/*if (!preg_match(EMAIL_FORMAT, $login)) {
    header("location:../../login.php?erro=1");
    exit();
}*/
if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
    header("location:../../login.php?erro=1");
    exit();
}

/* consulta segura */
$result = $connection->run(
    "SELECT conta_id, senha, ativacao, tripulacao_id, beta 
     FROM tb_conta 
     WHERE email = ? 
     LIMIT 1",
    "s",
    [$login]
)->fetch_array();

/* não encontrou */
if (!$result) {
    header("location:../../login.php?erro=1");
    exit();
}

/* senha inválida */
if (!password_verify($senha, $result["senha"])) {
    header("location:../../login.php?erro=1");
    exit();
}

/* beta */
if (IS_BETA && $result["beta"] != 1) {
    header("location:../../login.php?erro=2");
    exit();
}

/* login OK */
$userDetails->set_authentication($result["conta_id"]);

/* redirecionamento */
if ($result["tripulacao_id"]) {
    header("location:../../?ses=home");
} else {
    header("location:../../?ses=seltrip");
}
exit();
