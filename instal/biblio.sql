SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = biblio, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE auteurs
(
  idauteur serial NOT NULL,
  nom character varying(100),
  prenom character varying(100),
  prenomab character varying(30),
  CONSTRAINT auteurs_pkey PRIMARY KEY (idauteur)
);
CREATE TABLE biblio
(
  idbiblio serial NOT NULL,
  titre text,
  idauteur integer,
  typep character varying(15),
  publi text,
  annee character varying(15),
  tome character varying(40),
  fascicule character varying(20),
  page character varying(20),
  resume text,
  plusauteur character varying(3),
  datesaisie date,
  isbn character varying(20),
  CONSTRAINT biblio_pkey PRIMARY KEY (idbiblio)
);
CREATE TABLE bibliocom
(
  idcom serial NOT NULL,
  idbiblio integer,
  codecom character varying(5),
  CONSTRAINT bibliocom_pkey PRIMARY KEY (idcom)
);
CREATE TABLE bibliofiche
(
  idbf serial NOT NULL,
  idbiblio integer,
  idfiche integer,
  CONSTRAINT bibliofiche_pkey PRIMARY KEY (idbf)
);
CREATE TABLE bibliomc
(
  idmot serial NOT NULL,
  idbiblio integer,
  idmc smallint,
  CONSTRAINT bibliomc_pkey PRIMARY KEY (idmot)
);
CREATE TABLE biblioobserva
(
  idbiblio integer NOT NULL,
  observa character varying(10),
  CONSTRAINT biblioobserva_pkey PRIMARY KEY (idbiblio)
);
CREATE TABLE bibliotaxon
(
  idtax serial NOT NULL,
  idbiblio integer,
  cdnom integer,
  CONSTRAINT bibliotaxon_pkey PRIMARY KEY (idtax)
);
CREATE TABLE lienexterne
(
  idbiblio integer NOT NULL,
  url text,
  taille character varying(10),
  CONSTRAINT lienexterne_pkey PRIMARY KEY (idbiblio)
);
CREATE TABLE motcle
(
  idmc serial NOT NULL,
  mot character varying(40),
  CONSTRAINT motcle_pkey PRIMARY KEY (idmc)
);
CREATE TABLE plusauteur
(
  idplus serial NOT NULL,
  idbiblio integer,
  idauteur integer,
  CONSTRAINT plusauteur_pkey PRIMARY KEY (idplus)
);
CREATE TABLE suivisaisie
(
  idbiblio integer NOT NULL,
  idm integer,
  nom text,
  CONSTRAINT suivisaisie_pkey PRIMARY KEY (idbiblio)
);