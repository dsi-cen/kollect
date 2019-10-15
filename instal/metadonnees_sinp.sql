--Mise en place des tables de métadonnées conformément au standard V1.3.10 - Jomier R., Robert S., Vest F. 2019. Métadonnées version 1.3.10, UMS 2006 Patrimoine Naturel, Paris, 57 pp
--Est pris en compte également le guide paru en juin 2019 - DUPONT P. & TOUROULT J. 2018. Guide pour la constitution des jeux de données du SINP et le renseignement des métadonnées associées. Rapport UMS PatriNat (AFB, MNHN, CNRS), 36 pp.


CREATE SCHEMA md_sinp;
CREATE SCHEMA md_sinp_historique;

-- on ajoute à la table obs.obs la relation idjdd ainsi qu'à sa table d'historique
ALTER TABLE obs.obs ADD idjdd int NULL;
ALTER TABLE  obs_historique.histo_obs ADD idjdd int NULL;


--RELATIONS SINP

--obs_sinp : table faisant le lien entre l'observation et son identifiant unique donné par la plateforme du SINP
CREATE TABLE md_sinp.obs_sinp
(
    idobs integer,
    idobs_sinp uuid,
    remarques text,
    CONSTRAINT obs_sinp_pkey PRIMARY KEY (idobs,idobs_sinp)
);

CREATE TABLE md_sinp_historique.obs_sinp_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idobs integer,
    idobs_sinp uuid,
    remarques text,
    CONSTRAINT obs_sinp_histo_pkey PRIMARY KEY (date_operation, utilisateur, idobs, idobs_sinp)
);

-- Création de la fonction d'historisation
CREATE FUNCTION md_sinp_historique.alimente_obs_sinp_histo()
    RETURNS trigger AS $BODY$
    declare user_login integer;
	BEGIN

        user_login = outils.get_user();

		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.obs_sinp_histo SELECT 'DELETE', now(), user_login, OLD.*; RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.obs_sinp_histo SELECT 'UPDATE', now(), user_login, NEW.*; RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.obs_sinp_histo SELECT 'INSERT', now(), user_login, NEW.*; RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
	LANGUAGE plpgsql VOLATILE COST 100;

-- Création du trigger pour déclencher la fonction précédente
CREATE TRIGGER declenche_alimente_obs_sinp_histo
    BEFORE INSERT OR DELETE OR UPDATE 
    ON md_sinp.obs_sinp
    FOR EACH ROW
    EXECUTE PROCEDURE md_sinp_historique.alimente_obs_sinp_histo();


-------------------------------------REFERENTIELS CA ET JDD------------------------------------------

--ref_acteur_role : dictionnaire du rôle que peut avoir un acteur
CREATE TABLE md_sinp.ref_acteur_role
(
    id_acteur_role integer,
    id_sinp integer,
    lib_acteur_role character varying (50),
    md_acteur_role text,
    CONSTRAINT acteur_role_pkey PRIMARY KEY (id_acteur_role)
);

INSERT INTO md_sinp.ref_acteur_role VALUES
(1,1,'Contact principal','Contact principal'),
(2,2,'Financeur','Financeur'),
(3,3,'Maître d''ouvrage','Maître d''ouvrage'),
(4,4,'Maître d''oeuvre','Maître d''oeuvre'),
(5,5,'Fournisseur du jeu de données','Fournisseur du jeu de données'),
(6,6,'Producteur du jeu de données','Producteur du jeu de données'),
(7,7,'Point de contact base de données de production','Point de contact base de données de production');

-- TABLE HORS SINP 
--acteur : table referencant les acteurs
--Un acteur est défini par une structure et un nom_prénom ou lib_acteur
--idorg est en lien avec la table referentiel.organisme
--lib_acteur permet de ne pas personnifier l'acteur (ex: idorg = Cen Aquitaine et lib_acteur = Antenne Gironde) 

CREATE TABLE md_sinp.acteur
(
    id_acteur serial,
    idorg integer NOT NULL,
    nom_acteur character varying (50),
    prenom_acteur character varying (50),
    lib_acteur  character varying (150),
    mail_acteur character varying (50),
    CONSTRAINT acteur_pkey PRIMARY KEY (id_acteur),
    CONSTRAINT acteur_idorg_fkey FOREIGN KEY (idorg) REFERENCES referentiel.organisme (idorg)
);

--ref_type_protocole : dictionnaire des types de protocole que peut avoir un protocole
CREATE TABLE md_sinp.ref_type_protocole
(
    id_type_protocole integer,
    id_sinp integer NOT NULL,
    lib_type_protocole character varying (50),
    md_type_protocole text,
    CONSTRAINT ref_type_protocole_pkey PRIMARY KEY (id_type_protocole)
);

INSERT INTO md_sinp.ref_type_protocole VALUES
(1,0,'Inconnu','Inconnu'),
(2,1,'Protocole de collecte','Protocole de collecte'),
(3,2,'Protocole de synthèse','Protocole de synthèse'),
(4,3,'Protocole de conformité et de cohérence','Protocole de conformité et de cohérence'),
(5,4,'Protocole de validation','Protocole de validation');



-------------------------------------REFERENTIELS CA------------------------------------------------------

-- ref_ca_objectif : dictionnaire des objectifs que peut avoir un ca
CREATE TABLE md_sinp.ref_ca_objectif
(
    idca_objectif integer,
    id_sinp integer,
    lib_ca_objectif character varying (50),
    md_ca_objectif text,
    CONSTRAINT ref_ca_objectif_pkey PRIMARY KEY (idca_objectif)
);

INSERT INTO md_sinp.ref_ca_objectif VALUES
(1,1,'Inventaire espèce',''),
(2,2,'Inventaire habitat centré',''),
(3,3,'Inventaire logique espace',''),
(4,4,'Evaluation interaction',''),
(5,5,'Evolution temporelle',''),
(6,6,'Evolution espace',''),
(7,7,'Regroupements et autres études','');


-- ref_ca_processus : dictionnaire des processus d'acquisition que peut avoir un CA
CREATE TABLE md_sinp.ref_ca_processus
(
    idca_processus integer,
    id_sinp character varying (5),
    lib_ca_processus character varying (100),
    md_ca_processus text,
    CONSTRAINT ref_ca_processus_pkey PRIMARY KEY (idca_processus)
);

INSERT INTO md_sinp.ref_ca_processus VALUES
(1,1,'Inventaire et cartographie','L''acquisition des données d''occurrence est réalisée avec la démarche d''avoir des informations sur la présence simple, la présence/absence ou les effectifs/abondance (dénombrement, surface d''un habitat…) d''un ou de plusieurs objets de biodiversité (espèces, habitats ou autres). Le dispositif de collecte est établi pour avoir une représentation spatiale de la répartition d''un ou de plusieurs objets de biodiversité à des dates ou des périodes prédéfinies'),
(2,2,'Suivi/Surveillance dans le temps','L''acquisition des données d''occurrence est réalisée avec un dispositif de collecte comprenant obligatoirement une répétition de l''acquisition au cours du temps. La démarche permet une comparaison d''un état entre différentes périodes pour un ou plusieurs objets de biodiversité. Elle est mise en place en lien avec une question scientifique précise (ex : effet dans temps d''une mesure de gestion, impact d''un aménagement, effet des changements globaux etc.) et/ou dans un objectif de veille sans question spécifique (ex : tendance des effectifs d''une population etc.) et de production d''indicateurs'),
(3,3,'Expérimentation/Recherche','L''acquisition des données est réalisée avec une démarche d''amélioration de la connaissance scientifique ciblée sur une ou plusieurs questions précises (de la description des patrons de biodiversité à l''expérimentation pour expliquer les processus ou démontrer des relations causales de type « avant/après - témoin » (effet de la gestion, mécanismes etc.)). L''expérimentation et la recherche de type purement « observationnelle » ou « corrélative » doivent figurer dans les catégories « inventaires » ou « suivis/surveillance »'),
(4,4,'Multiple ou autres','L''acquisition des données est réalisée avec une démarche propre faisant intervenir plusieurs démarches préalablement décrites');


