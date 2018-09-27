ALTER TABLE obs.obs
ADD CONSTRAINT obs_idfiche_fk
FOREIGN KEY (idfiche) REFERENCES obs.fiche (idfiche);

ALTER TABLE obs.obs
ADD CONSTRAINT obs_idprotocole_fk
FOREIGN KEY (idprotocole) REFERENCES referentiel.protocole (idprotocole);

ALTER TABLE obs.obs
ADD CONSTRAINT obs_idetude_fk
FOREIGN KEY (idetude) REFERENCES referentiel.etude (idetude);

ALTER TABLE obs.obs
ADD CONSTRAINT obs_idmor_fk
FOREIGN KEY (idmor) REFERENCES site.membre (idmembre);

ALTER TABLE obs.coordonnee
ADD CONSTRAINT coordonnee_maillel93_fk
FOREIGN KEY (codel93) REFERENCES referentiel.maillel93 (codel93);

ALTER TABLE obs.fiche
ADD CONSTRAINT fiche_idcoord_fk
FOREIGN KEY (idcoord) REFERENCES obs.coordonnee (idcoord);

ALTER TABLE obs.fiche
ADD CONSTRAINT fiche_idobser_fk
FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser);

ALTER TABLE obs.fiche
ADD CONSTRAINT fiche_codecom_fk
FOREIGN KEY (codecom) REFERENCES referentiel.commune (codecom);

ALTER TABLE obs.fiche
ADD CONSTRAINT fiche_iddep_fk
FOREIGN KEY (iddep) REFERENCES referentiel.departement (iddep);

ALTER TABLE obs.fichesup
ADD CONSTRAINT fichesup_idfiche_fk
FOREIGN KEY (idfiche) REFERENCES obs.fiche (idfiche);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_idobs_fk
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_stade_fk
FOREIGN KEY (stade) REFERENCES referentiel.stade (idstade);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_idetatbio_fk
FOREIGN KEY (idetatbio) REFERENCES referentiel.occetatbio (idetatbio);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_idmethode_fk
FOREIGN KEY (idmethode) REFERENCES referentiel.methode (idmethode);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_idpros_fk
FOREIGN KEY (idpros) REFERENCES referentiel.prospection (idpros);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_idstbio_fk
FOREIGN KEY (idstbio) REFERENCES referentiel.occstatutbio (idstbio);

ALTER TABLE obs.ligneobs
ADD CONSTRAINT ligneobs_tdenom_fk
FOREIGN KEY (tdenom) REFERENCES referentiel.occtype (tdenom);

ALTER TABLE obs.coordgeo
ADD CONSTRAINT coordgeo_idcoord_fk
FOREIGN KEY (idcoord) REFERENCES obs.coordonnee (idcoord);

ALTER TABLE obs.obshab
ADD CONSTRAINT obshab_idobs_fk
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE obs.obshab
ADD CONSTRAINT obshab_cdhab_fk
FOREIGN KEY (cdhab) REFERENCES referentiel.eunis (cdhab);

ALTER TABLE obs.obsmort
ADD CONSTRAINT obsmort_idobs_fk
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE obs.obsmort
ADD CONSTRAINT obsmort_mort_fk
FOREIGN KEY (mort) REFERENCES referentiel.occmort (idmort);

ALTER TABLE obs.obsmort
ADD CONSTRAINT obsmort_stade_fk
FOREIGN KEY (stade) REFERENCES referentiel.stade (idstade);

ALTER TABLE obs.plusobser
ADD CONSTRAINT plusobser_idfiche_fk
FOREIGN KEY (idfiche) REFERENCES obs.fiche (idfiche);

ALTER TABLE obs.plusobser
ADD CONSTRAINT plusobser_idobser_fk
FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser);

ALTER TABLE obs.site
ADD CONSTRAINT site_idcoord_fk
FOREIGN KEY (idcoord) REFERENCES obs.coordonnee (idcoord);;

ALTER TABLE obs.obscoll
ADD CONSTRAINT obscoll_idobs_fk
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE obs.obsplte
ADD CONSTRAINT obsplte_idobs_fk
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE site.photo
ADD CONSTRAINT photo_idobser_fkey
FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser);

ALTER TABLE site.photo
ADD CONSTRAINT photo_codecom_fkey
FOREIGN KEY (codecom) REFERENCES referentiel.commune (codecom);

ALTER TABLE site.photo
ADD CONSTRAINT photo_idobs_fkey
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE site.photodet
ADD CONSTRAINT photodet_codecom_fkey
FOREIGN KEY (codecom) REFERENCES referentiel.commune (codecom);

ALTER TABLE site.photodet
ADD CONSTRAINT photodet_idobs_fkey FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE site.comobs
ADD CONSTRAINT comobs_idobs_fkey
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE site.comobs
ADD CONSTRAINT comobs_idm_fkey
FOREIGN KEY (idm) REFERENCES site.membre (idmembre);

ALTER TABLE site.liencom
ADD CONSTRAINT liencom_idobs_fkey
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);

ALTER TABLE site.son 
ADD CONSTRAINT son_idobser_fkey
FOREIGN KEY (idobser) REFERENCES referentiel.observateur (idobser);

ALTER TABLE site.son 
ADD CONSTRAINT photo_idobs_fkey
FOREIGN KEY (idobs) REFERENCES obs.obs (idobs);