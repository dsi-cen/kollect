SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = actu, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE actu
(
  idactu serial NOT NULL,
  titre text,
  soustitre text,
  actu text,
  tag character varying(100),
  theme character varying(50),
  datecreation date,
  url text,
  idauteur integer,
  visible smallint,
  compte integer,
  CONSTRAINT actu_pkey PRIMARY KEY (idactu)
);
CREATE TABLE docactu
(
  iddoc serial NOT NULL,
  idactu smallint,
  nomdoc character varying(100),
  CONSTRAINT docactu_pkey PRIMARY KEY (iddoc)
);
CREATE TABLE photoactu
(
  idphoto serial NOT NULL,
  idactu smallint,
  nom character varying(100),
  auteur character varying(100),
  info character varying(100),
  CONSTRAINT photoactu_pkey PRIMARY KEY (idphoto)
);
CREATE TABLE tag
(
  idtag serial NOT NULL,
  tag character varying(30),
  CONSTRAINT tag_pkey PRIMARY KEY (idtag)
);