--ref_type_financement : dictionnaire du type de financement d'un ca
CREATE TABLE md_sinp.ref_type_financement
(
    id_type_financement integer NOT NULL,
    id_sinp integer NOT NULL,
    lib_type_financement character varying (50),
    md_type_financement text,
    CONSTRAINT ref_type_financement_pkey PRIMARY KEY (id_type_financement)
);

INSERT INTO md_sinp.ref_type_financement VALUES
(1,1,'Public','Type de financement public'),
(2,2,'Privé','Type de financement privé'),
(3,3,'Mixte','Mélange de financement public et privé'),
(4,4,'Non financé','Absence de financement');

-- ref_niveau_territorial : dictionnaire du niveau_territorial que peut avoir un ca
CREATE TABLE md_sinp.ref_niveau_territorial
(
    id_niveau_territorial integer NOT NULL,
    id_sinp integer NOT NULL,
    lib_niveau_territorial character varying (50),
    md_niveau_territorial text,
    CONSTRAINT ref_niveau_territorial_pkey PRIMARY KEY (id_niveau_territorial)
);

INSERT INTO md_sinp.ref_niveau_territorial VALUES
(1,1,'International','International'),
(2,2,'Européen','Européen'),
(3,3,'National','National'),
(4,4,'Inter-régional terrestre, ou région marine','Inter-régional terrestre, ou région marine'),
(5,5,'Régional terrestre, ou sous-région marine','Régional terrestre, ou sous-région marine'),
(6,6,'Départemental, ou secteur marin','Départemental, ou secteur marin'),
(7,7,'Communal ou local','Communal ou local');

--ref_territoire : dictionnaire des territoires concernant le ca
CREATE TABLE md_sinp.ref_territoire
(
    id_territoire integer,
    id_sinp character varying (10),
    lib_territoire character varying (150),
    md_territoire text,
    CONSTRAINT ref_territoire_pkey PRIMARY KEY (id_territoire)
);

INSERT INTO md_sinp.ref_territoire VALUES
(1,'METROP','Métropole',''),
(2,'GUF','Guyane française',''),
(3,'MTQ','Martinique',''),
(4,'GLP','Guadeloupe',''),
(5,'MAF','Saint-Martin',''),
(6,'BLM','Saint-Barthélemy',''),
(7,'SPM','Saint-Pierre et Miquelon',''),
(8,'MYT','Mayotte',''),
(9,'REU','Réunion',''),
(10,'NCL','Nouvelle-Calédonie',''),
(11,'WLF','Wallis-et-Futuna',''),
(12,'PYF','Polynésie française',''),
(13,'CLI','Clipperton',''),
(14,'EPA','TAAF : Iles Eparses',''),
(15,'SUBANT','TAAF : Iles sub-Antarctiques',''),
(16,'TADL','TAAF : Terre-Adélie',''),
(17,'HORSFR','Hors territoire','');

--ref_volet_sinp : dictionnaire des volets que peut viser un ca
CREATE TABLE md_sinp.ref_volet_sinp
(
    id_volet_sinp integer NOT NULL,
    id_sinp integer NOT NULL,
    lib_ca_volet_sinp character varying (50),
    md_ca_volet_sinp text,
    CONSTRAINT ref_volet_sinp_pkey PRIMARY KEY (id_volet_sinp)
);

