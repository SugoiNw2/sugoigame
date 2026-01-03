<?php
// GARANTE QUE SEJA ARRAY
$conta = is_array($userDetails->conta) ? $userDetails->conta : [];
$tripulacao = is_array($userDetails->tripulacao) ? $userDetails->tripulacao : [];
$ilha = is_array($userDetails->ilha) ? $userDetails->ilha : [];
$navio = is_array($userDetails->navio) ? $userDetails->navio : [];

// FLAGS
$contaOk = !empty($conta);
$conect = !empty($tripulacao);
$inrota = !empty($userDetails->rotas);
$innavio = !empty($navio);

// BASE DO USUÁRIO
$usuario = $tripulacao;

// CONTA
$usuario["conta_id"] = $conta["conta_id"] ?? null;
$usuario["email"]    = $conta["email"] ?? null;

// TRIPULAÇÃO
$id = $tripulacao["id"] ?? null;

// ILHA
$usuario["mar"]  = $ilha["mar"] ?? null;
$usuario["ilha"] = $ilha["ilha"] ?? null;

// NAVIO
$usuario["capacidade_iventario"] = $navio["capacidade_inventario"] ?? 0;

// VIP / OUTROS
$usuario_vip = $userDetails->vip ?? false;
$inally = !empty($userDetails->ally);
$usuario["alianca"] = $userDetails->ally ?? null;


if ($innavio) {
    $navio = $userDetails->navio;
    $usuario["navio"] = $userDetails->navio["cod_navio"];
    $usuario["casco"] = $userDetails->navio["cod_casco"];
    $usuario["leme"] = $userDetails->navio["cod_leme"];
    $usuario["velas"] = $userDetails->navio["cod_velas"];
    $usuario["canhao"] = $userDetails->navio["cod_canhao"];
    $usuario["navio_hp"] = $userDetails->navio["hp"];
    $usuario["navio_hp_max"] = $userDetails->navio["hp_max"];
    $usuario["navio_lvl"] = $userDetails->navio["lvl"];
    $usuario["navio_reparo"] = $userDetails->navio["reparo"];
    $usuario["navio_reparo_tipo"] = $userDetails->navio["reparo_tipo"];
    $usuario["navio_reparo_quant"] = $userDetails->navio["reparo_quant"];
    $usuario["navio_xp"] = $userDetails->navio["xp"];
    $usuario["navio_xp_max"] = $userDetails->navio["xp_max"];
}

$usuario_vip = $userDetails->vip;
$inally = !!$userDetails->ally;
$usuario["alianca"] = $userDetails->ally;