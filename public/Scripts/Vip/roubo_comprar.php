<?php 
    require "../../includes/conectdb.php";
    include "../../includes/verifica_login.php";

    if(!conect ){
        echo ("Você precisa estar logado");
        exit ();
    }

    $protector->need_gold(PRECO_GOLD_ROUBO);

    $tempoBase  = $userDetails->vip["roubo"] ? $userDetails->vip["roubo_duracao"] : atual_segundo();
    $tempo      = $tempoBase + 86400;
    $tempoBase2 = $userDetails->vip["roubo"] ? $userDetails->vip["roubo_limite"] : time();
    $tempo2     = $tempoBase2 + 15 * 24 * 60 * 60;
    $tempoBase3 = $userDetails->vip["roubo"] ? $userDetails->vip["roubo_duracao_exib"] : time();
    $tempo3     = $tempoBase3 + 1 * 24 * 60 * 60;

    $tempoAtual = time();

    //Verifica limite do roubo no banco de dados 
    $query  = $connection->prepare("SELECT roubo_limite FROM tb_vip WHERE id = ?");
    $query->bind_param("i", $userDetails->tripulacao["id"]);
    $query->execute();
    $result = $query->get_result()->fetch_assoc();

    if($result && strtotime($result['roubo_limite']) < time()){
        // permitir compra
        // reduz ouro 
        $userDetails->reduz_gold(PRECO_GOLD_ROUBO, "roubo");

        $query = $connection->prepare("UPDATE tb_vip SET roubo = '1', roubo_duracao = '$tempo', roubo_limite = NOW() + INTERVAL 15 DAY, roubo_duracao_exib = NOW() + INTERVAL 1 DAY WHERE id = ?");
        $query->bind_param("i", $userDetails->tripulacao["id"]);
        $query->execute();
        echo ("Você adquiriu o ROUBO!");
    }else{
        if($result){
            $roubo_limite = strtotime($result['roubo_limite']);
            $tempoRestante = $roubo_limite - time ();
            $diasRestantes = floor($tempoRestante / (60 * 60 * 24));
            $tempoRestante %= 60 * 60 * 24;
            $horasRestantes = floor($tempoRestante / (60 * 60));
            $tempoRestante %= 60 * 60;
            $minutosRestantes = floor($tempoRestente / 60);
            echo ("Você já comprou o roubo nos últimos 15 dias, você podera comprar novamente em $diasRestantes Dias e $horasRestantes horas");
        }
    }

?>