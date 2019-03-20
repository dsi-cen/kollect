SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = md_obs, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE md_obs.obs_origine_odk
(
    idobs integer NOT NULL,
    id_formulaire_odk character varying(50),
    lib_utilisateur_odk character varying(50),
    id_releve_odk character varying(150),
    nb_obs_releve_odk integer,
    ordre_obs_releve_odk integer,
    debut_formulaire timestamp without time zone,
    fin_formulaire timestamp without time zone,
    remarques text,
    CONSTRAINT passage_origine_odk_pkey PRIMARY KEY (idobs),
    CONSTRAINT passage_origine_odk_idobs_fkey FOREIGN KEY (idobs) REFERENCES obs.obs (idobs) 
);

COMMENT ON TABLE md_obs.obs_origine_odk IS 'Table stockant les informations issues d''ODK si l''observation provient d''un relevé terrain sur terminal Androïd avec l''application ODK Collect';

CREATE TABLE md_obs.ref_source_origine
(
    id_source_origine serial,
    lib_source_origine character varying(150) NOT NULL,
    md_source_origine text,
    CONSTRAINT ref_source_origine_pkey PRIMARY KEY (id_source_origine)
);

COMMENT ON TABLE md_obs.ref_source_origine IS 'Référentiel des sources';

INSERT INTO md_obs.ref_source_origine VALUES
(1,'Indéterminé','Source indéterminée'),
(2,'Tableur','Fichier personnel de type tableur'),
(3,'SICEN (CEN Aquitaine)','Base de données SICEN du CEN Aquitaine'),
(4,'STKollect (CEN Aquitaine)','Base de données thématique du CEN Aquitaine : STKollect'),
(5,'ChiroKollect (CEN Aquitaine)','Base de données thématique du CEN Aquitaine : ChiroKollect'),
(6,'Faune Aquitaine','Base de données Faune Aquitaine de la LPO Aquitaine'),
(7,'Faune France','Base de données Faune France de la LPO France'),
(8,'ObsOcc (Parc National des Pyrénées)','Base de données du Parc National des Pyrénées : ObsOcc')
(9,'Observation.org','Base de données en ligne Observation.org'),
(10,'Fichier SIG','Fichier personnel SIG'),
(11,'RECHAU (CEN Aquitaine)','Base de données thématique du CEN Aquitaine : RECHAU');

CREATE TABLE md_obs.obs_source_ext
(
    id_obs_source_ext serial,
    idobs integer NOT NULL,
    idligne integer,
    id_source_origine integer NOT NULL,
    est_thematique boolean NOT NULL,
    nom_source character varying(80),
    type_source character varying(80),
    path_source character varying(250),
    remarques text,
    CONSTRAINT obs_source_ext_pkey PRIMARY KEY (id_obs_source_ext),
    CONSTRAINT obs_source_ext_idobs_fkey FOREIGN KEY (idobs) REFERENCES obs.obs (idobs),
    CONSTRAINT obs_source_ext_idligne_fkey FOREIGN KEY (idligne) REFERENCES obs.ligneobs (idligne),
    CONSTRAINT obs_source_ext_id_source_origine_fkey FOREIGN KEY (id_source_origine) REFERENCES md_obs.ref_source_origine (id_source_origine)
);

COMMENT ON TABLE md_obs.obs_source_ext IS 'Table stockant les informations de la source';

CREATE TABLE md_obs.obs_origine_ext
(
    id_obs_origine_ext serial,
    idobs integer NOT NULL,
    idligne integer,
    idobs_origine character varying(250) NOT NULL,
    CONSTRAINT obs_origine_ext_pkey PRIMARY KEY (id_obs_origine_ext),
    CONSTRAINT obs_origine_ext_idobs_fkey FOREIGN KEY (idobs) REFERENCES obs.obs (idobs),
    CONSTRAINT obs_origine_ext_idligne_fkey FOREIGN KEY (idligne) REFERENCES obs.ligneobs (idligne)
);

