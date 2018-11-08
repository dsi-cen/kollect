SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = obs, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

CREATE TABLE obs.coordgeo
(
  idcoord integer NOT NULL,
  geo text,
  poly polygon,
  CONSTRAINT coordgeo_pkey PRIMARY KEY (idcoord)
);

CREATE TABLE obs.coordonnee
(
  idcoord serial NOT NULL,
  x integer,
  y integer,
  altitude smallint,
  lat real,
  lng real,
  codel93 character varying(10),
  utm character varying(10),
  utm1 character varying(10),
  codel935 character varying(10),
  CONSTRAINT coordonnee_pkey PRIMARY KEY (idcoord)
);

CREATE TABLE obs.fiche
(
  idfiche serial NOT NULL,
  iddep character(2),
  codecom character varying(5),
  idsite integer,
  date1 date,
  date2 date,
  idobser smallint,
  decade character varying(3),
  localisation smallint,
  idcoord integer,
  floutage smallint,
  plusobser character varying(3),
  typedon character varying(2),
  source character varying(3),
  idorg smallint,
  idetude smallint,
  idpreci smallint,
  CONSTRAINT fiche_pkey PRIMARY KEY (idfiche),
  CONSTRAINT fiche_idpreci_fkey FOREIGN KEY (idpreci) REFERENCES referentiel.coordprecision (idpreci)
);

CREATE TABLE obs.fichesup
(
  idfiche integer NOT NULL,
  hdebut time without time zone,
  hfin time without time zone,
  meteo character varying(150),
  tempdebut smallint,
  tempfin smallint,
  CONSTRAINT fichesup_pkey PRIMARY KEY (idfiche)
);

CREATE TABLE obs.identif
(
  idligne integer NOT NULL,
  idobs integer,
  idfiche integer,
  idorigine integer,
  permid text,
  dates date,
  CONSTRAINT identif_pkey PRIMARY KEY (idligne)
);

CREATE TABLE obs.ligneobs
(
  idligne serial NOT NULL,
  idobs integer,
  stade smallint,
  ndiff smallint,
  male smallint,
  femelle smallint,
  denom character varying(3),
  idetatbio smallint,
  idmethode smallint,
  idpros smallint,
  idstbio smallint,
  idcomp smallint,
  nbmin integer,
  nbmax integer,
  sexe smallint,
  tdenom character varying(4),
  CONSTRAINT ligneobs_pkey PRIMARY KEY (idligne)
);

CREATE TABLE obs.obs
(
  idobs serial NOT NULL,
  idfiche integer,
  cdnom integer,
  cdref integer,
  nom_cite text,
  iddet smallint,
  nb smallint,
  rqobs text,
  validation smallint,
  datesaisie date,
  observa character varying(10),
  statutobs character varying(2),
  idprotocole smallint,
  idmor integer,
  CONSTRAINT obs_pkey PRIMARY KEY (idobs)
);

CREATE TABLE obs.obscoll
(
  idcol serial NOT NULL,
  idobs integer,
  iddetcol smallint,
  iddetgen smallint,
  codegen character varying(20),
  sexe character(1),
  idprep smallint,
  typedet character varying(20),
  stade smallint,
  CONSTRAINT obscoll_pkey PRIMARY KEY (idcol)
);

CREATE TABLE obs.obshab
(
  idobs integer NOT NULL,
  cdhab integer,
  cdnom integer,
  CONSTRAINT obshab_pkey PRIMARY KEY (idobs)
);

CREATE TABLE obs.obsmort
(
  idmort serial NOT NULL,
  idobs integer,
  mort smallint,
  stade smallint,
  CONSTRAINT obsmort_pkey PRIMARY KEY (idmort)
);

CREATE TABLE obs.obsplte
(
  idplte serial NOT NULL,
  idobs integer,
  nb integer,
  cdnom integer,
  stade smallint,
  CONSTRAINT obsplte_pkey PRIMARY KEY (idplte)
);

CREATE TABLE obs.plusobser
(
  idplus serial NOT NULL,
  idfiche integer,
  idobser integer,
  CONSTRAINT plusobser_pkey PRIMARY KEY (idplus)
);

CREATE TABLE obs.site
(
  idsite serial NOT NULL,
  idcoord integer,
  codecom character varying(5),
  site character varying(100),
  rqsite text,
  CONSTRAINT site_pkey PRIMARY KEY (idsite)
);