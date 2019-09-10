create materialized view obs.synthese_obs_flou as
    WITH order_plusobser AS (
	  SELECT plusobser.idfiche,plusobser.idobser, observateur.observateur
	  FROM obs.plusobser
	  JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
	  ORDER BY plusobser.idfiche,observateur.observateur
	  ),
		
	concat_otherobser AS (
	 SELECT order_plusobser.idfiche,
         string_agg((order_plusobser.observateur)::text, ', '::text)                  AS otherobser,
         string_agg(((order_plusobser.idobser)::character varying)::text, ', '::text) AS idotherobser
         FROM order_plusobser
         GROUP BY order_plusobser.idfiche
         ORDER BY order_plusobser.idfiche
    ),
         infos_validateur AS (
             SELECT DISTINCT h.idobs,
                             (((m_1.nom)::text || ' '::text) || (m_1.prenom)::text) AS validateur,
                             h.vali,
                             h.typevali,
                             max(h.dateval)                                         AS dateval
             FROM (vali.histovali h
                      JOIN site.membre m_1 ON ((m_1.idmembre = h.idm)))
             GROUP BY h.idobs, (((m_1.nom)::text || ' '::text) || (m_1.prenom)::text), h.vali, h.typevali
             ORDER BY h.idobs
         ),
         infos_collection AS (
             SELECT obscoll.idobs,
                    max(obscoll.idcol) AS idcol
             FROM obs.obscoll
             GROUP BY obscoll.idobs
         ),
         infos_photo AS (
             SELECT photo.idobs
             FROM site.photo
             GROUP BY photo.idobs
         ),
         infos_son AS (
             SELECT son_1.idobs
             FROM site.son son_1
             GROUP BY son_1.idobs
         ),
         infos_plante AS (
             SELECT obsplte.idobs,
                    obsplte.cdnom,
                    ((liste.nom || ' '::text) || liste.auteur) AS nomlatincomplet,
                    obsplte.stade
             FROM (obs.obsplte
                      JOIN referentiel.liste ON ((liste.cdnom = obsplte.cdnom)))
         ),
		  select_genre AS (
			SELECT cdnom AS cdnom_genre,cdref,nom FROM referentiel.taxref WHERE rang = 'GN'
			AND cdnom = cdref),

			select_es AS (
				SELECT cdnom AS cdnom_es,select_genre.nom AS genre
				FROM referentiel.taxref
				LEFT JOIN select_genre ON select_genre.cdnom_genre = taxref.cdtaxsup
				WHERE rang = 'ES' AND taxref.cdnom = taxref.cdref
			),

			select_sses_sup_es AS (
				SELECT cdnom AS cdnom_sses, cdtaxsup AS cdnom_es
				FROM referentiel.taxref
				WHERE rang = 'SSES' AND taxref.cdnom = taxref.cdref
			),

			select_sses AS (
				SELECT cdnom_sses, select_es.genre
				FROM select_sses_sup_es
				LEFT JOIN select_es ON select_es.cdnom_es = select_sses_sup_es.cdnom_es
			),
			infos_genre AS (
				SELECT cdnom_genre AS cdnom, nom AS genre FROM select_genre
				UNION
				SELECT cdnom_es AS cdnom, genre FROM select_es
				UNION
				SELECT cdnom_sses AS cdnom, genre FROM select_sses
				ORDER BY cdnom),
				
         infos_fiche AS (
             SELECT fiche.idfiche,
                    fiche.idorg,
                    organisme.organisme,
                    fiche.idetude,
                    etude.etude,
                    fiche.typedon,
                    CASE
                        WHEN ((fiche.typedon)::text = 'Pr'::text) THEN 'Origine privée'::text
                        WHEN ((fiche.typedon)::text = 'Pu'::text) THEN 'Origine publique'::text
                        WHEN ((fiche.typedon)::text = 'NSP'::text) THEN 'Inconnue'::text
                        ELSE NULL::text
                        END                 AS type_donnee,
                    fiche.plusobser,
                    fiche.codecom,
                    commune.geom_geojson AS comgeojson,
                    fiche.floutage,
                    CASE
                        WHEN (fiche.floutage = 0) THEN 'Pas de dégradation'::text
                        WHEN (fiche.floutage = 1) THEN 'Commune'::text
                        WHEN (fiche.floutage = 2) THEN 'Maille 10kmx10km'::text
                        WHEN (fiche.floutage = 3) THEN 'Département'::text
                        ELSE NULL::text
                        END                 AS floutage_kollect,
                    CASE
                        WHEN (fiche.localisation = '1'::smallint) THEN 'Coordonnée'::text
                        WHEN (fiche.localisation = '2'::smallint) THEN 'Commune'::text
                        WHEN (fiche.localisation = '3'::smallint) THEN 'Département'::text
                        WHEN (fiche.localisation = '4'::smallint) THEN 'Emprise de site'::text
                        ELSE NULL::text
                        END                 AS precision_saisie,
                    fiche.idcoord,
                    commune.commune,
                    site.idsite,
                    site.site,
                    fiche.iddep,
					departement.departement,
                    departement.geom_geojson AS depgeojson,
                    fiche.date1,
                    fiche.date2,
                    fiche.decade,
                    fs.hdebut,
                    fs.hfin,
                    fs.tempdebut,
                    fs.tempfin,
                    fiche.idobser           AS idmainobser,
                    observateur.observateur AS mainobser,
                    CASE
                        WHEN ((fiche.plusobser)::text = 'oui'::text) THEN (
                                ((observateur.observateur)::text || ', '::text) || concat_otherobser.otherobser)
                        WHEN ((fiche.plusobser)::text = 'non'::text) THEN (observateur.observateur)::text
                        ELSE NULL::text
                        END                 AS observateur,
                    CASE
                        WHEN ((fiche.plusobser)::text = 'oui'::text) THEN (
                                (((fiche.idobser)::character varying)::text || ', '::text) ||
                                concat_otherobser.idotherobser)
                        WHEN ((fiche.plusobser)::text = 'non'::text) THEN ((fiche.idobser)::character varying)::text
                        ELSE NULL::text
                        END                 AS idobservateur,
                    CASE
                        WHEN (cg.geo ~~ '%Polygon%'::text) THEN 'Polygone'::text
                        WHEN (cg.geo ~~ '%LineString%'::text) THEN 'Linéaire'::text
                        WHEN (cg.geo IS NULL) THEN 'Point'::text
                        ELSE NULL::text
                        END                 AS type_geometrie,
                    c_1.x,
                    c_1.y,
                    c_1.lng,
                    c_1.lat,
                    c_1.codel93,
                    c_1.codel935,
                    cp.lbpreci              AS precision_coord
             FROM (((((((((((obs.fiche
                 LEFT JOIN concat_otherobser ON ((concat_otherobser.idfiche = fiche.idfiche)))
                 JOIN referentiel.commune ON (((commune.codecom)::text = (fiche.codecom)::text)))
                 LEFT JOIN obs.site ON ((site.idsite = fiche.idsite)))
                 JOIN obs.coordonnee c_1 ON ((c_1.idcoord = fiche.idcoord)))
                 JOIN referentiel.observateur ON ((observateur.idobser = fiche.idobser)))
                 JOIN referentiel.organisme ON ((organisme.idorg = fiche.idorg)))
                 JOIN referentiel.etude ON ((etude.idetude = fiche.idetude)))
                 JOIN referentiel.coordprecision cp ON ((cp.idpreci = fiche.idpreci)))
				 JOIN referentiel.departement ON ((departement.iddep = fiche.iddep)))
                 LEFT JOIN obs.coordgeo cg ON ((cg.idcoord = c_1.idcoord)))
                  LEFT JOIN obs.fichesup fs ON ((fs.idfiche = fiche.idfiche)))
             ORDER BY fiche.idfiche
         ),
         infos_obs AS (
             SELECT infos_fiche.idfiche,
                    obs.idobs,
                    obs.cdnom,
                    obs.cdref,
                    infos_fiche.date1                                            AS date_debut_obs,
                    infos_fiche.date2                                            AS date_fin_obs,
                    infos_fiche.decade,
                    CASE
                        WHEN (se.sensible IS NOT NULL) THEN 'oui'::text
                        ELSE 'non'::text
                        END                                                      AS taxon_sensible,
                    CASE
                        WHEN (se.sensible = 1) THEN 'Commune'::text
                        WHEN (se.sensible = 2) THEN 'Maille 10kmx10km'::text
                        WHEN (se.sensible = 3) THEN 'Département'::text
                        ELSE NULL::text
                        END                                                      AS floutage_sensible,
                    'TAXREF v12.0'::text                                         AS referentiel,
                    obs.nom_cite,
                    hos.date_insert                                              AS date_insertion,
                    hos.date_update                                              AS date_derniere_modif,
                    obs.rqobs,
					liencom.nbcom AS nb_commentaires,
                    p_1.protocole                                                AS type_acquisition,
                    obs.statutobs,
                    CASE
                        WHEN ((obs.statutobs)::text = 'No'::text) THEN 'Non observé'::text
                        WHEN ((obs.statutobs)::text = 'Pr'::text) THEN 'Présent'::text
                        WHEN ((obs.statutobs)::text = 'NR'::text) THEN 'Inconnu'::text
                        ELSE 'Inconnu'::text
                        END                                                      AS statut_observation,
                    obs.validation                                               AS code_validation,
                    CASE
                        WHEN (obs.validation = 1) THEN '1 - Certain - très probable'::text
                        WHEN (obs.validation = 2) THEN '2 - Probable'::text
                        WHEN (obs.validation = 3) THEN '3 - Douteux'::text
                        WHEN (obs.validation = 4) THEN '4 - Invalide'::text
                        WHEN (obs.validation = 5) THEN '5 - Non réalisable'::text
                        WHEN (obs.validation = 6) THEN '6 - Non évalué (validation en cours)'::text
                        ELSE NULL::text
                        END                                                      AS statut_validation,
                    iv.validateur,
                    CASE
                        WHEN (iv.typevali IS NOT NULL) THEN iv.dateval
                        WHEN ((iv.typevali IS NULL) AND (obs.validation = 1)) THEN hos.date_insert
                        ELSE NULL::date
                        END                                                      AS date_validation,
                    CASE
                        WHEN (iv.typevali = 1) THEN 'Validation informatique'::text
                        WHEN (iv.typevali = 2) THEN 'Validation manuelle'::text
                        WHEN ((obs.validation = 1) AND (iv.typevali IS NULL)) THEN 'Non soumis à validation'::text
                        ELSE NULL::text
                        END                                                      AS type_validation,
                    liste.nom                                                    AS nomlatin,
                    ((liste.nom || ' '::text) || liste.auteur)                   AS nomlatincomplet,
                    liste.nomvern,
                    tx.nomvern                                                   AS nomverncomplet,
                    liste.observatoire,
                    liste.rang,
                    tx.regne,
                    tx.classe,
                    tx.ordre,
                    tx.famille,
					infos_genre.genre,
                    (((membre.nom)::text || ' '::text) || (membre.prenom)::text) AS numerisateur,
                    infos_fiche.idmainobser,
                    infos_fiche.idobservateur,
                    infos_fiche.observateur,
                    observateur.observateur                                      AS determinateur,
                    oc.typedet                                                   AS type_determination,
                    CASE
                        WHEN (oc.iddetcol IS NULL) THEN 'non'::text
                        WHEN (oc.iddetcol IS NOT NULL) THEN 'oui'::text
                        ELSE NULL::text
                        END                                                      AS en_collection,
                    infos_fiche.idorg,
                    infos_fiche.organisme,
                    infos_fiche.idetude,
                    infos_fiche.etude,
                    infos_fiche.typedon,
                    infos_fiche.type_donnee,
                    infos_fiche.codecom,
                    infos_fiche.comgeojson,
                    infos_fiche.commune,
                    infos_fiche.idsite,
                    infos_fiche.site,
                    infos_fiche.iddep,
					infos_fiche.departement,
                    infos_fiche.depgeojson,
                    infos_fiche.date1,
                    infos_fiche.date2,
                    infos_fiche.hdebut,
                    infos_fiche.hfin,
                    infos_fiche.tempdebut,
                    infos_fiche.tempfin,
                    infos_fiche.idcoord,
                    infos_fiche.type_geometrie,
                    infos_fiche.precision_saisie,
                    infos_fiche.x,
                    infos_fiche.y,
                    infos_fiche.lng,
                    infos_fiche.lat,
                    infos_fiche.precision_coord,
                    infos_fiche.floutage,
                    infos_fiche.floutage_kollect,
					CASE 
						WHEN se.sensible > infos_fiche.floutage THEN se.sensible
						WHEN se.sensible < infos_fiche.floutage THEN infos_fiche.floutage
						WHEN se.sensible IS NULL AND infos_fiche.floutage = 0 THEN 0
						WHEN se.sensible IS NULL AND infos_fiche.floutage > 0 THEN infos_fiche.floutage
						WHEN se.sensible = infos_fiche.floutage THEN se.sensible
					END as floutage_final,
                    infos_fiche.codel93,
                    infos_fiche.codel935
             FROM (((((((((((((infos_fiche
                 JOIN obs.obs ON ((obs.idfiche = infos_fiche.idfiche)))
                 JOIN referentiel.liste ON ((liste.cdnom = obs.cdref)))
                 LEFT JOIN referentiel.taxref tx ON ((tx.cdnom = obs.cdref)))
                 JOIN referentiel.observateur ON ((obs.iddet = observateur.idobser)))
                 LEFT JOIN site.membre ON ((obs.idmor = membre.idmembre)))
                 LEFT JOIN infos_validateur iv ON (((iv.idobs = obs.idobs) AND (iv.vali = obs.validation))))
                 LEFT JOIN referentiel.protocole p_1 ON ((p_1.idprotocole = obs.idprotocole)))
                 LEFT JOIN obs_historique.histo_obs_synthese hos ON ((hos.idobs = obs.idobs)))
                 LEFT JOIN infos_collection ic ON ((ic.idobs = obs.idobs)))
                 LEFT JOIN obs.obscoll oc ON ((oc.idcol = ic.idcol)))
                 LEFT JOIN referentiel.sensible se ON ((se.cdnom = obs.cdref)))
				 LEFT JOIN site.liencom ON ((obs.idobs = liencom.idobs)))
				 LEFT JOIN infos_genre ON ((obs.cdref = infos_genre.cdnom)))
             ORDER BY infos_fiche.idfiche, obs.idobs
         )
    SELECT i.idfiche,
           i.idobs,
           l.idligne,
           now()                                                      AS actualisation,
           i.code_validation,
           i.statut_validation,
           i.date_debut_obs,
           i.date_fin_obs,
           i.hdebut,
           i.hfin,
           i.decade,
           i.cdnom,
           i.cdref,
           i.referentiel,
           i.taxon_sensible,
           i.floutage_sensible,
           i.nom_cite,
           i.rang,
           i.regne,
           i.classe,
           i.ordre,
           i.famille,
		   i.genre,
           i.observatoire,
           i.nomlatin,
           i.nomlatincomplet,
           i.nomvern,
           i.nomverncomplet,
           i.idmainobser,
           i.observateur,
           i.idobservateur,
           i.determinateur,
           i.numerisateur,
           i.type_determination,
           i.en_collection,
           i.idorg,
           i.organisme,
           i.idetude,
           i.etude,
           i.typedon,
           i.type_donnee,
           i.iddep,
		   i.departement,
           i.floutage_kollect,
		      CASE
                 WHEN (i.floutage_final = 0) THEN 'Pas de dégradation'::text
                 WHEN (i.floutage_final = 1) THEN 'Commune'::text
                 WHEN (i.floutage_final = 2) THEN 'Maille 10kmx10km'::text
                 WHEN (i.floutage_final = 3) THEN 'Département'::text
                 ELSE NULL::text
                 END AS floutage_final,
           i.type_geometrie,
           i.precision_saisie,
           i.precision_coord,
           i.type_acquisition,
           i.statutobs,
           i.statut_observation,
           oe.etatbio,
           occmort.cause                                              AS cause_mort,
           s.stade,
           ((l.nbmin + l.nbmax) / 2)                                  AS nb_tot,
           l.ndiff,
           l.male,
           l.femelle,
           l.nbmin,
           l.nbmax,
           CASE
               WHEN ((l.denom)::text = 'Co'::text) THEN 'Compté'::text
               WHEN ((l.denom)::text = 'Es'::text) THEN 'Estimé'::text
               WHEN ((l.denom)::text = 'NSP'::text) THEN 'Non Renseigné'::text
               ELSE NULL::text
               END                                                    AS denom,
           ot.typedenom,
           m.methode,
           p.prospection,
           c.libcomp                                                  AS comportement,
           ipl.cdnom                                                  AS cdnom_plante_associee,
           ipl.nomlatincomplet                                        AS nomlatin_plante_associee,
           os.statutbio,
           aves.code                                                  AS code_reproduction,
           i.rqobs AS remarque_obs,
		   CASE WHEN i.nb_commentaires IS NULL THEN 0 ELSE i.nb_commentaires END AS nb_commentaires,
           eunis.lbcode                                               AS code_habitat,
           (((eunis.lbcode)::text || ' - '::text) || eunis.lbhabitat) AS nom_habitat,
           CASE
               WHEN (ip.idobs IS NULL) THEN 'non'::text
               ELSE 'oui'::text
               END                                                    AS photo,
           CASE
               WHEN (son.idobs IS NULL) THEN 'non'::text
               ELSE 'oui'::text
               END                                                    AS son,
           i.tempdebut,
           i.tempfin,
           i.date_insertion,
           i.date_derniere_modif,
           i.date_validation,
           i.type_validation,
           i.validateur,
           CASE
               WHEN i.floutage_final > 1 THEN NULL::character varying
			   ELSE i.codecom
               END AS codecom,
           CASE
               WHEN i.floutage_final > 1 THEN NULL::character varying
               ELSE i.commune
               END AS commune,
           CASE
               WHEN i.floutage_final > 0 THEN NULL::character varying
               ELSE i.idsite::character varying
               END AS id_station,
           CASE
               WHEN i.floutage_final > 0 THEN NULL::character varying
               ELSE i.site
               END AS nom_station,
           CASE
               WHEN i.floutage_final > 0 THEN NULL::character varying
               ELSE (i.lng)::character varying
               END AS lng,
           CASE
               WHEN i.floutage_final > 0 THEN NULL::character varying
               ELSE (i.lat)::character varying
               END AS lat,
           CASE
               WHEN i.floutage_final > 0 THEN NULL::character varying
               ELSE (i.x)::character varying
               END AS x,
           CASE
               WHEN i.floutage_final > 0 THEN NULL::character varying
               ELSE (i.y)::character varying
               END AS y,
           CASE
			   WHEN i.floutage_final = 2 THEN i.codel93
               ELSE NULL::character varying
               END AS maille10x10km,
               NULL AS maille5x5km,
               NULL AS idcoord,
           CASE
				WHEN i.floutage_final = 1 THEN i.comgeojson
				WHEN i.floutage_final = 2 THEN 
				'{"type":"Feature","properties":{},"geometry":' ||
				st_asGeoJSON(st_transform(St_GeomFromText(
				'POLYGON((' ||
				(substring(codel93, 2, 3)::integer * 10000)::double precision || ' ' || (substring(codel93::text, 6, 3)::integer * 10000)::double precision
				|| ',' || (substring(codel93, 2, 3)::integer * 10000)::double precision || ' ' || ((substring(codel93::text, 6, 3)::integer  * 10000) + 10000)::double precision
				|| ',' || ((substring(codel93, 2, 3)::integer * 10000) + 10000)::double precision || ' ' || ((substring(codel93::text, 6, 3)::integer  * 10000) + 10000)::double precision
				|| ',' || ((substring(codel93, 2, 3)::integer * 10000) + 10000)::double precision || ' ' || (substring(codel93::text, 6, 3)::integer  * 10000)::double precision
				|| ',' || (substring(codel93, 2, 3)::integer * 10000)::double precision || ' ' || (substring(codel93::text, 6, 3)::integer * 10000)::double precision || '))',2154),4326))
				|| '}'
				WHEN i.floutage_final =  3 THEN i.depgeojson END AS geom_geojson,
			CASE
				WHEN i.floutage_final = 1 THEN i.commune
				WHEN i.floutage_final = 2 THEN i.codel93 || ' (Maille 10x10km)'
				WHEN i.floutage_final = 3 THEN i.departement
				END AS localisation
			
    FROM ((((((((((((((((infos_obs i
        LEFT JOIN obs.ligneobs l ON ((l.idobs = i.idobs)))
        LEFT JOIN referentiel.stade s ON ((s.idstade = l.stade)))
        LEFT JOIN referentiel.occtype ot ON (((ot.tdenom)::text = (l.tdenom)::text)))
        LEFT JOIN referentiel.occetatbio oe ON ((oe.idetatbio = l.idetatbio)))
        LEFT JOIN referentiel.methode m ON ((m.idmethode = l.idmethode)))
        LEFT JOIN referentiel.prospection p ON ((p.idpros = l.idpros)))
        LEFT JOIN referentiel.comportement c ON ((c.idcomp = l.idcomp)))
        LEFT JOIN referentiel.occstatutbio os ON ((os.idstbio = l.idstbio)))
        LEFT JOIN obs.aves ON (((aves.idobs = i.idobs) AND (aves.stade = l.stade))))
        LEFT JOIN infos_photo ip ON ((ip.idobs = i.idobs)))
        LEFT JOIN infos_son son ON ((son.idobs = i.idobs)))
        LEFT JOIN obs.obshab ON ((obshab.idobs = i.idobs)))
        LEFT JOIN referentiel.eunis ON ((obshab.cdhab = eunis.cdhab)))
        LEFT JOIN obs.obsmort om ON (((om.idobs = i.idobs) AND (om.stade = l.stade))))
        LEFT JOIN infos_plante ipl ON (((ipl.idobs = i.idobs) AND (ipl.stade = l.stade))))
        LEFT JOIN referentiel.occmort ON ((om.mort = occmort.idmort)))
    WHERE ((i.taxon_sensible = 'oui'::text) OR (i.floutage_kollect <> 'Pas de dégradation'::text))
        ORDER BY i.idobs
        , l.idligne;