COMMENT ON TABLE md_obs.obs_origine_ext IS 'Table stockant les informations de l''identifiant de l''observation de la source. Il peut y avoir plusieurs identifiants par ligne';

CREATE TABLE md_obs.ref_type_cible
(
    id_type_cible integer,
    lib_type_cible character varying (50) NOT NULL,
    md_type_cible text,
    CONSTRAINT ref_type_cible_pkey PRIMARY KEY (id_type_cible)
);

COMMENT ON TABLE md_obs.ref_type_cible IS 'Référentiel des cibles pour les exports';

INSERT INTO md_obs.ref_type_cible VALUES
(1,'SINP région','Plateforme régionale du SINP'),
(2,'SINP national','SINP national'),
(3,'Maître d''ouvrage dossier','Maître d''ouvrage commanditaire de la donnée');

CREATE TABLE md_obs.obs_export
(
    id_obs_export serial NOT NULL,
    idobs integer NOT NULL,
    date_export timestamp without time zone NOT NULL,
    id_type_cible integer NOT NULL,
    idorg_cible integer,
    remarques text,
    CONSTRAINT obs_export_pkey PRIMARY KEY (id_obs_export),
    CONSTRAINT obs_export_idobs_fkey FOREIGN KEY (idobs) REFERENCES obs.obs (idobs),
    CONSTRAINT obs_export_id_type_cible_fkey FOREIGN KEY (id_type_cible) REFERENCES  md_obs.ref_type_cible (id_type_cible),
    CONSTRAINT obs_export_idorg_fkey FOREIGN KEY (idorg_cible) REFERENCES  referentiel.organisme (idorg)
);

COMMENT ON TABLE md_obs.obs_export IS 'Table stockant les informations sur les export des données vers l''extérieur';

CREATE FUNCTION md_obs.suppr_ligne_md_obs()
    RETURNS trigger AS
    $BODY$
        BEGIN
            DELETE FROM md_obs.obs_origine_ext WHERE idligne = OLD.idligne;
           	DELETE FROM md_obs.obs_source_ext WHERE idligne = OLD.idligne;
			RETURN OLD;
        END;
	$BODY$
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_suppr_ligne_md_obs
    BEFORE DELETE
    ON obs.ligneobs
    FOR EACH ROW
    EXECUTE PROCEDURE md_obs.suppr_ligne_md_obs();


CREATE FUNCTION md_obs.suppr_obs_md_obs()
    RETURNS trigger AS
    $BODY$
        BEGIN
            DELETE FROM md_obs.obs_origine_odk WHERE idobs = OLD.idobs;
            DELETE FROM md_obs.obs_export WHERE idobs = OLD.idobs;
            DELETE FROM md_obs.obs_origine_ext WHERE idobs = OLD.idobs;
           	DELETE FROM md_obs.obs_source_ext WHERE idobs = OLD.idobs;
			RETURN OLD;
        END;
	$BODY$
	LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_suppr_obs_md_obs
    BEFORE DELETE
    ON obs.obs
    FOR EACH ROW
    EXECUTE PROCEDURE md_obs.suppr_obs_md_obs();




SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = md_obs_historique, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;


CREATE TABLE md_obs_historique.histo_obs_origine_odk
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone NOT NULL,
    utilisateur text NOT NULL,
    idobs integer NOT NULL,
    id_formulaire_odk character varying(50),
    lib_utilisateur_odk character varying(50),
    id_releve_odk character varying(150),
    nb_obs_releve_odk integer,
    ordre_obs_releve_odk integer,
    debut_formulaire timestamp without time zone,
    fin_formulaire timestamp without time zone,
    remarques text,
    CONSTRAINT histo_obs_origine_odk_pkey PRIMARY KEY (date_operation, utilisateur, idobs)
);

