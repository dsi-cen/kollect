SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = obs_historique, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE obs_historique.histo_aves
(   
    type_operation text COLLATE pg_catalog."default",
    date_operation timestamp without time zone NOT NULL,
    utilisateur text COLLATE pg_catalog."default" NOT NULL,
    idaves integer NOT NULL,
    idobs integer,
    code smallint,
    stade smallint,
    CONSTRAINT histo_aves_pkey PRIMARY KEY (date_operation, utilisateur,idaves)
);

CREATE FUNCTION obs_historique.alimente_histo_aves() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_aves SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_aves SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_aves SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_aves
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.aves
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_aves();