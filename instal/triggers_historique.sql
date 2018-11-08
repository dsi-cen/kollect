SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = obs_historique, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE obs_historique.histo_obs
(
    type_operation text COLLATE pg_catalog."default",
    date_operation timestamp without time zone NOT NULL,
    utilisateur text COLLATE pg_catalog."default" NOT NULL,
    idobs integer NOT NULL,
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

    CONSTRAINT histo_obs_pkey PRIMARY KEY (date_operation, utilisateur, idobs)
);

CREATE TABLE obs_historique.histo_obs_synthese
(
    idobs integer NOT NULL,
    datetime_insert timestamp without time zone NOT NULL,
    date_insert date NOT NULL,
    datetime_update timestamp without time zone,
    date_update date,
    table_update text,
    CONSTRAINT histo_obs_synthese_pkey PRIMARY KEY (idobs)
);

CREATE FUNCTION obs_historique.alimente_histo_obs() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_obs SELECT 'DELETE', now(), user_login, OLD.*;
                                    DELETE FROM obs_historique.histo_obs_synthese WHERE idobs = OLD.idobs;
                                    RETURN OLD;
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_obs SELECT 'UPDATE', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obs' WHERE idobs = NEW.idobs;
                                        RETURN NEW; 
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_obs SELECT 'INSERT', now(), user_login, NEW.*;
                                        INSERT INTO obs_historique.histo_obs_synthese VALUES (NEW.idobs, now(),now(),NULL,NULL,NULL);
										RETURN NEW;
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	


CREATE TRIGGER declenche_alimente_histo_obs
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.obs
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_obs();


CREATE TABLE obs_historique.histo_ligneobs
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,  
  idligne integer,
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
  CONSTRAINT histo_ligneobs_pkey PRIMARY KEY (date_operation, utilisateur, idligne)
);

CREATE FUNCTION obs_historique.alimente_histo_ligneobs() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_ligneobs SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD;
			    ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_ligneobs SELECT 'UPDATE', now(), user_login, NEW.*;
                                            UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.ligneobs' WHERE idobs = NEW.idobs; 
                                            RETURN NEW; 
			    ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_ligneobs SELECT 'INSERT', now(), user_login, NEW.*;
                                            UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.ligneobs' 
                                            WHERE idobs = NEW.idobs AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                            RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	


CREATE TRIGGER declenche_alimente_histo_ligneobs
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.ligneobs
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_ligneobs();



CREATE TABLE obs_historique.histo_obscoll
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,  
  idcol integer NOT NULL,
  idobs integer,
  iddetcol smallint,
  iddetgen smallint,
  codegen character varying(20),
  sexe character(1),
  idprep smallint,
  typedet character varying(20),
  stade smallint,
  CONSTRAINT histo_obscoll_pkey PRIMARY KEY (date_operation, utilisateur,idcol)
);

CREATE FUNCTION obs_historique.alimente_histo_obscoll() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_obscoll SELECT 'DELETE', now(), user_login, OLD.*;
                                    UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obscoll' WHERE idobs = OLD.idobs;
                                    RETURN OLD;
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_obscoll SELECT 'UPDATE', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obscoll' WHERE idobs = NEW.idobs; 
                                        RETURN NEW; 
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_obscoll SELECT 'INSERT', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obscoll' 
                                        WHERE idobs = NEW.idobs AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                        RETURN NEW; 
			END IF;
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_histo_obscoll
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.obscoll
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_obscoll();


CREATE TABLE obs_historique.histo_fiche
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,  
  idfiche integer,
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
  CONSTRAINT histo_fiche_pkey PRIMARY KEY (date_operation, utilisateur, idfiche)
);

CREATE FUNCTION obs_historique.alimente_histo_fiche() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_fiche SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
			    ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_fiche SELECT 'UPDATE', now(), user_login, NEW.*;
                                            UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.fiche' 
                                            WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = NEW.idfiche);
                                            RETURN NEW;  
    		    ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_fiche SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	


CREATE TRIGGER declenche_alimente_histo_fiche
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.fiche
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_fiche();



