#!/bin/bash
# Script para download e importação da base de dados CNPJ disponibilizada
# no portal Dados Abertos da Receita Federal do Brasil
#
# Autor: Renato Monteiro Batista
# Data: 12/02/2023
#
# Versão 1.0

ICNPJ=$(pwd)
./download_cnpj.sh

# uma vez feito o download do arquivo só me interessa os arquivos .zip
mkdir $ICNPJ/200.152.38.155/zip
mkdir $ICNPJ/200.152.38.155/sql
mv $ICNPJ/200.152.38.155/CNPJ/*.zip $ICNPJ/200.152.38.155/zip/
mv $ICNPJ/200.152.38.155/CNPJ/regime_tributario/*.zip $ICNPJ/200.152.38.155/zip/

# Descompactar todos os arquivos zip existentes no diretório atual
for file in $ICNPJ/200.152.38.155/zip/Empresas*.zip; do
  unzip "$file"
  for arquivo in *CSV; do
    echo "Processando dados de $arquivo:"
    iconv -f iso-8859-1 -t utf-8 "$arquivo" > "empresas.csv"
    rm -f "$arquivo"
    php empresas.php $arquivo
    rm -f empresas.csv
  done
  rm -f "$file"
done
mv *.sql $ICNPJ/200.152.38.155/sql/


for file in $ICNPJ/200.152.38.155/zip/Estabelecimentos*.zip; do
  unzip "$file"
  for arquivo in *ESTABELE; do
    echo "Processando dados de $arquivo:"
    iconv -f iso-8859-1 -t utf-8 "$arquivo" > "estabelecimentos.csv"
    rm -f $arquivo
    php estabelecimentos.php $arquivo
    rm -f estabelecimentos.csv
  done
  rm -f "$file"
done
mv *.sql $ICNPJ/200.152.38.155/sql/

