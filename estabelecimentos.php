<?php

/*
Script para processamento dos dados dos estabelecimentos dos arquvios CSV do CNPJ da receita federal
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
$nomes_colunas=array("cnpj_basico", "cnpj_id", "cnpj_dv", "matriz_filial", "nome_fantasia", "situacao_cadastral", "data_situacao_cadastral", "motivo_situacao_cadastral", "cidade_exterior", "cod_pais", "data_inicio_ativ", "cnae_fiscal", "cnae_secundario", "tipo_logradouro", "logradouro", "numero", "complemento", "bairro", "cep", "uf", "cod_municipio", "ddd_1", "telefone_1", "ddd_2", "telefone_2", "ddd_fax", "num_fax", "email");
$inicio_query="INSERT INTO estabelecimentos (" .  implode(",", $nomes_colunas) . ") VALUES ";

// Ler o arquivo linha por linha e mostrar na tela
if ($handle) {
   while (($line = fgets($handle)) !== false) {
      // separar os campos por ;
      $entrada = explode('";"', $line);
      for ($i=0;$i<28;$i++) {
         $campos[$i] = remove_aspas($entrada[$i]);
         // Tratamento dos campos com texto
         if ((in_array($i, array(4,8,12,13,14,15,16,17,19)))) {
            $campos[$i] = aspas($campos[$i]);
         }
         // Tratamento do campo de e-mail, convertendo para minusculo
         if ($i == 27) {
            $campos[$i] = aspas(strtolower($campos[$i]));
         }
         // Tratamento das datas
         if ((in_array($i, array(6,10)))) {
            $campos[$i] = data_sql($campos[$i]);
         }
         // Tratamento dos campos numéricos que permitem NULO
         if ((in_array($i, array(9,18,21,22,23,24,25,26)))) {
            if (in_array($i, array(21,23,25))) {
               $campos[$i] = intval($campos[$i]);

            }
            $campos[$i] = vazio_null($campos[$i]);
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
   if ($contador_sql > 0) {
      // Gravar no arquivo SQL
      fwrite($gravacao, $query . ";\n");
      echo "T";
   }
   // Fechar os arquivos
   fclose($handle);
   fclose($gravacao);
} 
else {
   die("erro abrindo o arquivo " . $argv[1] . "\n");
}

?>