INSERT INTO md_sinp.ref_volet_sinp VALUES
(1,1,'Terre','Toutes les données relatives à la nature/biodiversité française du domaine terrestre (outre-mer compris) : habitats, flore, faune, champignons..., les données relatives aux espaces naturels (protégés / gérés ou non), aux sites géologiques, aux écosystèmes et leur fonctionnement'),
(2,2,'Mer','Toutes les données relatives à la nature / biodiversité française du domaine marin (outre-mer compris) : habitats, flore, faune, champignons..., les données relatives aux espaces naturels (protégés/gérés ou non), aux sites géologiques, aux écosystèmes et leur fonctionnement'),
(3,3,'Paysage','Toutes les données relatives aux paysages, c''est-à-dire des données relatives aux formes du territoire, aux perceptions sociales et aux dynamiques du territoire. 
Elles intègrent également des inventaires particuliers. Elles concernent les espaces naturels, ruraux, urbains et périurbains. 
Elles incluent les espaces terrestres, les eaux intérieures et maritimes. Elles concernent tant les paysages pouvant être considérés comme remarquables que les paysages du quotidien et les paysages dégradés');

-- TABLE HORS SINP : permet de spécifier un type de publication pour les publication rattachées au ca
CREATE TABLE md_sinp.ref_type_publication
(
    id_type_publication serial,
    id_sinp integer,
    lib_type_publication character varying (150) NOT NULL,
    md_type_publication text, 
    CONSTRAINT ref_type_publication_pkey PRIMARY KEY (id_type_publication)
);

INSERT INTO md_sinp.ref_type_publication VALUES
(1,NULL,'Rapport annuel de plan de gestion',''),
(2,NULL,'Rapport de bilan de plan de gestion',''),
(3,NULL,'Rapport d''élaboration de plan de gestion',''),
(4,NULL,'Rapport d''étude opérationnelle',''),
(5,NULL,'Rapport de PRA','');
--A COMPLETER

-- TABLE HORS SINP : permet de spécifier un type de ca
CREATE TABLE md_sinp.ref_type_ca
(
    id_type_ca serial,
    id_sinp integer,
    lib_type_ca character varying (150) NOT NULL,
    md_type_ca text, 
    CONSTRAINT ref_type_ca_pkey PRIMARY KEY (id_type_ca)
);

INSERT INTO md_sinp.ref_type_ca VALUES
(1,NULL,'Élaboration de plan de gestion',''),
(2,NULL,'Plan de gestion',''),
(3,NULL,'Plan de gestion simplifié',''),
(4,NULL,'Notice de gestion',''),
(5,NULL,'Plan régional d''action',''),
(6,NULL,'Programme spécifique',''),
(7,NULL,'Assistance technique à collectivité',''),
(8,NULL,'Animation de DOCOB Natura 2000',''),
(9,NULL,'Diagnostic écologique de DOCOB Natura 2000',''),
(10,NULL,'Données naturalistes indépendantes','');
--A COMPLETER



----------------------------------------------REFERENTIELS JDD-----------------------------------------------------------

--ref_type_donnees : dictionnaire du type de données du jdd
CREATE TABLE md_sinp.ref_type_donnees
(
    id_type_donnees integer,
    id_sinp integer,
    lib_type_donnee character varying (50),
    md_type_donnee text,
    CONSTRAINT ref_type_donnees_pkey PRIMARY KEY (id_type_donnees)
);

INSERT INTO md_sinp.ref_type_donnees VALUES
(1,1,'Occurrences de taxons',''),
(2,2,'Occurrences d''habitats',''),
(3,3,'Synthèse de taxons',''),
(4,4,'Synthèse d''habitats',''),
(5,5,'Non renseigné','');

-- ref_jdd_objectif : dictionnaire de l'objectif visé par un jdd
CREATE TABLE md_sinp.ref_jdd_objectif
(
    idjdd_objectif integer,
    id_sinp character varying (5),
    lib_jdd_objectif character varying (100),
    md_jdd_objectif text,
    CONSTRAINT ref_jdd_objectif_pkey PRIMARY KEY (idjdd_objectif)
);

INSERT INTO md_sinp.ref_jdd_objectif VALUES
(1,'1.1','Observations naturalistes opportunistes',''),
(2,'1.2','Inventaire de répartition',''),
(3,'1.3','Inventaire pour étude d''espèces ou de communautés',''),
(4,'1.4','Numérisation de collections',''),
(5,'1.5','Numérisation de bibliographie',''),
(6,'2.1','Cartographie d''habitats',''),
(7,'2.2','Inventaire d''habitat',''),
(8,'2.3','Données opportunistes d''habitat',''),
(9,'2.4','Inventaire pour étude d''habitat',''),
(10,'2.5','Numérisation de bibliographie habitat',''),
(11,'3.1','Inventaire type ABC',''),
(12,'3.2','Inventaire de Zonages d''intérêt',''),
(13,'3.3','Inventaire/évaluation pour plans de gestion',''),
(14,'3.4','Observations opportunistes sur un site',''),
(15,'3.5','Inventaires généralisés & exploration',''),
(16,'3.6','Inventaire pour étude d''impact',''),
(17,'3.7','Cartographie d''habitat d''un site',''),
(18,'4.1','Évaluation de la ressource / prélèvements',''),
(19,'4.2','Évaluation des collisions/échouages',''),
(20,'5.1','Suivi individus centré',''),
(21,'5.2','Surveillance temporelle d''espèces',''),
(22,'5.3','Surveillance communauté d''espèces',''),
(23,'5.4','Surveillance des habitats',''),
(24,'5.5','Surveillance de pathogènes et EEE',''),
(25,'6.1','Surveillance site',''),
(26,'6.2','Suivis de gestion ou expérimental',''),
(27,'6.3','Étude effet gestion',''),
(28,'6.4','Suivis réglementaires',''),
(29,'7.1','Regroupement de données',''),
(30,'7.2','Autres études et programmes','');

-- ref_jdd_processus : dictionnaire des processus associés au jdd
CREATE TABLE md_sinp.ref_jdd_processus
(
    idjdd_processus integer,
    id_sinp character varying (5),
    lib_jdd_processus character varying (100),
    md_jdd_processus text,
    CONSTRAINT ref_jdd_processus_pkey PRIMARY KEY (idjdd_processus)
);

INSERT INTO md_sinp.ref_jdd_processus VALUES
(1,'1','Processus de collecte non intentionnel ou de réutilisation','Les données sont collectées sur le terrain de façon accidentelle ou à partir de sources spécifiques différentes du terrain : service de données, collections ou bibliographie. Par exemple, une donnée d''observation d''une espèce de libellule collectée dans le cadre d''un comptage floristique rentre dans un JDD caractérisé par un processus de collecte accidentel. 
La nature de cette donnée est complètement différente de celle d''une donnée sur la même espèce associée à un processus de collecte ciblé sur cette dernière. Pour la bibliographie ou la saisie de données de collections, la saisie ne relève pas du même objectif que l''acquisition originale de l''information. 
Par conséquent, individualiser des jeux de données issues des collections ou de la bibliographie dans des JDDs spécifiques permet une meilleure gestion d''attributs spécifiques comme la localisation d''un spécimen (numéro de boite pour un insecte par exemple) où la référence bibliographique, ce qui permet de faciliter une vérification de la donnée par un tiers. 
Les jeux de données de synthèse rentrent aussi dans cette catégorie. Ils sont construits à partir de données sources auxquels on soumet un processus de synthèse en lien avec un objectif particulier (le plus souvent une analyse statistique). Ce processus peut inclure une sélection des données selon des paramètres prédéterminés et/ou un croisement avec des données non soumises au protocole du SINP'),
(2,'2','Processus de collecte opportuniste','Les données sont collectées sur le terrain de façon opportuniste, c''est-à-dire sans planification et pression d''observation déterminée. Une donnée d''observation d''une espèce de papillon par un naturaliste entomologiste lors d''une randonnée estivale rentre dans cette typologie'),
(3,'3','Processus de collecte étendu spatialement','Les données sont collectées sur le terrain en lien avec un protocole s''appliquant à une vaste zone géographique. A l''échelle locale, on ne recherche pas l''exhaustivité dans la collecte des données. La motivation associée au dispositif de collecte est de couvrir au maximum le territoire sachant que la couverture de l''ensemble du territoire est impossible.
Une donnée collectée dans le cadre d''un dispositif de collecte couvrant une région et fondée sur la visite d''un réseau de communes ou de mailles 10x10 km, rentre dans cette typologie. Un lien avec le référentiel des protocoles Campanule4 peut être établi.'),
(4,'4','Processus de collecte restreint spatialement','Les données sont collectées sur le terrain en lien avec un protocole s''appliquant à une zone géographique restreinte. Dans cette zone, on recherche l''exhaustivité dans la collecte des données (présence et/ou absence pour une espèce ; communauté présente pour un groupe taxonomique ou fonctionnel ; …). 
L''objet géographique cible peut être un espace protégé, une parcelle, une placette, un linéaire, un transect ou un point pour lesquels on vise l''exhaustivité dans l''objet étudié. Cette typologie concerne notamment, (1) les dispositifs de collecte de type « Inventaires généraux de la biodiversité (ATBI) » ; (2) les données récoltées par un naturaliste dans une parcelle avec une motivation d''avoir des informations sur une communauté d''espèces observées ou non observées ; (3) les données d''oiseaux collectées en appliquant un seul protocole standardisé basé sur la méthode du point d''écoute ; (4) les données obtenues à l''aide d''un piégeage direct par attraction ou interception. 
Ce type de processus permet de déduire des informations directes concernant la problématique de présence/absence des taxons. Un lien avec le référentiel des protocoles Campanule peut être établi'),
(5,'5','Processus de collecte par piégeage ou échantillonnage indirect','Les données sont collectées sur le terrain de façon indirecte soit à l''aide d''un piège photographique, d''un enregistrement de son, de télémétrie ou d''observations de traces, plumes, fèces, etc. L''ADN environnemental, rentre également dans cette typologie. 
Selon le type de protocole, ce processus peut permettre de déduire des informations directes concernant la problématique de présence/absence des taxons. Un lien avec le référentiel des protocoles Campanule peut être établi'),
(6,'6','Processus de collecte compilatoire','La liste des données d''occurrence assemblées provient de différents processus dont on n''a pas gardé la trace.
Les données anciennes d''une association naturaliste peuvent rentrées dans cette typologie.
Le JDD associé à ce processus est qualifié d''hétérogène');



--ref_methode_recueil : dictionnaire des méthodes de recueil des données utilisées dans un jdd
CREATE TABLE md_sinp.ref_methode_recueil
(
    id_methode_recueil integer,
    id_sinp character varying (5),
    lib_methode_recueil character varying (150),
    md_methode_recueil text,
    CONSTRAINT ref_methode_recueil_pkey PRIMARY KEY (id_methode_recueil)
);

INSERT INTO md_sinp.ref_methode_recueil VALUES
(1,1,'Observation directe : Vue, écoute, olfactive, tactile',''),
(2,2,'Pièges photo',''),
(3,3,'Détection d''ultrasons',''),
(4,4,'Recherche d''indices de présence',''),
(5,5,'Photographies aériennes',''),
(6,6,'Télédétection',''),
(7,7,'Télémétrie',''),
(8,8,'Capture d''individus (sans capture d''échantillon) : capture-relâcher',''),
(9,9,'Prélèvement (capture avec collecte d''échantillon) : capture-conservation',''),
(10,10,'Capture marquage recapture',''),
(11,11,'Capture-suivi (radiotracking)',''),
(12,12,'Autre','');


-- TABLE HORS SINP
--ref_regne_cible : dictionnaire du règne ciblé dans un jdd
CREATE TABLE md_sinp.ref_regne_cible
(
    id_regne_cible integer,
    id_sinp integer,
    lib_id_regne_cible character varying (150),
    md_id_regne_cible text,
    CONSTRAINT ref_id_regne_cible_pkey PRIMARY KEY (id_regne_cible)
);

INSERT INTO md_sinp.ref_regne_cible VALUES
(1,NULL,'Animalia','Données faune'),
(2,NULL,'Plantae','Données flore'),
(3,NULL,'Fungi','Données fonge');
--A COMPLETER


-----------------------------------------------SAISIE CA------------------------------------------------------

CREATE TABLE md_sinp.ca
(
    idca serial,
	uuidca uuid,
    id_type_ca integer NOT NULL,--HORS SINP, permet de rattacher un ca à un type de ca (si donnée indé, id_type_ca = 10)
    lib_complet_ca text NOT NULL,--libelle
    description_ca text NOT NULL,--description
    ca_mots_cles text,--motCle
    date_deb date NOT NULL,--dateLancement
    date_fin date,--dateCloture
    id_niveau_territorial integer,--niveauTerritorial
    precision_niveau_territorial text,--precisionGeographique
    id_type_financement integer,--typeFinancement
    cible_ecologique_geologique text,--cibleEcologiqueOuGeologique
    description_cible text,--descriptionCible
	idca_processus integer,
    est_metacadre boolean NOT NULL,--estMetaCadre
    id_metacadre integer,--idMetaCadreParent
    CONSTRAINT ca_pkey PRIMARY KEY (idca),
    CONSTRAINT ca_id_type_ca_fkey FOREIGN KEY (id_type_ca) REFERENCES md_sinp.ref_type_ca (id_type_ca),
    CONSTRAINT ca_id_niveau_territorial_fkey FOREIGN KEY (id_niveau_territorial) REFERENCES md_sinp.ref_niveau_territorial (id_niveau_territorial),
    CONSTRAINT ca_id_type_financement_fkey FOREIGN KEY (id_type_financement) REFERENCES md_sinp.ref_type_financement (id_type_financement),
	CONSTRAINT ca_idca_processus_fkey FOREIGN KEY (idca_processus) REFERENCES md_sinp.ref_ca_processus (idca_processus)
);

CREATE TABLE md_sinp_historique.ca_histo
(
    type_operation text,
    date_operation timestamp without time zone,
    utilisateur text,
    idca integer,
	uuidca uuid,
    id_type_ca integer,
    lib_complet_ca text,
    description_ca text,
    ca_mots_cles text,
    date_deb date,
    date_fin date,
    id_niveau_territorial integer,
    precision_niveau_territorial text,
    id_type_financement integer,
    cible_ecologique_geologique text,
    description_cible text,
	idca_processus integer,
    est_metacadre boolean,
    id_metacadre integer,
    
    CONSTRAINT ca_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca)
);


CREATE TABLE md_sinp_historique.ca_histo_synthese
(
    idca integer NOT NULL,
    datetime_insert timestamp without time zone NOT NULL,
    date_insert date NOT NULL,
    datetime_update timestamp without time zone,
    date_update date,
    table_update text,
    CONSTRAINT ca_histo_synthese_pkey PRIMARY KEY (idca)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_histo SELECT 'DELETE', now(), current_user, OLD.*; 
			DELETE FROM md_sinp_historique.ca_histo_synthese WHERE idca = OLD.idca;
			RETURN OLD;
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_histo SELECT 'UPDATE', now(), current_user, NEW.*; 
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca' WHERE idca = NEW.idca;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_histo SELECT 'INSERT', now(), current_user, NEW.*; 
			INSERT INTO md_sinp_historique.ca_histo_synthese VALUES (NEW.idca, now(),now(),NULL,NULL,NULL);
			RETURN NEW; 
		END IF; 
			RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca
        FOR EACH ROW
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_histo();

	
-- Table intermediaire : un ca peut avoir plusieurs etudes associées et une étude peut avoir plusieurs ca associés
CREATE TABLE md_sinp.ca_etude
(
	idca_etude serial,
	idca integer,
	idetude integer,--HORS SINP : permet de rattacher un ca à une etude de la table referentiel.etude
	CONSTRAINT ca_etude_pkey PRIMARY KEY (idca_etude),
	CONSTRAINT ca_etude_idca_fkey FOREIGN KEY (idca) REFERENCES md_sinp.ca (idca),
	CONSTRAINT ca_etude_idetude_fkey FOREIGN KEY (idetude) REFERENCES referentiel.etude (idetude)	
);	
	
CREATE TABLE md_sinp_historique.ca_etude_histo
(
    type_operation text,
    date_operation timestamp without time zone,
    utilisateur text,
    idca_etude integer,
    idca integer,
    idetude integer,   
    CONSTRAINT ca_etude_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca_etude)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_etude_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_etude_histo SELECT 'DELETE', now(), current_user, OLD.*; 
			RETURN OLD;
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_etude_histo SELECT 'UPDATE', now(), current_user, NEW.*; 
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_etude_histo SELECT 'INSERT', now(), current_user, NEW.*; 
			RETURN NEW; 
		END IF; 
			RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_etude
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_etude
        FOR EACH ROW
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_etude_histo();

--Insertion du cadre d'acquisition des données indépendantes et l'étude associée	
INSERT INTO md_sinp.ca ("uuidca", "id_type_ca", "lib_complet_ca", "description_ca", "ca_mots_cles", "date_deb", "date_fin", "id_niveau_territorial", "precision_niveau_territorial", "id_type_financement", "cible_ecologique_geologique", "description_cible", "idca_processus", "est_metacadre", "id_metacadre") 
VALUES (null, 10, 'Données naturalistes indépendantes d''origine privée partagées sur la base de données Kollect Nouvelle-Aquitaine', 'Données naturalistes indépendantes d''origine privée partagées sur la base de données Kollect Nouvelle-Aquitaine', null, '1980-08-01', null, null, null, null, null, null, null, false, null);	

INSERT INTO md_sinp.ca_etude ("idca","idetude") VALUES (1,0);

-- A FAIRE : créer un trigger qui ajoute automatiquement un acteur :
--avec le rôle de Contact principal (id 1) selon l'organisme rattaché à l'étude  
--avec le rôle de Producteur du jeu de données (id 6) selon l'organisme rattaché à l'étude
--avec le rôle de Point de contact base de données de production (id 7) à dba@cen-aquitaine.fr
--le rôle de Maître d'oeuvre (id 4) ne peut pas être automatisé 

CREATE TABLE md_sinp.ca_acteur
(
    idca integer,
    id_acteur integer,
    id_acteur_role integer NOT NULL,
    CONSTRAINT ca_acteur_pkey PRIMARY KEY (idca,id_acteur,id_acteur_role),
    CONSTRAINT ca_acteur_idca_fkey FOREIGN KEY (idca) REFERENCES md_sinp.ca (idca),
    CONSTRAINT ca_acteur_id_acteur_fkey FOREIGN KEY (id_acteur) REFERENCES md_sinp.acteur (id_acteur),
    CONSTRAINT ca_acteur_id_acteur_role_fkey FOREIGN KEY (id_acteur_role) REFERENCES md_sinp.ref_acteur_role (id_acteur_role)
);

CREATE TABLE md_sinp_historique.ca_acteur_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idca integer,
    id_acteur integer,
    id_acteur_role integer,
    CONSTRAINT ca_acteur_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca,id_acteur,id_acteur_role)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_acteur_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_acteur_histo SELECT 'DELETE', now(), current_user, OLD.*; 
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_acteur' WHERE idca = OLD.idca;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_acteur_histo SELECT 'UPDATE', now(), current_user, NEW.*; 
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_acteur' WHERE idca = NEW.idca;
			RETURN NEW;
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_acteur_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_acteur' WHERE idca = NEW.idca AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_acteur_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_acteur
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_acteur_histo()
;

CREATE TABLE md_sinp.ca_protocole
(
    idca integer,
    id_type_protocole integer,--protocoles
    lib_protocole character varying (150),
    md_protocole text,
    url_protocole text,
    CONSTRAINT ca_protocole_pkey PRIMARY KEY (idca,id_type_protocole),
    CONSTRAINT ca_protocole_id_type_protocole_fkey FOREIGN KEY (id_type_protocole) REFERENCES md_sinp.ref_type_protocole (id_type_protocole)
);

CREATE TABLE md_sinp_historique.ca_protocole_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idca integer,
    id_type_protocole integer,
    lib_protocole character varying (150),
    md_protocole text,
    url_protocole text,
    CONSTRAINT ca_protocole_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca, id_type_protocole)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_protocole_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_protocole_histo SELECT 'DELETE', now(), current_user, OLD.*; 
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_protocole' WHERE idca = OLD.idca;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_protocole_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_protocole' WHERE idca = NEW.idca;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_protocole_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_protocole' WHERE idca = NEW.idca AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_protocole_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_protocole
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_protocole_histo()
;

CREATE TABLE md_sinp.ca_volet_sinp
( 
    idca integer NOT NULL,
    id_volet_sinp integer NOT NULL,--voletSINP
    CONSTRAINT ca_volet_sinp_pkey PRIMARY KEY (idca, id_volet_sinp)
);

CREATE TABLE md_sinp_historique.ca_volet_sinp_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idca integer,
    id_volet_sinp integer,
    CONSTRAINT ca_volet_sinp_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca, id_volet_sinp)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_volet_sinp_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_volet_sinp_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_volet_sinp' WHERE idca = OLD.idca;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_volet_sinp_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_volet_sinp' WHERE idca = NEW.idca;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_volet_sinp_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_volet_sinp' WHERE idca = NEW.idca AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_volet_sinp_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_volet_sinp
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_volet_sinp_histo()
;

CREATE TABLE md_sinp.ca_objectif
(
    idca integer,
    idca_objectif integer,--objectif
    CONSTRAINT ca_objectif_pkey PRIMARY KEY (idca,idca_objectif),
    CONSTRAINT ca_objectif_idca_fkey FOREIGN KEY (idca) REFERENCES md_sinp.ca (idca),
    CONSTRAINT ca_objectif_idca_objectif_fkey FOREIGN KEY (idca_objectif) REFERENCES md_sinp.ref_ca_objectif (idca_objectif)
);

CREATE TABLE md_sinp_historique.ca_objectif_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idca integer,
    idca_objectif integer,
    CONSTRAINT ca_objectif_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca, idca_objectif)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_objectif_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_objectif_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_objectif' WHERE idca = OLD.idca;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_objectif_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_objectif' WHERE idca = NEW.idca;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_objectif_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_objectif' WHERE idca = NEW.idca AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_objectif_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_objectif
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_objectif_histo()
;

CREATE TABLE md_sinp.ca_territoire
(
    idca integer,
    id_territoire integer,--territoire
    CONSTRAINT ca_territoire_pkey PRIMARY KEY (idca,id_territoire),
    CONSTRAINT ca_territoire_idca_fkey FOREIGN KEY (idca) REFERENCES md_sinp.ca (idca),
    CONSTRAINT ca_territoire_id_territoire_fkey FOREIGN KEY (id_territoire) REFERENCES md_sinp.ref_territoire (id_territoire)
);

CREATE TABLE md_sinp_historique.ca_territoire_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idca integer,
    id_territoire integer,
    CONSTRAINT ca_territoire_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca, id_territoire)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_territoire_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_territoire_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_territoire' WHERE idca = OLD.idca;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_territoire_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_territoire' WHERE idca = NEW.idca;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_territoire_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_territoire' WHERE idca = NEW.idca AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_territoire_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_territoire
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_territoire_histo()
;

CREATE TABLE md_sinp.ca_publication
(
    idca_publication serial,
    idca integer NOT NULL,
    id_type_publication integer NOT NULL,--CHAMP HORS SINP
    url_publication text,--URLPublication
    reference_publication text,--referencePublication
    CONSTRAINT ca_publication_pkey PRIMARY KEY (idca_publication),
    CONSTRAINT ca_publication_idca_fkey FOREIGN KEY (idca) REFERENCES md_sinp.ca (idca),
    CONSTRAINT ca_publication_id_type_publication_fkey FOREIGN KEY (id_type_publication) REFERENCES md_sinp.ref_type_publication (id_type_publication)
);

CREATE TABLE md_sinp_historique.ca_publication_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idca_publication integer,
    idca integer,
    id_type_publication integer,
    url_publication text,
    reference_publication text,
    CONSTRAINT ca_publication_histo_pkey PRIMARY KEY (date_operation, utilisateur, idca_publication)
);

