<?php

/*
Biblioteca de funcoes para tratamento dos dados da base CNPJ da Receita Federal
(C) 2023 - Renato Monteiro Batista
https://github.com/renatomb/cnpj2sql
*/

function remove_aspas($texto) {
   return addslashes(str_replace('"', '', $texto));
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

?>