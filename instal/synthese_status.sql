create materialized view statut.dh as
    WITH select_liste_statut_dh AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.idreg,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'DH'::text)
        ORDER BY libelle.idreg, libelle.annee
    ),
         select_statut_dh AS (
             SELECT statut.cdnom,
                    select_liste_statut_dh.cdprotect,
                    libelle.article
             FROM ((select_liste_statut_dh
                 LEFT JOIN statut.statut ON (((statut.cdprotect)::text = (select_liste_statut_dh.cdprotect)::text)))
                      LEFT JOIN statut.libelle ON (((libelle.cdprotect)::text = (statut.cdprotect)::text)))
             ORDER BY statut.cdnom, libelle.article
         )
    SELECT select_statut_dh.cdnom,
           select_statut_dh.article AS dh
    FROM select_statut_dh;


create materialized view statut.lre as
    WITH select_liste_statut_lre AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'LRE'::text)
    ),
         select_statut_lre AS (
             SELECT statut.cdnom,
                    select_liste_statut_lre.cdprotect,
                    statut.lr,
                    select_liste_statut_lre.annee
             FROM (select_liste_statut_lre
                      LEFT JOIN statut.statut
                                ON (((statut.cdprotect)::text = (select_liste_statut_lre.cdprotect)::text)))
             WHERE ((select_liste_statut_lre.type)::text = 'LRE'::text)
             ORDER BY statut.cdnom
         )
    SELECT select_statut_lre.cdnom,
           select_statut_lre.lr AS lre
    FROM select_statut_lre
    ORDER BY select_statut_lre.cdnom;

create materialized view statut.lrf as
    WITH select_liste_statut_lrf AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'LRF'::text)
    ),
         select_statut_lrf AS (
             SELECT statut.cdnom,
                    select_liste_statut_lrf.cdprotect,
                    CASE
                        WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISNICH'::text) THEN 1
                        WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISHIV'::text) THEN 2
                        WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISPASS'::text) THEN 3
                        ELSE NULL::integer
                        END AS ordre_lr_ois,
                    CASE
                        WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISNICH'::text)
                            THEN (((statut.lr)::text || ' (Nicheur)'::text))::character varying
                        WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISHIV'::text)
                            THEN (((statut.lr)::text || ' (Hivernant)'::text))::character varying
                        WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISPASS'::text)
                            THEN (((statut.lr)::text || ' (De passage)'::text))::character varying
                        ELSE statut.lr
                        END AS lr,
                    select_liste_statut_lrf.annee
             FROM (select_liste_statut_lrf
                      LEFT JOIN statut.statut
                                ON (((statut.cdprotect)::text = (select_liste_statut_lrf.cdprotect)::text)))
             WHERE ((select_liste_statut_lrf.type)::text = 'LRF'::text)
             ORDER BY statut.cdnom,
                      CASE
                          WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISNICH'::text) THEN 1
                          WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISHIV'::text) THEN 2
                          WHEN ((select_liste_statut_lrf.cdprotect)::text = 'LRFOISPASS'::text) THEN 3
                          ELSE NULL::integer
                          END
         )
    SELECT select_statut_lrf.cdnom,
           select_statut_lrf.lr AS lrf
    FROM select_statut_lrf
    ORDER BY select_statut_lrf.cdnom;

create materialized view statut.lrm as
    WITH select_liste_statut_lrm AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'LRM'::text)
    ),
         select_statut_lrm AS (
             SELECT statut.cdnom,
                    select_liste_statut_lrm.cdprotect,
                    statut.lr,
                    select_liste_statut_lrm.annee
             FROM (select_liste_statut_lrm
                      LEFT JOIN statut.statut
                                ON (((statut.cdprotect)::text = (select_liste_statut_lrm.cdprotect)::text)))
             WHERE ((select_liste_statut_lrm.type)::text = 'LRM'::text)
             ORDER BY statut.cdnom
         )
    SELECT select_statut_lrm.cdnom,
           select_statut_lrm.lr AS lrm
    FROM select_statut_lrm
    ORDER BY select_statut_lrm.cdnom;

