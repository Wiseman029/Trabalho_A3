-- ChapeCode Academy — script do banco de dados (MySQL / MariaDB)
-- Importar pelo phpMyAdmin: aba "Importar" > escolher este arquivo > Executar.

CREATE DATABASE IF NOT EXISTS chapecode
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chapecode;

DROP TABLE IF EXISTS orcamentos;
DROP TABLE IF EXISTS contatos;
DROP TABLE IF EXISTS servicos;

CREATE TABLE servicos (
  id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(150) NOT NULL,
  descricao VARCHAR(255) NOT NULL,
  badge     VARCHAR(50)  DEFAULT NULL,
  preco     DECIMAL(10,2) NOT NULL,
  tempo     VARCHAR(60)  NOT NULL,
  dias      VARCHAR(60)  NOT NULL,
  horarios  VARCHAR(60)  NOT NULL,
  imagem    VARCHAR(150) NOT NULL,
  pagina    VARCHAR(60)  NOT NULL,
  ativo     TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orcamentos (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  servico_id    INT UNSIGNED NOT NULL,
  nome          VARCHAR(120) NOT NULL,
  email         VARCHAR(160) NOT NULL,
  telefone      VARCHAR(30)  NOT NULL,
  endereco      VARCHAR(200) NOT NULL,
  cidade        VARCHAR(80)  NOT NULL,
  uf            CHAR(2)      NOT NULL,
  data_desejada DATE         NOT NULL,
  observacoes   TEXT         DEFAULT NULL,
  criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_orc_servico FOREIGN KEY (servico_id) REFERENCES servicos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contatos (
  id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(120) NOT NULL,
  email     VARCHAR(160) NOT NULL,
  mensagem  TEXT         NOT NULL,
  criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO servicos (id,nome,descricao,badge,preco,tempo,dias,horarios,imagem,pagina) VALUES
(1,'Cursos de Programação e Desenvolvimento','Lógica, desenvolvimento web, linguagens e projetos práticos.','Mais procurado',299.00,'Até 40 horas por turma','Segunda a sexta','08h às 20h','img/carousel/SERV1.jpg','servico1.html'),
(2,'Inteligência Artificial e Tecnologias Emergentes','IA, automação e novas tecnologias para criar soluções.','Inovação',349.00,'Até 24 horas por turma','Terças e quintas','08h às 20h','img/carousel/SERV2.jpg','servico2.html'),
(3,'Robótica, IoT e Projetos Tecnológicos','Programação, eletrônica, robótica e IoT aplicadas a protótipos.','Mão na massa',399.00,'Até 30 horas por turma','Quartas e sábados','09h às 18h','img/carousel/SERV3.jpg','servico3.html');
