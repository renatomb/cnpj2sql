<?php

/*
Script para processamento dos dados dos optantes pelo simples nos arquvios CSV do CNPJ da receita federal
(C) 2023 - Renato Monteiro Batista
https://github.com/renatomb/cnpj2sql
*/

require_once("funcoes.php");

// Abrir o arquivo CSV .csv
$handle = fopen($argv[1] . ".csv", "r");
// Abrir o arquivo SQL para gravacao acrescentando os dados ao final do arquivo 
// Nome do arquivo sera passado por parametro
$gravacao = fopen($argv[1] . ".sql", "a");
$contador_sql=0;
$nomes_colunas=array("cnpj_basico","optante_simples","data_opcao_simples","data_exclusao_simples","mei","data_opcao_mei","data_exclusao_mei");
$inicio_query="INSERT INTO simples (" .  implode(",", $nomes_colunas) . ") VALUES ";

// Ler o arquivo linha por linha e mostrar na tela
if ($handle) {
   while (($line = fgets($handle)) !== false) {
      // separar os campos por ;
      $entrada = explode('";"', $line);
      for ($i=0;$i<7;$i++) {
         $campos[$i] = remove_aspas($entrada[$i]);
         // Tratamento do campo de CNPJ
         if ($i == 0) {
            $campos[$i] = vazio_null($campos[$i]);
         }
         // Tratamento dos campos sim/nao convertendo para binario
         if ((in_array($i, array(1,4)))) {
            $campos[$i] = simnao($campos[$i]);
         }
         // Tratamento dos campos de data que podem ser nulos
         if ((in_array($i, array(2,3,5,6)))) {
            $campos[$i] = data_sql($campos[$i]);
         }
      }
      if ($contador_sql == 0) {
         // Iniciar a query
         $query = $inicio_query . "(" . implode(",", $campos) . ")";
      }
      else {
         // Continuar a query
         $query .= ", (" . implode(",", $campos) . ")";
      }
      $contador_sql++;
      if ($contador_sql == 1000) {
         // Gravar no arquivo SQL
         fwrite($gravacao, $query . ";\n");
         echo ".";
         $contador_sql=0;
      }
      
   }
   // Fechar os arquivos
   fclose($handle);
   fclose($gravacao);
} 
else {
   die("erro abrindo o arquivo " . $argv[1] . "\n");
}

?>