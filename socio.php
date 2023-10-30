<?php

/*
Script para processamento dos dados dos socios dos arquvios CSV do CNPJ da receita federal
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
$nomes_colunas=array("cnpj_basico","identificador_socio","nome_socio","cnpj_socio","cod_qualificacao_socio","data_entrada_sociedade","perc_capital_social","cpf_representante_legal","nome_representante_legal","cod_qualificacao_representante_legal","desconhecido","cpf_socio_mascarado");
$inicio_query="INSERT INTO socio (" .  implode(",", $nomes_colunas) . ") VALUES ";

// Ler o arquivo linha por linha e mostrar na tela
if ($handle) {
   while (($line = fgets($handle)) !== false) {
      // separar os campos por ;
      $entrada = explode('";"', $line);
      for ($i=0;$i<11;$i++) {
         $campos[$i] = remove_aspas($entrada[$i]);
         // Tratamento dos campos com texto
         if ((in_array($i, array(2,8,10)))) {
            $campos[$i] = aspas($campos[$i]);
         }
         // Tratamento do campo cnpj/cpf do socio
         if ($i == 3) {
            if (strlen($campos[$i]) == 11) {
               // CPF
               $campos[11] = vazio_null($campos[$i]);
               $campos[$i] = "NULL";
            }
            else {
               // CNPJ
               $campos[$i] =  vazio_null($campos[$i]);
               $campos[11] = "NULL";
            }
         }
         // Tratamento das datas
         if ((in_array($i, array(5)))) {
            $campos[$i] = data_sql($campos[$i]);
         }
         // Tratamento dos campos numéricos 
         if ((in_array($i, array(0,1,4,6,7,9)))) {
            $campos[$i] = vazio_null($campos[$i]);
         }
      }
      if ($contador_sql == 0) {
         // ordenar os campos
         ksort($campos);
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
         echo "O";
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