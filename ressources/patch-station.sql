--Modif RLE
ALTER TABLE obs.site ADD "typestation" integer;
ALTER TABLE obs.site ADD "commentaire" text;
ALTER TABLE obs.site ADD "idorg" integer;
ALTER TABLE obs.site ADD "idetude" integer;
ALTER TABLE obs.site ADD "idmembre" integer;
ALTER TABLE obs.site ADD "wsite" text;
ALTER TABLE obs.site ADD "idparent" integer;
ALTER TABLE obs.site ADD "idstatus" integer;
ALTER TABLE obs_historique.histo_site ADD "typestation" integer;
ALTER TABLE obs_historique.histo_site ADD "commentaire" text;
ALTER TABLE obs_historique.histo_site ADD "idorg" integer;
ALTER TABLE obs_historique.histo_site ADD "idetude" integer;
ALTER TABLE obs_historique.histo_site ADD "idmembre" integer;
ALTER TABLE obs_historique.histo_site ADD "wsite" text;
ALTER TABLE obs_historique.histo_site ADD "idparent" integer;
ALTER TABLE obs_historique.histo_site ADD "idstatus" integer;


--SCHEMA referentiel_station : dictionnaires relatifs aux stations
CREATE SCHEMA referentiel_station;


--Dictionnaire des types de stations
create table referentiel_station.statusstation
(
	idstatusstation smallint,
	libidstatusstation varchar(50),
	mdidstatusstation text
)
;

INSERT INTO referentiel_station.statusstation VALUES
(1, 'Rien à signaler',NULL),
(2, 'Station à prospecter',NULL),
(3, 'Inaccessible',NULL),
(4, 'Disparue',NULL),
(5, 'A vérifier',NULL);

create table referentiel_station.typestation
(
	idtypestation smallint,
	libtypestation varchar(50),
	mdtypestation text,
     CONSTRAINT typestation_pkey PRIMARY KEY (idtypestation)
)
;

INSERT INTO referentiel_station.typestation VALUES
(1,'Mare',NULL),
(2,'A renseigner',NULL),
(3,'Autre',NULL);

ALTER TABLE obs.site ADD CONSTRAINT site_typestation_fkey FOREIGN KEY (typestation) REFERENCES referentiel_station.typestation (idtypestation);
ALTER TABLE obs.site ADD CONSTRAINT site_idorg_idetude_fkey FOREIGN KEY (idorg,idetude) REFERENCES referentiel.etude_organisme (idorg,idetude);
ALTER TABLE obs.site ADD CONSTRAINT site_idm_fkey FOREIGN KEY (idmembre) REFERENCES site.membre (idmembre);

create table referentiel_station.typemare
(
	idtypemare smallint,
	libtypemare varchar(50),
	mdtypemare text,
    CONSTRAINT typemare_pkey PRIMARY KEY (idtypemare)
)
;

INSERT INTO referentiel_station.typemare VALUES
(0,'Non renseigné',NULL),
(1,'Mare prairiale',NULL),
(2,'Mare de carrière',NULL),
(3,'Mare forestière',NULL),
(4,'Mare dunaire',NULL),
(5,'Mare d''ornement',NULL),
(6,'Étang',NULL),
(7,'Source',NULL),
(8,'Lavoir',NULL),
(9,'Ornière',NULL),
(10,'Bassin de rétention',NULL),
(11,'Autre',NULL);


create table referentiel_station.environnement
(
	idenvironnement smallint,
	libenvironnement varchar(50),
	mdenvironnement text,
    CONSTRAINT environnement_pkey PRIMARY KEY (idenvironnement)
);

INSERT INTO referentiel_station.environnement VALUES
(0,'Non renseigné',NULL),
(1,'Prairie humide',NULL),
(2,'Prairie',NULL),
(3,'Culture',NULL),
(4,'Boisement',NULL),
(5,'Jardin',NULL),
(6,'Zone urbanisée',NULL),
(7,'Carrière',NULL),
(8,'Friche',NULL),
(9,'Lande',NULL),
(10,'Autre',NULL);

create table referentiel_station.menaces
(
	idmenaces smallint,
	libmenaces varchar(50),
	mdmenaces text,
    CONSTRAINT menaces_pkey PRIMARY KEY (idmenaces)
);

INSERT INTO referentiel_station.menaces VALUES
(0,'Non renseigné',NULL),
(1,'Aucune visible',NULL),
(2,'Poissons',NULL),
(3,'Atterrissement/Embroussaillement - Niveau 1',NULL),
(4,'Atterrissement/Embroussaillement - Niveau 2',NULL),
(5,'Piétinement',NULL),
(6,'Pollution diverse',NULL),
(7,'Ragondin',NULL),
(8,'Dépôts de déchets/gravats',NULL),
(9,'Autre',NULL);


create table referentiel_station.vegaquatique
(
	idvegaquatique smallint,
	libvegaquatique varchar(50),
	mdvegaquatique text,
    CONSTRAINT vegaquatique_pkey PRIMARY KEY (idvegaquatique)
);