create materialized view statut.lrr as
    WITH select_liste_statut_lrr AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.idreg,
                        CASE
                            WHEN (libelle.idreg = 72) THEN 'Aquitaine'::text
                            WHEN (libelle.idreg = 52) THEN 'Pays de la Loire'::text
                            WHEN (libelle.idreg = 54) THEN 'Poitou-Charentes'::text
                            WHEN (libelle.idreg = 74) THEN 'Limousin'::text
                            ELSE 'Erreur région'::text
                            END AS nomreg,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'LRR'::text)
        ORDER BY libelle.idreg, libelle.annee
    ),
         select_statut_lrr AS (
             SELECT statut.cdnom,
                    select_liste_statut_lrr.cdprotect,
                    select_liste_statut_lrr.idreg,
                    select_liste_statut_lrr.nomreg,
                    statut.lr,
                    select_liste_statut_lrr.annee
             FROM (select_liste_statut_lrr
                      LEFT JOIN statut.statut
                                ON (((statut.cdprotect)::text = (select_liste_statut_lrr.cdprotect)::text)))
             WHERE (((select_liste_statut_lrr.type)::text = 'LRR'::text) AND
                    (select_liste_statut_lrr.idreg IS NOT NULL))
             ORDER BY statut.cdnom, select_liste_statut_lrr.nomreg
         )
    SELECT select_statut_lrr.cdnom,
           select_statut_lrr.idreg,
           ((((select_statut_lrr.lr)::text || ' ('::text) || select_statut_lrr.nomreg) || ')'::text) AS lrr
    FROM select_statut_lrr
    ORDER BY select_statut_lrr.cdnom, select_statut_lrr.nomreg;

create materialized view statut.pd as
    WITH select_liste_statut_pd AS (
        SELECT DISTINCT statutsite.cdprotect,
                        libelle.iddep
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'PD'::text)
        ORDER BY libelle.iddep
    ),
         select_statut_pd AS (
             SELECT statut.cdnom,
                    select_liste_statut_pd.cdprotect,
                    select_liste_statut_pd.iddep,
                    libelle.article
             FROM ((select_liste_statut_pd
                 LEFT JOIN statut.statut ON (((statut.cdprotect)::text = (select_liste_statut_pd.cdprotect)::text)))
                      LEFT JOIN statut.libelle ON (((libelle.cdprotect)::text = (statut.cdprotect)::text)))
             ORDER BY statut.cdnom
         )
    SELECT select_statut_pd.cdnom,
           (((select_statut_pd.iddep || ' ('::text) || (select_statut_pd.article)::text) || ')'::text) AS pd
    FROM select_statut_pd
    ORDER BY select_statut_pd.cdnom, select_statut_pd.iddep;

create materialized view statut.pn as
    WITH select_liste_statut_pn AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.idreg,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'PN'::text)
        ORDER BY libelle.idreg, libelle.annee
    ),
         select_statut_pn AS (
             SELECT statut.cdnom,
                    select_liste_statut_pn.cdprotect,
                    libelle.article
             FROM ((select_liste_statut_pn
                 LEFT JOIN statut.statut ON (((statut.cdprotect)::text = (select_liste_statut_pn.cdprotect)::text)))
                      LEFT JOIN statut.libelle ON (((libelle.cdprotect)::text = (statut.cdprotect)::text)))
             ORDER BY statut.cdnom, libelle.article
         )
    SELECT select_statut_pn.cdnom,
           select_statut_pn.article AS pn
    FROM select_statut_pn;