CREATE TABLE obs_historique.histo_fichesup
( 
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL, 
  idfiche integer NOT NULL,
  hdebut time without time zone,
  hfin time without time zone,
  meteo character varying(150),
  tempdebut smallint,
  tempfin smallint,
  CONSTRAINT histo_fichesup_pkey PRIMARY KEY (date_operation, utilisateur,idfiche)
);

CREATE FUNCTION obs_historique.alimente_histo_fichesup() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_fichesup SELECT 'DELETE', now(), user_login, OLD.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.fichesup' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = OLD.idfiche);
                                        RETURN OLD; 

			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_fichesup SELECT 'UPDATE', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.fichesup' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = NEW.idfiche);
                                        RETURN NEW;  
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_fichesup SELECT 'INSERT', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.fichesup' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = NEW.idfiche) AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                        RETURN NEW; 
            END IF;
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_histo_fichesup
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.fichesup
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_fichesup();

    CREATE TABLE obs_historique.histo_coordonnee
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,
  idcoord integer,
  x integer,
  y integer,
  altitude smallint,
  lat real,
  lng real,
  codel93 character varying(10),
  utm character varying(10),
  utm1 character varying(10),
  codel935 character varying(10),
  CONSTRAINT histo_coordonnee_pkey PRIMARY KEY (date_operation, utilisateur, idcoord)
);

CREATE FUNCTION obs_historique.alimente_histo_coordonnee() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_coordonnee SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_coordonnee SELECT 'UPDATE', now(), user_login, NEW.*; 
                                     UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.coordonnee' 
                                     WHERE idobs IN (SELECT idobs FROM obs.obs JOIN obs.fiche ON fiche.idfiche = obs.idfiche WHERE fiche.idcoord = NEW.idcoord);
                                     RETURN NEW;  

			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_coordonnee SELECT 'INSERT', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.coordonnee' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs JOIN obs.fiche ON fiche.idfiche = obs.idfiche WHERE fiche.idcoord = NEW.idcoord)
                                        AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                        RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_histo_coordonnee
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.coordonnee
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_coordonnee();


CREATE TABLE obs_historique.histo_coordgeo
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,   
  idcoord integer NOT NULL,
  geo text,
  poly polygon,
  CONSTRAINT histo_coordgeo_pkey PRIMARY KEY (date_operation, utilisateur, idcoord)
);

CREATE FUNCTION obs_historique.alimente_histo_coordgeo() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_coordgeo SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_coordgeo SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_coordgeo SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_coordgeo
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.coordgeo
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_coordgeo();


 CREATE TABLE obs_historique.histo_obshab
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,
  idobs integer NOT NULL,
  cdhab integer,
  cdnom integer,
  CONSTRAINT histo_obshab_pkey PRIMARY KEY (date_operation, utilisateur,idobs)
);

CREATE FUNCTION obs_historique.alimente_histo_obshab() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

		    IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_obshab SELECT 'DELETE', now(), user_login, OLD.*;
                                    UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obshab' WHERE idobs = OLD.idobs;
                                    RETURN OLD;
			    ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_obshab SELECT 'UPDATE', now(), user_login, NEW.*;
                                            UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obshab' WHERE idobs = NEW.idobs;
                                            RETURN NEW; 
			    ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_obshab SELECT 'INSERT', now(), user_login, NEW.*;
                                            UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obshab' 
                                            WHERE idobs = NEW.idobs AND date_trunc('second',datetime_insert) != date_trunc('second',now()); 
                                            RETURN NEW; 
			END IF;
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_obshab
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.obshab
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_obshab();   

CREATE TABLE obs_historique.histo_obsmort
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL, 
  idmort integer NOT NULL,
  idobs integer,
  mort smallint,
  stade smallint,
  CONSTRAINT histo_obsmort_pkey PRIMARY KEY (date_operation, utilisateur,idobs)
);

CREATE FUNCTION obs_historique.alimente_histo_obsmort() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_obsmort SELECT 'DELETE', now(), user_login, OLD.*;
                                    UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obsmort' WHERE idobs = OLD.idobs;
                                    RETURN OLD;
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_obsmort SELECT 'UPDATE', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obsmort' WHERE idobs = NEW.idobs;
                                        RETURN NEW;
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_obsmort SELECT 'INSERT', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obsmort' 
                                        WHERE idobs = NEW.idobs AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                        RETURN NEW;
			END IF;  
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_obsmort
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.obsmort
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_obsmort();

