SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = site, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

CREATE TABLE site.article
(
  idarticle serial NOT NULL,
  typear character varying(15),
  titre text,
  soustitre text,
  article text,
  CONSTRAINT article_pkey PRIMARY KEY (idarticle)
);

CREATE TABLE site.comdet
(
  idcom serial NOT NULL,
  idpdet integer,
  idm integer,
  commentaire text,
  datecom timestamp without time zone,
  CONSTRAINT comdet_pkey PRIMARY KEY (idcom)
);

CREATE TABLE site.comobs
(
  idcom serial NOT NULL,
  idobs integer,
  idm integer,
  commentaire text,
  datecom timestamp without time zone,
  CONSTRAINT comobs_pkey PRIMARY KEY (idcom)
);

CREATE TABLE site.determination
(
  iddet serial NOT NULL,
  idpdet integer,
  idm integer,
  cdnom integer,
  datedet date,
  ndet character varying(3),
  CONSTRAINT determination_pkey PRIMARY KEY (iddet)
);

CREATE TABLE site.liencom
(
  idobs integer NOT NULL,
  nbcom smallint,
  CONSTRAINT liencom_pkey PRIMARY KEY (idobs)
);

CREATE TABLE site.modif
(
  idmodif serial NOT NULL,
  typeid character varying(15),
  numid integer,
  typemodif text,
  modif text,
  datemodif timestamp without time zone,
  idmembre integer,
  CONSTRAINT modif_pkey PRIMARY KEY (idmodif)
);

CREATE TABLE site.notif
(
  idnotif serial NOT NULL,
  idm integer,
  type character varying(10),
  idtype integer,
  CONSTRAINT notif_pkey PRIMARY KEY (idnotif)
);

CREATE TABLE site.photo
(
  idphoto serial NOT NULL,
  cdnom integer,
  idobser integer,
  datephoto date,
  codecom character varying(5),
  stade smallint,
  nomphoto character varying(50),
  datesaisie timestamp without time zone,
  sexe character varying(3),
  observatoire character varying(10),
  idobs integer,
  ordre smallint,
  CONSTRAINT photo_pkey PRIMARY KEY (idphoto)
);

CREATE TABLE site.photodet
(
  idpdet serial NOT NULL,
  idm integer,
  codecom character varying(5),
  datephoto date,
  datesaisie timestamp without time zone,
  nomphoto character varying(20),
  nomini character varying(50),
  rq text,
  vali character varying(3),
  idobs integer,
  typef character varying(5),
  observa character varying(10),
  CONSTRAINT photodet_pkey PRIMARY KEY (idpdet)
);

CREATE TABLE site.prefmembre
(
  idmembre integer NOT NULL,
  obser character varying(10),
  latin character varying(6),
  floutage smallint,
  contact character(3),
  couche character varying(10),
  typedon character varying(3),
  org smallint,
  CONSTRAINT prefmembre_pkey PRIMARY KEY (idmembre)
);

CREATE TABLE site.son
(
  idson serial NOT NULL,
  cdnom integer,
  idobser integer,
  nomson character varying(50),
  datesaisie timestamp without time zone,
  idobs integer,
  descri character varying(100),
  dateson date,
  CONSTRAINT son_pkey PRIMARY KEY (idson)
);

CREATE TABLE site.tuto
(
  idtuto serial NOT NULL,
  nomdoc character varying(50),
  format character varying(10),
  descri character varying(150),
  CONSTRAINT tuto_pkey PRIMARY KEY (idtuto)
);

CREATE TABLE site.utilisateur
(
  ip character varying(15),
  "timestamp" integer,
  idm integer,
  agent text,
  referer text,
  uri text
);

CREATE TABLE site.validateur
(
  idmembre integer NOT NULL,
  discipline text,
  CONSTRAINT validateur_pkey PRIMARY KEY (idmembre)
);

CREATE TABLE site.virtuel
(
  idvirt serial NOT NULL,
  idmembre integer,
  typeid character varying(15),
  idsession integer,
  nomvirtuel character varying(200),
  datevirt timestamp without time zone,
  CONSTRAINT virtuel_pkey PRIMARY KEY (idvirt)
);

CREATE TABLE site.membre (
						idmembre serial NOT NULL,
						nom character varying(50),
						prenom character varying(20),
						droits smallint,
						motpasse text,
						mail character varying(50),
						derniereconnection timestamp without time zone,
						gestionobs text,
						actif smallint,
						mdpo boolean,
						ticket text,
						CONSTRAINT membre_pkey PRIMARY KEY (idmembre));