CREATE FUNCTION md_sinp_historique.alimente_ca_publication_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.ca_publication_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_publication' WHERE idca = OLD.idca;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.ca_publication_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_publication' WHERE idca = NEW.idca;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.ca_publication_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.ca_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.ca_publication' WHERE idca = NEW.idca AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_ca_publication_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.ca_publication
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_ca_publication_histo()
;

-----------------------------------------------SAISIE JDD------------------------------------------------------

----- 
CREATE TABLE md_sinp.jdd
(
    idjdd serial,
	uuidjdd uuid,
    idca_etude integer NOT NULL,--Pour lier à l'étude et au ca
    lib_complet_jdd character varying (150) NOT NULL,--libelle
    lib_court_jdd character varying (30) NOT NULL,--libelleCourt
    description_jdd text NOT NULL,--description
    jdd_mots_cles text,--motCle
    id_type_donnees integer NOT NULL,--typeDonnees
    idjdd_objectif integer NOT NULL,--objectifJdd
	idjdd_processus integer NOT NULL,
    idorg integer NOT NULL,-- CHAMP HORS SINP faisant le lien avec l''organisme associé à l'étude du CA
    idbdd integer, --baseProduction utilisé pour faire le rattachement au jdd si idorg = "Indépendant" --> à utiliser pour les sources externes au SI du CEN (>= 6, considérant que les tableurs sont d'offices intégrés au SI)
    idobser integer,--CHAMP HORS SINP : permet de rattacher à un ca les données indépendantes selon l'observateur principal de la table obs.obs, utilisé uniquement si idorg = 'Indépendant'
    id_type_acquisition integer,-- CHAMP HORS SINP faisant le lien avec le type d'acquisition renseigné lors des obs CEN
    id_regne_cible integer NOT NULL,-- CHAMP HORS SINP faisant le lien avec le règne ciblé (pour faire la différence entre faune et flore )
    domaine_marin boolean NOT NULL,--domaineMarin
    domaine_terrestre boolean NOT NULL,--domaineTerrestre
    CONSTRAINT jdd_pkey PRIMARY KEY (idjdd),
    CONSTRAINT jdd_idca_etude_fkey FOREIGN KEY (idca_etude) REFERENCES md_sinp.ca_etude (idca_etude),
	CONSTRAINT jdd_idobser_fkey FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser),
	CONSTRAINT jdd_idorg_fkey FOREIGN KEY (idorg) REFERENCES referentiel.organisme (idorg),
    CONSTRAINT jdd_id_type_donnees_fkey FOREIGN KEY (id_type_donnees) REFERENCES md_sinp.ref_type_donnees (id_type_donnees),
	CONSTRAINT jdd_idjdd_processus_fkey FOREIGN KEY (idjdd_processus) REFERENCES md_sinp.ref_jdd_processus (idjdd_processus)
);

