<?php

/*
Script para processamento dos dados das empresas dos arquvios CSV do CNPJ da receita federal
(C) 2023 - Renato Monteiro Batista
https://github.com/renatomb/cnpj2sql
*/

require_once("funcoes.php");

// Abrir o arquivo CSV empresas.csv
$handle = fopen("empresas.csv", "r");
// Abrir o arquivo SQL para gravacao acrescentando os dados ao final do arquivo 
// Nome do arquivo sera passado por parametro
$gravacao = fopen($argv[1] . ".sql", "a");
$contador_sql=0;
$inicio_query="INSERT INTO empresas (cnpj_basico, razao_social, natureza_juridica, qualif_juridica, capital_social, porte, ente_federativo_responsavel) VALUES ";

// Ler o arquivo linha por linha e mostrar na tela
if ($handle) {
   while (($line = fgets($handle)) !== false) {
      // separar os campos por ;
      $campos = explode('";"', $line);
      for ($i=0;$i<count($campos);$i++) {
         $campos[$i] = remove_aspas($campos[$i]);
         if (($i == 1) || ($i == 6)) {
            $campos[$i] = aspas($campos[$i]);
         }
         if ($i == 4) {
            // Trocar virgula por ponto no capital social
            $campos[$i] = troca_virgula_ponto($campos[$i]);
         }
      }
      // Tratamento dos casos onde o porte está vazio
      $campos[5] = vazio_null($campos[5]);
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