<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

// 1️⃣ Garante POST
if (!isset($_POST["login"], $_POST["senha"])) {
    header("location:../../login.php");
    exit();
}

// 2️⃣ Normaliza dados
$login = strtolower(trim($_POST["login"]));
$senha = trim($_POST["senha"]);

// 3️⃣ Valida email
if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
    header("location:../../login.php?erro=1");
    exit();
}

// 4️⃣ Busca usuário usando prepared statement da sua classe
$result = $connection->run(
    "SELECT conta_id, senha, ativacao, tripulacao_id, beta 
     FROM tb_conta 
     WHERE email = ? 
     LIMIT 1",
    "s",
    [$login]
);

// 5️⃣ Verifica se encontrou o usuário
if (!$result->count()) {
    header("location:../../login.php?erro=1");
    exit();
}

// 6️⃣ Recupera os dados como array associativo
$conta = $result->fetch(); // array associativo: $conta['conta_id'], $conta['senha'], ...

// 7️⃣ Verifica senha
if (!password_verify($senha, $conta["senha"])) {
    header("location:../../login.php?erro=1");
    exit();
}

// 8️⃣ Verifica beta se necessário
if (defined('IS_BETA') && IS_BETA && isset($conta['beta']) && $conta['beta'] != 1) {
    header("location:../../login.php?erro=2");
    exit();
}

// 9️⃣ Autentica o usuário
$userDetails->set_authentication($conta["conta_id"]);

// 🔟 Redireciona de acordo com a tripulação
if (!empty($conta["tripulacao_id"])) {
    header("location:../../?ses=home");
} else {
    header("location:../../?ses=seltrip");
}
exit();
