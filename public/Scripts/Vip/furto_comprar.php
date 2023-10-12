<?php
   require "../../includes/concectdb.php";
   include "../../includes/verifica_login.php";

   if (!$conect ){
      echo ("Você precisa estar logado!");
      exit ();
   }

   $protector->need_gold(PRECO_GOLD_FURTO);

   $tempoBase  = $userDetails->vip["furto"] ? $userDetails->vip["furto_duracao"] : atual_segundo();
   $tempo      = $tempoBase + 1 * 24 * 60 * 60;
   $tempoBase2 = $userDetails->vip["furto"] ? $userDetails->vip["furto_limite"] : atual_segundo();
   $tempo2     = $tempoBase2 + 15 * 24 * 60 * 60;
   
   // Verifica o limite do furto no banco de dados
   $connection->run("SELECT furto_limite FROM tb_vip WHERE id = ?",
      "i", $userDetails->tripulacao["id"]);
   $result = $connection->fetch();

   if ($result['furto_limtite'] === 0) {
      // permitir compra
      $conncetion->run("UPDATE tb_vip SET furto = 1, furto_limte = ?, furto_duracao = ? WHERE id = ?",
         "iii", array($tempo, $tempo2, $userDetails->tripulacao["id"]));
      echo ("Você adquiriu o Furto!");
   } else {
      $tempoRestante = $tempoBase2 - time ();
      $diasRestantes = floor($tempoRestante / (60 * 60 * 24));
      $tempoRestante %= 60 * 60 * 24;
      $horasRestantes = floor($tempoRestante / (60 * 60));
      $tempoRestante %= 60 * 60;
      $minutosRestantes = floor($tempoRestente / 60);
      echo ("Você já comprou o furto no últimos 15 dias, você podera comprar novamente em: $diasRestantes, $horasRestantes e $minutosRestantes");
   }
   
   $userDetails->reduz_gold(PRECO_GOLD_FURTO, "furto");
   
?>