CREATE FUNCTION md_obs_historique.alimente_histo_obs_origine_odk()
    RETURNS trigger AS $BODY$

    declare user_login integer;

	BEGIN

        user_login = outils.get_user();

		IF (TG_OP = 'DELETE') THEN INSERT INTO md_obs_historique.histo_obs_origine_odk SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_obs_historique.histo_obs_origine_odk SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_obs_historique.histo_obs_origine_odk SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_histo_obs_origine_odk
    AFTER INSERT OR DELETE OR UPDATE 
    ON md_obs.obs_origine_odk
    FOR EACH ROW
    EXECUTE PROCEDURE md_obs_historique.alimente_histo_obs_origine_odk();

CREATE TABLE md_obs_historique.histo_obs_source_ext
(
    type_operation text,
    date_operation timestamp without time zone NOT NULL,
    utilisateur text,
    id_obs_source_ext integer,
    idobs integer,
    idligne integer,
    id_source_origine integer,
    est_thematique boolean,
    nom_source character varying(80),
    type_source character varying(80),
    path_source character varying(250),
    remarques text,
    CONSTRAINT histo_obs_source_ext_pkey PRIMARY KEY (date_operation, utilisateur, id_obs_source_ext)
);

CREATE FUNCTION md_obs_historique.alimente_histo_obs_source_ext()
    RETURNS trigger AS $BODY$

    declare user_login integer;

	BEGIN

        user_login = outils.get_user();

		IF (TG_OP = 'DELETE') THEN INSERT INTO md_obs_historique.histo_obs_source_ext SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_obs_historique.histo_obs_source_ext SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_obs_historique.histo_obs_source_ext SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_histo_obs_source_ext
    AFTER INSERT OR DELETE OR UPDATE 
    ON md_obs.obs_source_ext
    FOR EACH ROW
    EXECUTE PROCEDURE md_obs_historique.alimente_histo_obs_source_ext();

CREATE TABLE md_obs_historique.histo_obs_origine_ext
(
    type_operation text,
    date_operation timestamp without time zone NOT NULL,
    utilisateur text,
    id_obs_origine_ext integer,
    idobs integer,
    idligne integer,
    idobs_origine character varying(250),
    CONSTRAINT histo_ligne_obs_source_extpkey PRIMARY KEY (date_operation, utilisateur, id_obs_origine_ext)
);

CREATE FUNCTION md_obs_historique.alimente_histo_obs_origine_ext()
    RETURNS trigger AS $BODY$

    declare user_login integer;

	BEGIN

        user_login = outils.get_user();

		IF (TG_OP = 'DELETE') THEN INSERT INTO md_obs_historique.histo_obs_origine_ext SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_obs_historique.histo_obs_origine_ext SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_obs_historique.histo_obs_origine_ext SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_histo_obs_origine_ext
    AFTER INSERT OR DELETE OR UPDATE 
    ON md_obs.obs_origine_ext
    FOR EACH ROW
    EXECUTE PROCEDURE md_obs_historique.alimente_histo_obs_origine_ext();

CREATE TABLE md_obs_historique.histo_obs_export
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone NOT NULL,
    utilisateur text NOT NULL,
    id_obs_export integer NOT NULL,
    idobs integer NOT NULL,
    date_export timestamp without time zone NOT NULL,
    id_type_cible integer NOT NULL,
    idorg_cible integer,
    remarques text,
    CONSTRAINT histo_obs_export_pkey PRIMARY KEY (date_operation, utilisateur, id_obs_export)
);

CREATE FUNCTION md_obs_historique.alimente_histo_obs_export()
    RETURNS trigger AS $BODY$

    declare user_login integer;

	BEGIN

        user_login = outils.get_user();

		IF (TG_OP = 'DELETE') THEN INSERT INTO md_obs_historique.histo_obs_export SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_obs_historique.histo_obs_export SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_obs_historique.histo_obs_export SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;	

CREATE TRIGGER declenche_alimente_histo_obs_export
    AFTER INSERT OR DELETE OR UPDATE 
    ON md_obs.obs_export
    FOR EACH ROW
    EXECUTE PROCEDURE md_obs_historique.alimente_histo_obs_export();