CREATE TABLE md_sinp_historique.jdd_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idjdd integer,
	uuidjdd uuid,
    idca_etude integer,
    lib_complet_jdd character varying (150),
    lib_court_jdd character varying (30),
    description_jdd text,
    jdd_mots_cles text,
    id_type_donnees integer,
    idjdd_objectif integer,
	idjdd_processus integer,
    id_org integer,
    idbdd integer,
    idobser integer,
    id_type_acquisition integer,
    id_regne_cible integer,
    domaine_marin boolean,
    domaine_terrestre boolean,
    CONSTRAINT jdd_histo_pkey PRIMARY KEY (date_operation, utilisateur, idjdd)
);

CREATE TABLE md_sinp_historique.jdd_histo_synthese
(
    idjdd integer NOT NULL,
    datetime_insert timestamp without time zone NOT NULL,
    date_insert date NOT NULL,
    datetime_update timestamp without time zone,
    date_update date,
    table_update text,
    CONSTRAINT jdd_histo_synthese_pkey PRIMARY KEY (idjdd)
);

CREATE FUNCTION md_sinp_historique.alimente_jdd_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.jdd_histo SELECT 'DELETE', now(), current_user, OLD.*;
			DELETE FROM md_sinp_historique.jdd_histo_synthese WHERE idjdd = OLD.idjdd;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.jdd_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd' WHERE idjdd = NEW.idjdd;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.jdd_histo SELECT 'INSERT', now(), current_user, NEW.*;
			INSERT INTO md_sinp_historique.jdd_histo_synthese VALUES (NEW.idjdd, now(),now(),NULL,NULL,NULL);
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_jdd_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.jdd
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_jdd_histo()
;


