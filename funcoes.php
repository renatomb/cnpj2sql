<?php

/*
Biblioteca de funcoes para tratamento dos dados da base CNPJ da Receita Federal
(C) 2023 - Renato Monteiro Batista
https://github.com/renatomb/cnpj2sql
*/

function remove_aspas($texto) {
   return addslashes(str_replace('"', '', remove_espacos_duplicados($texto)));
}

function troca_virgula_ponto($texto) {
   return str_replace(',', '.', $texto);
}

function aspas($texto) {
   $texto=trim($texto);
   if (empty($texto)) {
      return "NULL";
   }
   else {
      return '"' . $texto . '"';
   }
}

function vazio_null($texto) {
  $texto=trim($texto);
  if (empty($texto)) {
     return "NULL";
  }
  else {
     return $texto;
  }
}

function data_sql($texto) {
   /* Converte data AAAAMMDD para formato SQL */
   if (empty($texto)) {
      return "NULL";
   }
   else {
      return "'" . substr($texto, 0, 4) . "-" . substr($texto, 4, 2) . "-" . substr($texto, 6, 2) . "'";
   }
}

function remove_espacos_duplicados($texto) {
   return preg_replace('/\s+/', ' ', $texto);
}

?>