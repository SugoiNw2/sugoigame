<?php
   require "../../includes/conectdb.php";
   include "../../includes/verifica_login.php";

   if (!$conect ){
      echo ("Você precisa estar logado!");
      exit ();
   }

   $protector->need_gold(PRECO_DOBRAO_FURTO);

   $tempoBase  = $userDetails->vip["furto"] ? $userDetails->vip["furto_duracao"] : atual_segundo();
   $tempo      = $tempoBase + 86400;
   $tempoBase2 = $userDetails->vip["furto"] ? $userDetails->vip["furto_limite"] : time();
   $tempo2     = $tempoBase2 + 15 * 24 * 60 * 60;
   $tempoBase3 = $userDetails->vip["furto"] ? $userDetails->vip["furto_duracao_exib"] : time();
   $tempo3     = $tempoBase3 + 1 * 24 * 60 * 60;
   
   $tempoAtual = time();
   
   // Verifica o limite do furto no banco de dados
   $query = $connection->prepare("SELECT furto_limite FROM tb_vip WHERE id = ?");
   $query->bind_param("i", $userDetails->tripulacao["id"]);
   $query->execute();
   $result = $query->get_result()->fetch_assoc();
   if ($result && strtotime($result['furto_limite']) < time()) {
      // permitir compra
      // Desconta o ouro 
      $userDetails->reduz_gold(PRECO_DOBRAO_FURTO, "furto");
      $query = $connection->prepare("UPDATE tb_vip SET furto = '1', furto_limite = NOW() + INTERVAL 15 DAY, furto_duracao='$tempo', furto_duracao_exib = NOW() + INTERVAL 1 DAY WHERE id = ?");
      $query->bind_param("i", $userDetails->tripulacao["id"]);

      $query->execute();
      echo ("Você adquiriu o Furto!");
   } else {
      if ($result){
         $furto_limite = strtotime($result['furto_limite']);
         $tempoRestante = $furto_limite - time ();
         $diasRestantes = floor($tempoRestante / (60 * 60 * 24));
         $tempoRestante %= 60 * 60 * 24;
         $horasRestantes = floor($tempoRestante / (60 * 60));
         $tempoRestante %= 60 * 60;
         $minutosRestantes = floor($tempoRestente / 60);
         echo ("Você já comprou o furto nos últimos 15 dias, você podera comprar novamente em $diasRestantes Dias e $horasRestantes horas");
?>