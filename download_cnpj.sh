#!/bin/bash
# Script para download da base de dados CNPJ disponibilizada
# no portal Dados Abertos da Receita Federal do Brasil. O download
# é realizado através de um mirror utilizando o wget.
#
# Autor: Renato Monteiro Batista
# Data: 10/02/2023
#
# Versão 1.0

# Download (mirror) dos arquivos no portal de dados abertos da Receita Federal
# sudo apg-get install wget2 -y
wget2 --max-threads=999 -N --mirror --convert-links --adjust-extension --page-requisites --no-parent -A zip http://200.152.38.155/CNPJ/
