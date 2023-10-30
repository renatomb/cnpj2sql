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
    echo "Convertendo condificacao de $arquivo:" && iconv -f iso-8859-1 -t utf-8 "$arquivo" > "$arquivo.csv" && rm -f "$arquivo"  && echo "Processando empresas $arquivo.csv:"  && php empresas.php $arquivo && rm -f empresas.csv && mv $arquivo.sql $ICNPJ/200.152.38.155/sql/ &
  done
  rm -f "$file"
done

for file in $ICNPJ/200.152.38.155/zip/Estabelecimentos*.zip; do
  unzip "$file"
  for arquivo in *ESTABELE; do
    echo "Convertendo condificacao de $arquivo:" && iconv -f iso-8859-1 -t utf-8 "$arquivo" > "$arquivo.csv" && rm -f $arquivo && echo "Processando estabelecimentos $arquivo.csv:" && php estabelecimentos.php $arquivo && rm -f $arquivo.csv && mv $arquivo.sql $ICNPJ/200.152.38.155/sql/ &
  done
  rm -f "$file"
done

for file in $ICNPJ/200.152.38.155/zip/Socios*.zip; do
  unzip "$file"
  for arquivo in *SOCIOCSV; do
    echo "Convertendo condificacao de $arquivo:" && iconv -f iso-8859-1 -t utf-8 "$arquivo" > "$arquivo.csv" && rm -f $arquivo && echo "Processando socios $arquivo.csv:" && php socio.php $arquivo && rm -f $arquivo.csv && mv $arquivo.sql $ICNPJ/200.152.38.155/sql/ &
  done
  rm -f "$file"
done

for file in $ICNPJ/200.152.38.155/zip/Simple*.zip; do
  unzip "$file"
  for arquivo in *SIMPLES*; do
    # renomear arquivos mantendo somente o que tem apos o ultimo ponto no nome do arquivo
    novo=$(echo $arquivo | sed 's/.*\.//')
    novonome=SIMPLESCSV-$novo
    mv $arquivo $novonome
  done
  for arquivo in SIMPLESCSV*; do
    echo "Convertendo condificacao de $arquivo:" && iconv -f iso-8859-1 -t utf-8 "$arquivo" > "$arquivo.csv" && rm -f $arquivo && echo "Processando simples $arquivo.csv:" && php simples.php $arquivo && rm -f $arquivo.csv && mv $arquivo.sql $ICNPJ/200.152.38.155/sql/ &
  done  
  rm -f "$file"
done

for file in $ICNPJ/200.152.38.155/zip/*s.zip; do
  unzip "$file"
  rm -f "$file"
done

function auxiliar() {
  local arquivo=$1
  echo "Convertendo condificacao para $arquivo.csv" && iconv -f iso-8859-1 -t utf-8 "$arquivo" > "$arquivo.csv" && rm -f $arquivo && echo "Processando auxiliar $arquivo.csv:" && php id-nome.php $arquivo && rm -f $arquivo.csv && mv $arquivo.sql $ICNPJ/200.152.38.155/sql/ &
}

echo "Renomeando arquivos das tabelas auxiliares"
mv *CNAECSV CNAECSV && auxiliar CNAECSV &
mv *MUNICCSV MUNICCSV && auxiliar MUNICCSV &
mv *MOTICSV MOTICSV && auxiliar MOTICSV &
mv *NATJUCSV NATJUCSV && auxiliar NATJUCSV &
mv *PAISCSV PAISCSV && auxiliar PAISCSV &
mv *QUALSCSV QUALSCSV && auxiliar QUALSCSV &