CREATE TABLE md_sinp.jdd_acteur
(
    idjdd integer,
    id_acteur integer,--pointContactJdd
    id_acteur_role integer NOT NULL,
    CONSTRAINT jdd_acteur_pkey PRIMARY KEY (idjdd,id_acteur,id_acteur_role),
    CONSTRAINT jdd_acteur_idjdd_fkey FOREIGN KEY (idjdd) REFERENCES md_sinp.jdd (idjdd),
    CONSTRAINT jdd_acteur_id_acteur_fkey FOREIGN KEY (id_acteur) REFERENCES md_sinp.acteur (id_acteur),
    CONSTRAINT jdd_acteur_id_acteur_role_fkey FOREIGN KEY (id_acteur_role) REFERENCES md_sinp.ref_acteur_role (id_acteur_role),
    CONSTRAINT jdd_acteur_id_acteur_role_ckeck CHECK (id_acteur_role = 1 OR id_acteur_role = 5 OR id_acteur_role = 6)
);

CREATE TABLE md_sinp_historique.jdd_acteur_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idjdd integer,
    id_acteur integer,
    id_acteur_role integer,
    CONSTRAINT jdd_acteur_histo_pkey PRIMARY KEY (date_operation, utilisateur, idjdd, id_acteur,id_acteur_role)
);

CREATE FUNCTION md_sinp_historique.alimente_jdd_acteur_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.jdd_acteur_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_acteur' WHERE idjdd = OLD.idjdd;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.jdd_acteur_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_acteur' WHERE idjdd = NEW.idjdd;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.jdd_acteur_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_acteur' WHERE idjdd = NEW.idjdd AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_jdd_acteur_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.jdd_acteur
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_jdd_acteur_histo()
;

CREATE TABLE md_sinp.jdd_territoire
(
    idjdd integer,
    id_territoire integer,--territoire
    CONSTRAINT jdd_territoire_pkey PRIMARY KEY (idjdd,id_territoire),
    CONSTRAINT jdd_territoire_idca_fkey FOREIGN KEY (idjdd) REFERENCES md_sinp.jdd (idjdd),
    CONSTRAINT jdd_territoire_id_territoire_fkey FOREIGN KEY (id_territoire) REFERENCES md_sinp.ref_territoire (id_territoire)
);

CREATE TABLE md_sinp_historique.jdd_territoire_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idjdd integer,
    id_territoire integer,
    CONSTRAINT jdd_territoire_histo_pkey PRIMARY KEY (date_operation, utilisateur, idjdd, id_territoire)
);

CREATE FUNCTION md_sinp_historique.alimente_jdd_territoire_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.jdd_territoire_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_territoire' WHERE idjdd = OLD.idjdd;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.jdd_territoire_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_territoire' WHERE idjdd = NEW.idjdd;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.jdd_territoire_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_territoire' WHERE idjdd = NEW.idjdd AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_jdd_territoire_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.jdd_territoire
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_jdd_territoire_histo()
;

CREATE TABLE md_sinp.jdd_methode_recueil
(
    idjdd integer,
    id_methode_recueil integer,--methodeRecueil
    CONSTRAINT jdd_methode_recueil_pkey PRIMARY KEY (idjdd,id_methode_recueil),
    CONSTRAINT jdd_methode_recueil_idca_fkey FOREIGN KEY (idjdd) REFERENCES md_sinp.jdd (idjdd),
    CONSTRAINT jdd_methode_recueil_id_methode_recueil_fkey FOREIGN KEY (id_methode_recueil) REFERENCES md_sinp.ref_methode_recueil (id_methode_recueil)
);

CREATE TABLE md_sinp_historique.jdd_methode_recueil_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idjdd integer,
    id_methode_recueil integer,
    CONSTRAINT jdd_methode_recueil_histo_pkey PRIMARY KEY (date_operation, utilisateur, idjdd, id_methode_recueil)
);

