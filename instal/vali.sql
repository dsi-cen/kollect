SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = vali, pg_catalog;

CREATE TABLE comvali
(
  idcom serial NOT NULL,
  idobs integer,
  idm integer,
  commentaire text,
  datecom timestamp without time zone,
  CONSTRAINT comvali_pkey PRIMARY KEY (idcom)
);
CREATE TABLE critere
(
  cdnom integer NOT NULL,
  stade character varying(30),
  photo character(3),
  son character(3),
  loupe character(3),
  bino character(3),
  observa character varying(10),
  idstade smallint,
  CONSTRAINT critere_pkey PRIMARY KEY (cdnom)
);
CREATE TABLE grille
(
  cdref integer NOT NULL,
  nb bigint,
  codesl93 character varying[],
  decades character varying[],
  obsers smallint[],
  CONSTRAINT grille_pkey PRIMARY KEY (cdref)
);
CREATE TABLE histovali
(
  idhisto serial NOT NULL,
  idobs integer,
  cdnom integer,
  dateval date,
  vali smallint,
  decision text,
  idm integer,
  typevali smallint,
  CONSTRAINT histovali_pkey PRIMARY KEY (idhisto)
);