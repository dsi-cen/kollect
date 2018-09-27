SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = import, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE import.fiche
(
  idor integer NOT NULL,
  idfiche integer,
  idcoord integer,
  CONSTRAINT fiche_pkey PRIMARY KEY (idor)
);
CREATE TABLE import.impobser
(
  idobseror integer NOT NULL,
  nom character varying(100),
  prenom character varying(100),
  idobser integer,
  CONSTRAINT impobser_pkey PRIMARY KEY (idobseror)
);
CREATE TABLE import.histo
(
  id serial NOT NULL,
  dateimport date,
  idm smallint,
  idobsdeb integer,
  idobsfin integer,
  descri text,
  CONSTRAINT histo_pkey PRIMARY KEY (id)
);
CREATE TABLE import.verifcdnom
(
  nom text
);