INSERT INTO referentiel_station.vegaquatique VALUES
(0,'Non renseigné',NULL),
(1,'Non',NULL),
(2,'Oui',NULL);

create table referentiel_station.vegsemiaquatique
(
	idvegsemiaquatique smallint,
	libvegsemiaquatique varchar(50),
	mdvegsemiaquatique text,
    CONSTRAINT vegsemiaquatique_pkey PRIMARY KEY (idvegsemiaquatique)
);

INSERT INTO referentiel_station.vegsemiaquatique VALUES
(0,'Non renseigné',NULL),
(1,'Non',NULL),
(2,'Oui',NULL);

create table referentiel_station.vegrivulaire
(
	idvegrivulaire smallint,
	libvegrivulaire varchar(50),
	mdvegrivulaire text,
    CONSTRAINT vegrivulaire_pkey PRIMARY KEY (idvegrivulaire)
);

INSERT INTO referentiel_station.vegrivulaire VALUES
(0,'Non renseigné',NULL),
(1,'Non',NULL),
(2,'Oui',NULL);

create table referentiel_station.typeexutoire
(
	idtypeexutoire smallint,
	libtypeexutoire varchar(50),
	mdtypeexutoire text,
    CONSTRAINT typeexutoire_pkey PRIMARY KEY (idtypeexutoire)
);

INSERT INTO referentiel_station.typeexutoire VALUES
(0,'Non renseigné',NULL),
(1,'Inconnu',NULL),
(2,'Pas d’exutoire',NULL),
(3,'Exutoire en eau',NULL),
(4,'Exutoire à sec',NULL);

create table referentiel_station.taillemare
(
	idtaillemare smallint,
	libtaillemare varchar(50),
	mdtaillemare text,
    CONSTRAINT taillemare_pkey PRIMARY KEY (idtaillemare)
);

INSERT INTO referentiel_station.taillemare VALUES
(0,'Non renseigné',NULL),
(1,'0 à 5 m2',NULL),
(2,'5 à 25 m2',NULL),
(3,'25 à 100 m2',NULL),
(4,'100 à 500 m2',NULL),
(5,'500 à 2000 m2',NULL),
(6,'plus de 2000 m2',NULL);

create table referentiel_station.couleureau
(
	idcouleureau smallint,
	libcouleureau varchar(50),
	mdcouleureau text,
    CONSTRAINT couleureau_pkey PRIMARY KEY (idcouleureau)
);

INSERT INTO referentiel_station.couleureau VALUES
(0,'Non renseigné',NULL),
(1,'Limpide',NULL),
(2,'Trouble',NULL),
(3,'Opaque',NULL);

create table referentiel_station.naturefond
(
	idnaturefond smallint,
	libnaturefond varchar(50),
	mdnaturefond text,
    CONSTRAINT naturefond_pkey PRIMARY KEY (idnaturefond)
);

INSERT INTO referentiel_station.naturefond VALUES
(0,'Non renseigné',NULL),
(1,'Inconnu',NULL),
(2,'Naturel',NULL),
(3,'Artificiel (bâche, béton)',NULL);

create table referentiel_station.recberge
(
	idrecberge smallint,
	librecberge varchar(50),
	mdrecberge text,
    CONSTRAINT recberge_pkey PRIMARY KEY (idrecberge)
);

INSERT INTO referentiel_station.recberge VALUES
(0,'Non renseigné',NULL),
(1,'Aucun',NULL),
(2,'0 à 25%',NULL),
(3,'25 à 50%',NULL),
(4,'50 à 75%',NULL),
(5,'75 à 100%',NULL);


create table referentiel_station.profondeureau
(
	idprofondeureau smallint,
	libprofondeureau varchar(50),
	mdprofondeureau text,
    CONSTRAINT profondeureau_pkey PRIMARY KEY (idprofondeureau)
);

INSERT INTO referentiel_station.profondeureau VALUES
(0,'Non renseigné',NULL),
(1,'0 à 20 cm',NULL),
(2,'20 à 150 cm',NULL),
(3,'plus de 150 cm',NULL);

create table referentiel_station.alimeau
(
	idalimeau smallint,
	libalimeau varchar(50),
	mdalimeau text,
    CONSTRAINT alimeau_pkey PRIMARY KEY (idalimeau)
);

INSERT INTO referentiel_station.alimeau VALUES
(0,'Non renseigné',NULL),
(1,'Inconnu',NULL),
(2,'Pluie',NULL),
(3,'Nappe',NULL),
(4,'Drains enterrés',NULL),
(5,'Fossés',NULL);

--SCHEMA station : tables pour infos sur chaque type de stations

CREATE SCHEMA station;