create materialized view statut.pr as
    WITH select_liste_statut_pr AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.idreg,
                        CASE
                            WHEN (libelle.idreg = 72) THEN 'Aquitaine'::text
                            WHEN (libelle.idreg = 52) THEN 'Pays de la Loire'::text
                            WHEN (libelle.idreg = 54) THEN 'Poitou-Charentes'::text
                            WHEN (libelle.idreg = 74) THEN 'Limousin'::text
                            ELSE 'Erreur région'::text
                            END AS nomreg,
                        libelle.article,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'PR'::text)
    ),
         select_statut_pn AS (
             SELECT statut.cdnom,
                    select_liste_statut_pr.cdprotect,
                    select_liste_statut_pr.idreg,
                    select_liste_statut_pr.nomreg,
                    select_liste_statut_pr.article
             FROM (select_liste_statut_pr
                      LEFT JOIN statut.statut ON (((statut.cdprotect)::text = (select_liste_statut_pr.cdprotect)::text)))
             ORDER BY statut.cdnom, select_liste_statut_pr.nomreg
         )
    SELECT select_statut_pn.cdnom,
           select_statut_pn.idreg,
           (((select_statut_pn.nomreg || ' ('::text) || (select_statut_pn.article)::text) || ')'::text) AS pr
    FROM select_statut_pn
    ORDER BY select_statut_pn.cdnom;

create materialized view statut.znieff as
    WITH select_liste_statut_znieff AS (
        SELECT DISTINCT statutsite.cdprotect,
                        statutsite.type,
                        libelle.idreg,
                        CASE
                                WHEN (libelle.idreg = 41) THEN 'Lorraine'::text
                                WHEN (libelle.idreg = 54) THEN 'Poitou-Charentes'::text
                                WHEN (libelle.idreg = 73) THEN 'Midi-Pyrénées'::text
                                WHEN (libelle.idreg = 82) THEN 'Rhône-Alpes'::text
                                WHEN (libelle.idreg = 72) THEN 'Aquitaine'::text
                                WHEN (libelle.idreg = 43) THEN 'Franche-Comté'::text
                                WHEN (libelle.idreg = 23) THEN 'Haute-Normandie'::text
                                WHEN (libelle.idreg = 25) THEN 'Basse-Normandie'::text
                                WHEN (libelle.idreg = 31) THEN 'Nord-Pas-de-Calais'::text
                                WHEN (libelle.idreg = 22) THEN 'Picardie'::text
                                WHEN (libelle.idreg = 21) THEN 'Champagne-Ardenne'::text
                                WHEN (libelle.idreg = 52) THEN 'Pays de la Loire'::text
                                WHEN (libelle.idreg = 53) THEN 'Bretagne'::text
                                WHEN (libelle.idreg = 91) THEN 'Languedoc-Roussillon'::text
                                WHEN (libelle.idreg = 93) THEN 'Provence-Alpes-Côte d''Azur'::text
                                WHEN (libelle.idreg = 11) THEN 'Ile-de-France'::text
                                WHEN (libelle.idreg = 26) THEN 'Bourgogne'::text
                                WHEN (libelle.idreg = 83) THEN 'Auvergne'::text
                                WHEN (libelle.idreg = 42) THEN 'Alsace'::text
                                WHEN (libelle.idreg = 32) THEN 'Hauts-de-France'::text
                                WHEN (libelle.idreg = 44) THEN 'Grand Est'::text
                                WHEN (libelle.idreg = 75) THEN 'Nouvelle-Aquitaine'::text
                                WHEN (libelle.idreg = 76) THEN 'Occitanie'::text
                                WHEN (libelle.idreg = 84) THEN 'Auvergne-Rhône-Alpes'::text
                                WHEN (libelle.idreg = 28) THEN 'Normandie'::text
                                WHEN (libelle.idreg = 24) THEN 'Centre-Val de Loire'::text
                                WHEN (libelle.idreg = 27) THEN 'Bourgogne-Franche-Comté'::text
                                WHEN (libelle.idreg = 74) THEN 'Limousin'::text
                                WHEN (libelle.idreg = 94) THEN 'Corse'::text
                            ELSE 'Erreur région'::text
                            END AS nomreg,
                        libelle.annee
        FROM (statut.statutsite
                 JOIN statut.libelle ON (((libelle.cdprotect)::text = (statutsite.cdprotect)::text)))
        WHERE ((statutsite.type)::text = 'Z'::text)
        ORDER BY libelle.idreg, libelle.annee
    ),
         select_statut_znieff AS (
             SELECT statut.cdnom,
                    select_liste_statut_znieff.cdprotect,
                    select_liste_statut_znieff.idreg,
                    select_liste_statut_znieff.nomreg,
                    select_liste_statut_znieff.annee
             FROM (select_liste_statut_znieff
                      LEFT JOIN statut.statut
                                ON (((statut.cdprotect)::text = (select_liste_statut_znieff.cdprotect)::text)))
             WHERE (((select_liste_statut_znieff.type)::text = 'Z'::text) AND
                    (select_liste_statut_znieff.idreg IS NOT NULL))
             ORDER BY statut.cdnom, select_liste_statut_znieff.nomreg
         )
    SELECT select_statut_znieff.cdnom,
           select_statut_znieff.idreg,
           select_statut_znieff.nomreg AS znieff
    FROM select_statut_znieff
    ORDER BY select_statut_znieff.cdnom, select_statut_znieff.nomreg;