CREATE FUNCTION md_sinp_historique.alimente_jdd_methode_recueil_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.jdd_methode_recueil_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_methode_recueil' WHERE idjdd = OLD.idjdd;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.jdd_methode_recueil_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_methode_recueil' WHERE idjdd = NEW.idjdd;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.jdd_methode_recueil_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_methode_recueil' WHERE idjdd = NEW.idjdd AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_jdd_methode_recueil_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.jdd_methode_recueil
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_jdd_methode_recueil_histo()
;

CREATE TABLE md_sinp.jdd_protocole
(
    idjdd integer,
    id_type_protocole integer,--protocoles
    lib_protocole character varying (150),
    md_protocole text,
    url_protocole text,
    CONSTRAINT jdd_protocole_pkey PRIMARY KEY (idjdd,id_type_protocole),
    CONSTRAINT jdd_protocole_id_type_protocole_fkey FOREIGN KEY (id_type_protocole) REFERENCES md_sinp.ref_type_protocole (id_type_protocole)
);
--A FAIRE : table liant les protocoles selon les types d'acquisition

CREATE TABLE md_sinp_historique.jdd_protocole_histo
(
    type_operation text NOT NULL,
    date_operation timestamp without time zone,
    utilisateur text,
    idjdd integer,
    id_type_protocole integer,
    lib_protocole character varying (150),
    md_protocole text,
    url_protocole text,
    CONSTRAINT jdd_protocole_histo_pkey PRIMARY KEY (date_operation, utilisateur, idjdd, id_type_protocole)
);

CREATE FUNCTION md_sinp_historique.alimente_jdd_protocole_histo()
    RETURNS trigger AS 
    $BODY$
    BEGIN
		IF (TG_OP = 'DELETE') THEN INSERT INTO md_sinp_historique.jdd_protocole_histo SELECT 'DELETE', now(), current_user, OLD.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_protocole' WHERE idjdd = OLD.idjdd;
			RETURN OLD; 
		ELSIF (TG_OP = 'UPDATE') THEN INSERT INTO md_sinp_historique.jdd_protocole_histo SELECT 'UPDATE', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_protocole' WHERE idjdd = NEW.idjdd;
			RETURN NEW; 
		ELSIF (TG_OP = 'INSERT') THEN INSERT INTO md_sinp_historique.jdd_protocole_histo SELECT 'INSERT', now(), current_user, NEW.*;
			UPDATE md_sinp_historique.jdd_histo_synthese SET date_update = now(), datetime_update = now(), table_update = 'md_sinp.jdd_protocole' WHERE idjdd = NEW.idjdd AND date_trunc('second',datetime_insert) != date_trunc('second',now());
			RETURN NEW; 
		END IF; 
		RETURN NULL; 
	END;
	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_alimente_jdd_protocole_histo
    BEFORE INSERT OR DELETE OR UPDATE ON md_sinp.jdd_protocole
    FOR EACH ROW 
    EXECUTE PROCEDURE md_sinp_historique.alimente_jdd_protocole_histo();


--------AUTOMATISATION DE L'ATTRIBUTION DE idjdd à une observation selon l'organisme, l'étude, la date d'obs, le type d'acquisition et le règne
--lors de la création ou de la mise à jour d'une observation

CREATE FUNCTION obs.insert_idjdd_obs()
    RETURNS trigger AS 
    $BODY$
    DECLARE 

    w_organisme INTEGER;
    w_obser INTEGER;
    w_etude INTEGER;
	w_regne INTEGER;
    w_date_max DATE;
    w_type_acquisition INTEGER;
    w_ca_etude INTEGER;
    w_jdd INTEGER;

    BEGIN

        SELECT idorg FROM obs.fiche WHERE fiche.idfiche = NEW.idfiche INTO w_organisme;
		
		SELECT idetude FROM obs.fiche WHERE fiche.idfiche = NEW.idfiche INTO w_etude;
				
		SELECT id_regne_cible FROM md_sinp.ref_regne_cible JOIN referentiel.taxref ON taxref.regne = ref_regne_cible.lib_id_regne_cible WHERE taxref.cdnom = NEW.cdnom INTO w_regne;
				
        SELECT NEW.idprotocole INTO w_type_acquisition;
		
		
        IF w_organisme >= 3 THEN
            
            SELECT date2 FROM obs.fiche WHERE fiche.idfiche = NEW.idfiche INTO w_date_max;
			
            SELECT ca_etude.idca_etude 
			FROM md_sinp.ca_etude 
			JOIN md_sinp.ca ON ca_etude.idca = ca.idca
			WHERE ca_etude.idetude = w_etude 
			AND ca.date_deb <= w_date_max AND ca.date_fin >= w_date_max INTO w_ca_etude;
			
            SELECT jdd.idjdd FROM md_sinp.jdd 
			WHERE jdd.idca_etude = w_ca_etude 
			AND jdd.id_type_acquisition = w_type_acquisition 
			AND jdd.idorg = w_organisme 
			AND jdd.id_regne_cible = w_regne INTO w_jdd;
        
            IF w_jdd IS NOT NULL THEN
                NEW.idjdd = w_jdd;
            END IF;
            IF w_jdd IS NULL THEN
                NEW.idjdd = NULL;
            END IF;
            
        END IF;

		--Si organisme = Indépendant
		-- CA = Données naturalistes idépendantes d'origine privée partagées sur la base Kollect Nouvelle - Aquitaine
		-- pas de notion de dates
		-- pas de notion de type d'acquisition (pour simplification)
		-- JDD = Selon la personne et la bdd d'origine. 
		--Pour la base de données source, cela est fait manuellement	
			--SELECT obs_source_ext.id_source_origine FROM md_obs.obs_source_ext WHERE obs_source_ext.idobs = NEW.idobs
        
		IF w_organisme = 2 THEN

            SELECT fiche.idobser FROM obs.fiche WHERE fiche.idfiche = NEW.idfiche INTO w_obser;
			
			SELECT ca_etude.idca_etude
			FROM md_sinp.ca_etude
			JOIN md_sinp.ca ON ca_etude.idca = ca.idca
			WHERE ca.idca = 1 
			INTO w_ca_etude;
			
			
          --idbdd NULL pour classer l'obs d'office dans le SI. une autre vérif doit se faire après insertion dans la table obs_source_ext pour modifier le jdd le cas échéant
			SELECT idjdd 
			FROM md_sinp.jdd 
			WHERE jdd.idca_etude = w_ca_etude
			AND jdd.idobser = w_obser 
			AND jdd.idbdd IS NULL 
			AND jdd.id_regne_cible = w_regne 
			INTO w_jdd;

            IF w_jdd IS NOT NULL THEN
                NEW.idjdd = w_jdd;
            END IF;

            IF w_jdd IS NULL THEN
                NEW.idjdd = NULL;
            END IF;

        END IF;
        
        RETURN NEW;

    END;

	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_insert_idjdd_obs
    BEFORE INSERT OR UPDATE OF idprotocole,cdnom ON obs.obs
    FOR EACH ROW 
    EXECUTE PROCEDURE obs.insert_idjdd_obs();
	
	
-- lors de la mise à jour d'une fiche sur la date, l'organisme, l'étude ou l'observateur

