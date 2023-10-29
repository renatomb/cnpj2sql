<?php

/*
Script para processamento dos dados das tabelas auxiliares dos arquvios CSV do CNPJ da receita federal
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
$nomes_colunas=array("id","nome");
echo "Processando " . $argv[1] . "\n";
switch($argv[1]) {
   case "CNAECSV":
      $nome_tabela="cnaes";
      break;
   case "MOTICSV":
      $nome_tabela="motivos";
      break;
   case "MUNICCSV":
      $nome_tabela="municipios";
      break;
   case "NATJUCSV":
      $nome_tabela="naturezas";
      break;
   case "PAISCSV":
      $nome_tabela="paises";
      break;
   case "QUALSCSV":
      $nome_tabela="qualificacoes";
      break;
   default:
      die("Tabela nao encontrada\n");
}
$inicio_query="INSERT INTO $nome_tabela (" .  implode(",", $nomes_colunas) . ") VALUES ";

// Ler o arquivo linha por linha e mostrar na tela
if ($handle) {
   // enquanto não for fim do arquivo

   while (($line = fgets($handle)) !== false) {
      // separar os campos por ;
      $entrada = explode('";"', $line);
      for ($i=0;$i<2;$i++) {
         $campos[$i] = remove_aspas($entrada[$i]);
         // Tratamento do campo de ID
         if ($i == 0) {
            $campos[$i] = vazio_null($campos[$i]);
         }
         // Tratamento do campos de texto
         if ($i == 1) {
            $campos[$i] = aspas($campos[$i]);
         }
      }
      if ($contador_sql == 0) {
         // Iniciar a query
         $query = $inicio_query . "(" . implode(",", $campos) . ")";
      }
      else {
         // Continuar a query
         print_r($campos);
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
   if ($contador_sql > 0) {
      // Gravar no arquivo SQL
      fwrite($gravacao, $query . ";\n");
      echo ".";
   }
   // Fechar os arquivos
   fclose($handle);
   fclose($gravacao);
} 
else {
   die("erro abrindo o arquivo " . $argv[1] . "\n");
}

?>