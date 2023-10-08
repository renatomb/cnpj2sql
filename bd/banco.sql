-- MySQL Script generated by MySQL Workbench
-- Sun Oct  8 15:55:14 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema cnpj
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema cnpj
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `cnpj` DEFAULT CHARACTER SET utf8mb4 ;
USE `cnpj` ;

-- -----------------------------------------------------
-- Table `cnpj`.`motivos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`motivos` (
  `id` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `nome` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`municipios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`municipios` (
  `id` SMALLINT(4) UNSIGNED ZEROFILL NOT NULL,
  `nome` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`naturezas_juridicas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`naturezas_juridicas` (
  `id` SMALLINT(4) UNSIGNED ZEROFILL NOT NULL,
  `nome` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`paises`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`paises` (
  `id` SMALLINT(3) UNSIGNED ZEROFILL NOT NULL,
  `nome` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`qualificacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`qualificacoes` (
  `id` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `nome` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`empresas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`empresas` (
  `cnpj_basico` INT(8) UNSIGNED ZEROFILL NOT NULL,
  `razao_social` VARCHAR(150) NOT NULL,
  `natureza_juridica` SMALLINT(4) UNSIGNED ZEROFILL NOT NULL,
  `qualif_juridica` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `capital_social` DECIMAL(20,2) NULL DEFAULT NULL,
  `porte` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `ente_federativo_responsavel` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`cnpj_basico`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`estabelecimentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`estabelecimentos` (
  `cnpj_basico` INT(8) UNSIGNED ZEROFILL NOT NULL,
  `cnpj_id` SMALLINT(4) UNSIGNED ZEROFILL NOT NULL,
  `cnpj_dv` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `matriz_filial` TINYINT(1) UNSIGNED ZEROFILL NOT NULL,
  `nome_fantasia` VARCHAR(150) NULL DEFAULT NULL,
  `situacao_cadastral` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `data_situacao_cadastral` DATE NOT NULL,
  `motivo_situacao_cadastral` TINYINT(2) UNSIGNED ZEROFILL NOT NULL,
  `cidade_exterior` VARCHAR(150) NULL DEFAULT NULL,
  `cod_pais` SMALLINT(3) NULL DEFAULT NULL,
  `data_inicio_ativ` DATE NOT NULL,
  `cnae_fiscal` INT(7) UNSIGNED ZEROFILL NOT NULL,
  `cnae_secundario` TEXT NULL DEFAULT NULL,
  `tipo_logradouro` VARCHAR(30) NULL DEFAULT NULL,
  `logradouro` VARCHAR(150) NULL DEFAULT NULL,
  `numero` VARCHAR(6) NULL DEFAULT NULL,
  `complemento` VARCHAR(200) NULL DEFAULT NULL,
  `bairro` VARCHAR(100) NULL DEFAULT NULL,
  `cep` INT(8) UNSIGNED ZEROFILL NULL DEFAULT NULL,
  `uf` CHAR(2) NULL DEFAULT NULL,
  `cod_municipio` SMALLINT(4) UNSIGNED ZEROFILL NOT NULL,
  `ddd_1` TINYINT(2) UNSIGNED NULL DEFAULT NULL,
  `telefone_1` INT(9) UNSIGNED NULL DEFAULT NULL,
  `ddd_2` TINYINT(2) UNSIGNED NULL DEFAULT NULL,
  `telefone_2` INT(9) UNSIGNED NULL DEFAULT NULL,
  `ddd_fax` TINYINT(2) UNSIGNED NULL DEFAULT NULL,
  `num_fax` INT(9) UNSIGNED NULL DEFAULT NULL,
  `email` VARCHAR(150) NULL DEFAULT NULL,
  INDEX `fk_estabe_empresas_idx` (`cnpj_basico` ASC) VISIBLE,
  PRIMARY KEY (`cnpj_basico`, `cnpj_id`, `cnpj_dv`),
  CONSTRAINT `fk_estabe_empresas`
    FOREIGN KEY (`cnpj_basico`)
    REFERENCES `cnpj`.`empresas` (`cnpj_basico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`socio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`socio` (
  `cnpj_basico` INT(8) UNSIGNED ZEROFILL NOT NULL,
  `identificador_socio` TINYINT(1) UNSIGNED NOT NULL,
  `nome_socio` VARCHAR(150) NULL DEFAULT NULL,
  `cnpj_socio` BIGINT(14) UNSIGNED ZEROFILL NULL DEFAULT NULL,
  `cpf_socio_mascarado` MEDIUMINT(6) UNSIGNED ZEROFILL NULL DEFAULT NULL,
  `cod_qualificacao_socio` TINYINT(2) UNSIGNED NOT NULL,
  `data_entrada_sociedade` DATE NOT NULL,
  `perc_capital_social` SMALLINT(5) NULL DEFAULT NULL,
  `cpf_representante_legal` MEDIUMINT(6) NULL DEFAULT NULL,
  `nome_representante_legal` VARCHAR(150) NULL DEFAULT NULL,
  `cod_qualificacao_representante_legal` TINYINT(2) UNSIGNED ZEROFILL NULL DEFAULT NULL,
  `desconhecido` CHAR(1) NULL DEFAULT NULL,
  INDEX `fk_socio_empresas1_idx` (`cnpj_basico` ASC) VISIBLE,
  CONSTRAINT `fk_socio_empresas1`
    FOREIGN KEY (`cnpj_basico`)
    REFERENCES `cnpj`.`empresas` (`cnpj_basico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `cnpj`.`cnaes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cnpj`.`cnaes` (
  `id` MEDIUMINT(7) UNSIGNED ZEROFILL NOT NULL,
  `nome` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