CREATE FUNCTION obs.update_idjdd_obs_from_fiche()
    RETURNS trigger AS 
    $BODY$
    DECLARE 

    w_organisme INTEGER;
    w_obser INTEGER;
    w_etude INTEGER;
    w_date_max DATE;
	w_regne INTEGER;
	

    BEGIN

		SELECT NEW.idorg INTO w_organisme;
        SELECT NEW.date2 INTO w_date_max;
		SELECT NEW.idetude INTO w_etude;
		SELECT NEW.idobser INTO w_obser;
          
        IF w_organisme >= 3 THEN
		
		
			WITH liste_jdd AS (
				SELECT jdd.idjdd,jdd.id_regne_cible,jdd.id_type_acquisition
				FROM md_sinp.jdd
				JOIN md_sinp.ca_etude ON ca_etude.idetude = w_etude
				JOIN md_sinp.ca ON ca.idca = ca_etude.idca
				WHERE jdd.idca_etude = ca_etude.idca_etude AND jdd.idorg = w_organisme AND (ca.date_deb <= w_date_max AND ca.date_fin >= w_date_max)),
		
				 liste_obs AS (
					SELECT obs.idobs,liste_jdd.idjdd,ref_regne_cible.id_regne_cible FROM obs.obs
					JOIN referentiel.taxref ON obs.cdnom = taxref.cdnom
					JOIN md_sinp.ref_regne_cible ON taxref.regne = ref_regne_cible.lib_id_regne_cible
					JOIN liste_jdd ON liste_jdd.id_regne_cible = ref_regne_cible.id_regne_cible AND liste_jdd.id_type_acquisition = obs.idprotocole
					WHERE obs.idfiche = NEW.idfiche)
				
				UPDATE obs.obs SET idjdd = (SELECT idjdd FROM liste_obs WHERE liste_obs.idobs = obs.idobs) WHERE obs.idfiche = NEW.idfiche;
	
        END IF;
		
		
		IF w_organisme = 2 THEN
		
				
			WITH liste_jdd AS (
					SELECT jdd.idjdd,jdd.id_regne_cible FROM md_sinp.jdd
					JOIN md_sinp.ca_etude ON ca_etude.idca_etude = jdd.idca_etude
					JOIN md_sinp.ca ON ca_etude.idca = ca.idca
					WHERE jdd.idobser = w_obser AND  jdd.idorg = w_organisme),
		
				 liste_obs AS (
					SELECT obs.idobs,liste_jdd.idjdd,ref_regne_cible.id_regne_cible FROM obs.obs
					JOIN referentiel.taxref ON obs.cdnom = taxref.cdnom
					JOIN md_sinp.ref_regne_cible ON taxref.regne = ref_regne_cible.lib_id_regne_cible
					JOIN liste_jdd ON liste_jdd.id_regne_cible = ref_regne_cible.id_regne_cible
					WHERE obs.idfiche = NEW.idfiche)
		
				UPDATE obs.obs SET idjdd = (SELECT idjdd FROM liste_obs WHERE liste_obs.idobs = obs.idobs) WHERE obs.idfiche = NEW.idfiche;
			
        END IF;

        RETURN NEW;

    END;

	$BODY$ 
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_update_idjdd_obs_from_fiche
    AFTER UPDATE OF date2, idorg, idetude ON obs.fiche
    FOR EACH ROW 
    EXECUTE PROCEDURE obs.update_idjdd_obs_from_fiche();
	
	
-- lors de la creation d'un jdd
	

CREATE FUNCTION obs.update_idjdd_obs_from_jdd()
    RETURNS trigger AS
    $BODY$
	DECLARE

	w_organisme INTEGER;
    w_obser INTEGER;
	w_etude INTEGER;
	w_regne INTEGER;
	w_type_acquisition INTEGER;
	w_datedeb DATE;
	w_datefin DATE;


    BEGIN

		SELECT NEW.idorg INTO w_organisme;
		SELECT NEW.idobser INTO w_obser;
        SELECT NEW.id_regne_cible INTO w_regne;




        IF w_organisme >= 3 THEN

			SELECT ca_etude.idetude FROM md_sinp.ca_etude WHERE ca_etude.idca_etude = NEW.idca_etude INTO w_etude;
			SELECT ca.date_deb FROM md_sinp.ca JOIN md_sinp.ca_etude ON ca_etude.idca = ca.idca WHERE ca_etude.idca_etude = NEW.idca_etude INTO w_datedeb;
			SELECT ca.date_fin FROM md_sinp.ca JOIN md_sinp.ca_etude ON ca_etude.idca = ca.idca WHERE ca_etude.idca_etude = NEW.idca_etude INTO w_datefin;
			SELECT NEW.id_type_acquisition INTO w_type_acquisition;

		    IF w_datefin IS NOT NULL THEN

			    WITH liste_fiche AS (

					SELECT fiche.idfiche FROM obs.fiche
					WHERE fiche.idorg = w_organisme AND fiche.idetude = w_etude AND fiche.date2 >= w_datedeb AND fiche.date2 <= w_datefin),

				liste_obs AS (

					SELECT obs.idobs
					FROM obs.obs
					JOIN liste_fiche ON liste_fiche.idfiche = obs.idfiche
					JOIN referentiel.taxref ON obs.cdnom = taxref.cdnom
					JOIN md_sinp.ref_regne_cible ON taxref.regne = ref_regne_cible.lib_id_regne_cible
					WHERE ref_regne_cible.id_regne_cible = w_regne AND obs.idprotocole = w_type_acquisition)

			    UPDATE obs.obs SET idjdd = NEW.idjdd WHERE obs.idobs IN (SELECT idobs FROM liste_obs);

			ELSE

		        WITH liste_fiche AS (

					SELECT fiche.idfiche FROM obs.fiche
					WHERE fiche.idorg = w_organisme AND fiche.idetude = w_etude AND fiche.date2 >= w_datedeb),

				liste_obs AS (

					SELECT obs.idobs
					FROM obs.obs
					JOIN liste_fiche ON liste_fiche.idfiche = obs.idfiche
					JOIN referentiel.taxref ON obs.cdnom = taxref.cdnom
					JOIN md_sinp.ref_regne_cible ON taxref.regne = ref_regne_cible.lib_id_regne_cible
					WHERE ref_regne_cible.id_regne_cible = w_regne AND obs.idprotocole = w_type_acquisition)

			    UPDATE obs.obs SET idjdd = NEW.idjdd WHERE obs.idobs IN (SELECT idobs FROM liste_obs);

		    END IF;

		END IF;

		IF w_organisme = 2 THEN

			WITH liste_fiche AS (
					SELECT fiche.idfiche FROM obs.fiche
					WHERE fiche.idorg = w_organisme AND fiche.idobser = w_obser),

				liste_obs AS (
					SELECT obs.idobs
					FROM obs.obs
					JOIN liste_fiche ON liste_fiche.idfiche = obs.idfiche
					JOIN referentiel.taxref ON obs.cdnom = taxref.cdnom
					JOIN md_sinp.ref_regne_cible ON taxref.regne = ref_regne_cible.lib_id_regne_cible
					WHERE ref_regne_cible.id_regne_cible = w_regne)

			UPDATE obs.obs SET idjdd = NEW.idjdd WHERE obs.idobs IN (SELECT idobs FROM liste_obs);

		   END IF;

        RETURN NEW;

    END;

	$BODY$
LANGUAGE plpgsql VOLATILE COST 100;

CREATE TRIGGER declenche_update_idjdd_obs_from_jdd
    AFTER INSERT ON md_sinp.jdd
    FOR EACH ROW
    EXECUTE PROCEDURE obs.update_idjdd_obs_from_jdd();

	
	
	
	
	
	
