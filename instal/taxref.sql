SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = referentiel, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE referentiel.taxref (
    cdnom integer NOT NULL,
    cdsup integer,
    cdtaxsup integer,
    cdref integer,
    rang character varying(5),
    nom text,
    auteur text,
    groupe character varying(30),
    classe character varying(30),
    ordre character varying(40),
    famille character varying(40),
    nomvern text,
    statut character(1),
    regne character varying(30)
);

ALTER TABLE ONLY taxref ADD CONSTRAINT taxref_pkey PRIMARY KEY (cdnom);