create table station.infosmare
(
    idinfosmare serial,
	idstation integer,
    idobser integer,
    plusobser varchar(3),
    idorg integer,
    idetude integer,
    datedescription date NOT NULL,
    idtypemare integer,
    idenvironnement integer,
    receaulibre smallint,
    idvegaquatique integer,
    idvegsemiaquatique integer,
    idvegrivulaire integer,
    idtypeexutoire integer,
    idtaillemare integer,
    idcouleureau integer,
    idnaturefond integer,
    idrecberge integer,
    idprofondeureau integer,
    commentaire varchar,
    idmembre integer,
    CONSTRAINT infosmare_pkey PRIMARY KEY (idinfosmare),
    CONSTRAINT infosmare_idobser_fkey FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser),
    CONSTRAINT infosmare_idorg_idetude_fkey FOREIGN KEY (idorg,idetude) REFERENCES referentiel.etude_organisme (idorg,idetude),
    CONSTRAINT infosmare_idstation_fkey FOREIGN KEY (idstation) REFERENCES obs.site (idsite),
    CONSTRAINT infosmare_idtypemare_fkey FOREIGN KEY (idtypemare) REFERENCES referentiel_station.typemare (idtypemare),
    CONSTRAINT infosmare_idenvironnement_fkey FOREIGN KEY (idenvironnement) REFERENCES referentiel_station.environnement (idenvironnement),
    CONSTRAINT infosmare_idvegaquatique_fkey FOREIGN KEY (idvegaquatique) REFERENCES referentiel_station.vegaquatique (idvegaquatique),
    CONSTRAINT infosmare_idvegsemiaquatique_fkey FOREIGN KEY (idvegsemiaquatique) REFERENCES referentiel_station.vegsemiaquatique (idvegsemiaquatique),
    CONSTRAINT infosmare_idvegrivulaire_fkey FOREIGN KEY (idvegrivulaire) REFERENCES referentiel_station.vegrivulaire (idvegrivulaire),
    CONSTRAINT infosmare_idtypeexutoire_fkey FOREIGN KEY (idtypeexutoire) REFERENCES referentiel_station.typeexutoire (idtypeexutoire),
    CONSTRAINT infosmare_idtaillemare_fkey FOREIGN KEY (idtaillemare) REFERENCES referentiel_station.taillemare (idtaillemare),
    CONSTRAINT infosmare_idcouleureau_fkey FOREIGN KEY (idcouleureau) REFERENCES referentiel_station.couleureau (idcouleureau),
    CONSTRAINT infosmare_idnaturefond_fkey FOREIGN KEY (idnaturefond) REFERENCES referentiel_station.naturefond (idnaturefond),
    CONSTRAINT infosmare_idrecberge_fkey FOREIGN KEY (idrecberge) REFERENCES referentiel_station.recberge (idrecberge),
    CONSTRAINT infosmare_idprofondeureau_fkey FOREIGN KEY (idprofondeureau) REFERENCES referentiel_station.profondeureau (idprofondeureau),
    CONSTRAINT infosmare_idmembre_fkey FOREIGN KEY (idmembre) REFERENCES site.membre (idmembre)
);

create table station.infosmare_plusobser
(
    idinfosmare integer,
    idobser integer,
    CONSTRAINT infosmare_plusobser_pkey PRIMARY KEY (idinfosmare,idobser),
    CONSTRAINT infosmare_plusobser_idinfosmare_fkey FOREIGN KEY (idinfosmare) REFERENCES station.infosmare (idinfosmare),
    CONSTRAINT infosmare_plusobser_idobser_fkey FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser)
);

create table station.infosmare_menaces
(
    idinfosmare integer,
    idmenaces smallint,
    CONSTRAINT infosmare_menaces_pkey PRIMARY KEY (idinfosmare,idmenaces),
    CONSTRAINT infosmare_menaces_idinfosmare_fkey FOREIGN KEY (idinfosmare) REFERENCES station.infosmare (idinfosmare),
    CONSTRAINT infosmare_menaces_idmenaces_fkey FOREIGN KEY (idmenaces) REFERENCES referentiel_station.menaces (idmenaces)
);

create table station.infosmare_alimeau
(
    idinfosmare integer,
    idalimeau smallint,
    CONSTRAINT infosmare_alimeau_pkey PRIMARY KEY (idinfosmare,idalimeau),
    CONSTRAINT infosmare_alimeau_idinfosmare_fkey FOREIGN KEY (idinfosmare) REFERENCES station.infosmare (idinfosmare),
    CONSTRAINT infosmare_alimeau_idalimeau_fkey FOREIGN KEY (idalimeau) REFERENCES referentiel_station.alimeau (idalimeau)
);

create table station.photo
(
  idphoto serial,
  idstation integer,
  idobser integer,
  datephoto date,
  codecom varchar(5),
  nomphoto varchar(50),
  datesaisie timestamp,
  ordre smallint,
  CONSTRAINT photostation_pkey PRIMARY KEY (idphoto),
  CONSTRAINT photostation_idstation_fkey FOREIGN KEY (idstation) REFERENCES obs.site (idsite),
  CONSTRAINT photostation_idobser_fkey FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser),
  CONSTRAINT photostation_codecom_fkey FOREIGN KEY (codecom) REFERENCES referentiel.commune (codecom)
);
