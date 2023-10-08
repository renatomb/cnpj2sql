<?php

/*
Script para processamento dos dados das empresas dos arquvios CSV do CNPJ da receita federal
(C) 2023 - Renato Monteiro Batista
https://github.com/renatomb/cnpj2sql
*/

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
      $campos = explode(";", $line);
      for ($i=0;$i<count($campos);$i++) {
         if ($i != 1) { 
            // não remover aspas da razão social
            $campos[$i] = remove_aspas($campos[$i]);
         }
         if ($i == 4) {
            // Trocar virgula por ponto no capital social
            $campos[$i] = troca_virgula_ponto($campos[$i]);
         }
      }
      // Remover quebra de linha do ultimo campo
      $campos[6] = str_replace("\n", "", $campos[6]);
      // Ultimo campo pode ser vazio, converter para null
      if (empty($campos[6])) {
         $campos[6] = "NULL";
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
      if ($contador_sql == 500) {
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

function remove_aspas($texto) {
    return str_replace('"', '', $texto);
}

function troca_virgula_ponto($texto) {
    return str_replace(',', '.', $texto);
}

?>