create materialized view statut.statut_synthese as
    WITH select_pr AS (
        SELECT pr.cdnom,
               string_agg(pr.pr, ', '::text) AS pr
        FROM statut.pr
        GROUP BY pr.cdnom
    ),
         select_pd AS (
             SELECT pd.cdnom,
                    string_agg(pd.pd, ', '::text) AS pd
             FROM statut.pd
             GROUP BY pd.cdnom
         ),
         select_dh AS (
             SELECT dh.cdnom,
                    string_agg((dh.dh)::text, ', '::text) AS dh
             FROM statut.dh
             GROUP BY dh.cdnom
         ),
         select_lrf AS (
             SELECT lrf.cdnom,
                    string_agg((lrf.lrf)::text, ', '::text) AS lrf
             FROM statut.lrf
             GROUP BY lrf.cdnom
         ),
         select_lrr AS (
             SELECT lrr.cdnom,
                    string_agg(lrr.lrr, ', '::text) AS lrr
             FROM statut.lrr
             GROUP BY lrr.cdnom
         ),
         select_znieff AS (
             SELECT znieff.cdnom,
                    string_agg(znieff.znieff, ', '::text) AS znieff
             FROM statut.znieff
             GROUP BY znieff.cdnom
         )
    SELECT liste.cdnom AS cdnom_status,
           pn.pn,
           select_pr.pr,
           select_pd.pd,
           select_dh.dh,
           lrm.lrm,
           lre.lre,
           select_lrf.lrf,
           select_lrr.lrr,
           select_znieff.znieff
    FROM (((((((((referentiel.liste
        LEFT JOIN statut.pn ON ((pn.cdnom = liste.cdnom)))
        LEFT JOIN select_pr ON ((select_pr.cdnom = liste.cdnom)))
        LEFT JOIN select_pd ON ((select_pd.cdnom = liste.cdnom)))
        LEFT JOIN select_dh ON ((select_dh.cdnom = liste.cdnom)))
        LEFT JOIN statut.lrm ON ((lrm.cdnom = liste.cdnom)))
        LEFT JOIN statut.lre ON ((lre.cdnom = liste.cdnom)))
        LEFT JOIN select_lrf ON ((select_lrf.cdnom = liste.cdnom)))
        LEFT JOIN select_lrr ON ((select_lrr.cdnom = liste.cdnom)))
        LEFT JOIN select_znieff ON ((select_znieff.cdnom = liste.cdnom)));