CREATE TABLE obs_historique.histo_obsplte
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,
  idplte integer NOT NULL,
  idobs integer,
  nb integer,
  cdnom integer,
  stade smallint,
  CONSTRAINT histo_obsplte_pkey PRIMARY KEY (date_operation, utilisateur,idobs)
);

CREATE FUNCTION obs_historique.alimente_histo_obsplte() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_obsplte SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obsplte' WHERE idobs = OLD.idobs; 
                                        RETURN OLD; 
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_obsplte SELECT 'UPDATE', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(),datetime_update = now(), table_update = 'obs.obsplte' WHERE idobs = NEW.idobs;
                                        RETURN NEW;
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_obsplte SELECT 'INSERT', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.obsplte' 
                                        WHERE idobs = NEW.idobs AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                        RETURN NEW;
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_obsplte
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.obsplte
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_obsplte();

CREATE TABLE obs_historique.histo_plusobser
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,
  idplus integer NOT NULL,
  idfiche integer,
  idobser integer,
  CONSTRAINT histo_plusobser_pkey PRIMARY KEY (date_operation, utilisateur,idplus)
);

CREATE FUNCTION obs_historique.alimente_histo_plusobser() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_plusobser SELECT 'DELETE', now(), user_login, OLD.*; 
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.plusobser' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = OLD.idfiche);
                                        RETURN OLD; 
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_plusobser SELECT 'UPDATE', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.plusobser' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = NEW.idfiche);
                                        RETURN NEW;  
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_plusobser SELECT 'INSERT', now(), user_login, NEW.*;
                                        UPDATE obs_historique.histo_obs_synthese SET date_update = now(), datetime_update = now(), table_update = 'obs.plusobser' 
                                        WHERE idobs IN (SELECT idobs FROM obs.obs WHERE obs.idfiche = NEW.idfiche) AND date_trunc('second',datetime_insert) != date_trunc('second',now());
                                        RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_plusobser
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.plusobser
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_plusobser();

CREATE TABLE obs_historique.histo_site
(
  type_operation text COLLATE pg_catalog."default",
  date_operation timestamp without time zone NOT NULL,
  utilisateur text COLLATE pg_catalog."default" NOT NULL,
  idsite integer NOT NULL,
  idcoord integer,
  codecom character varying(5),
  site character varying(100),
  rqsite text,
  CONSTRAINT histo_site_pkey PRIMARY KEY (date_operation, utilisateur,idsite)
);

CREATE FUNCTION obs_historique.alimente_histo_site() RETURNS trigger AS 
	$BODY$
        declare user_login integer;
		BEGIN
            user_login = outils.get_user();

			IF (TG_OP = 'DELETE') THEN INSERT INTO obs_historique.histo_site SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
			ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO obs_historique.histo_site SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
			ELSIF (TG_OP = 'INSERT') THEN INSERT INTO obs_historique.histo_site SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
			END IF; 
			RETURN NULL; 
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_histo_site
    AFTER INSERT OR DELETE OR UPDATE 
    ON obs.site
    FOR EACH ROW
    EXECUTE PROCEDURE obs_historique.alimente_histo_site();
	
	CREATE FUNCTION referentiel.alimente_observateur() RETURNS trigger AS 
	$BODY$

    declare id_observateur integer;

		BEGIN
            
            SELECT idobser FROM referentiel.observateur WHERE observateur.nom = NEW.nom AND observateur.prenom = NEW.prenom INTO id_observateur;

            IF id_observateur IS NULL THEN
		        INSERT INTO referentiel.observateur (observateur, nom, prenom, idm) VALUES (NEW.nom || ' ' || NEW.prenom::varchar(200),NEW.nom, NEW.prenom, NEW.idmembre); 
                RETURN NEW; 
            ELSE
                UPDATE referentiel.observateur SET idm = NEW.idmembre WHERE idobser = id_observateur;
                RETURN NEW;
            END IF;
		END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_observateur 
	AFTER INSERT
	ON site.membre
	FOR EACH ROW
	EXECUTE PROCEDURE referentiel.alimente_observateur();