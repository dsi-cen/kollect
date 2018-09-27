CREATE OR REPLACE FUNCTION vali.validation(
    integer,
    integer)
  RETURNS text AS
$BODY$	
DECLARE
	varidobs alias FOR $1;
	vartype alias FOR $2;
	varvali integer; varcdnom integer; vardeux text; 
BEGIN
	WITH obs AS (
		SELECT idobs, codel93, decade, idobser, cdref FROM obs.obs
		INNER JOIN obs.fiche USING(idfiche)
		LEFT JOIN obs.coordonnee USING(idcoord)
		WHERE idobs = varidobs
	),
	critere AS (
		SELECT cdref, nb, codesl93, decades, obsers FROM vali.grille
		INNER JOIN obs USING(cdref)
	),
	bilan AS (
		SELECT idobs, cdref,
			CASE WHEN codel93 = ANY(codesl93) THEN 'ok' ELSE 'non' END AS codel93,
			CASE WHEN decade = ANY(decades) THEN 'ok' ELSE 'non' END AS decade,
			CASE WHEN idobser = ANY(obsers) THEN 'ok' ELSE 'non' END AS observateur
		FROM obs
		LEFT JOIN critere USING(cdref)
	),
	result AS (
		SELECT idobs, cdref, CASE WHEN CONCAT(codel93,decade,observateur) ILIKE '%non%' THEN 6 ELSE 1 END AS vali, CONCAT(' Détail - codel93: ', codel93, ', decade: ',decade, ', observateur: ',observateur) AS decision FROM bilan
	)
	SELECT vali, cdref, decision INTO varvali, varcdnom, vardeux FROM result;
	IF vartype = 1 THEN
		IF varvali = '1' THEN
			UPDATE obs.obs SET validation = 1 WHERE idobs = varidobs;
		END IF;
		INSERT INTO vali.histovali (idobs, cdnom, dateval, vali, decision, idm, typevali)
		VALUES (varidobs, varcdnom, now()::date, varvali, CONCAT('Validation automatique du ',to_char(now()::date,'dd/mm/yy')::text, ' à ',to_char(now()::time,'HH24:MI'), vardeux), 0, 1);		
	ELSE
		INSERT INTO vali.histovali (idobs, cdnom, dateval, vali, decision, idm, typevali)
		VALUES (varidobs, varcdnom, now()::date, 6, CONCAT('Filtre automatique du ',to_char(now()::date,'dd/mm/yy')::text, ' à ',to_char(now()::time,'HH24:MI'), vardeux), 0, 1);
	END IF;
	RETURN varvali;	
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE OR REPLACE FUNCTION vali.majgrille()
  RETURNS trigger AS
$BODY$ declare
	BEGIN
		DELETE FROM vali.grille WHERE cdref = NEW.cdnom;
		INSERT INTO vali.grille
		SELECT cdref, count(idobs) as nb, array_agg(DISTINCT codel93) AS codel93, array_agg(DISTINCT decade) AS decade, array_agg(DISTINCT idobser) AS obser from obs.obs
		INNER JOIN obs.fiche USING(idfiche)
		INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
		INNER JOIN obs.ligneobs USING(idobs)
		WHERE (validation = 1 OR validation = 2) AND date1 = date2 AND idetatbio != 3 AND cdref = NEW.cdnom
		GROUP BY cdref;
		RETURN NULL; 
	END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER majgrillev
  AFTER INSERT
  ON vali.histovali
  FOR EACH ROW
  EXECUTE PROCEDURE vali.majgrille();  