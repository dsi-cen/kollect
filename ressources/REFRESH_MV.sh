#!/bin/bash

# Ajouter un cronjob 
# Par exemple : 0 0 * * * /bin/bash /home/folder/REFRESH_MV.sh
echo 'REFRESH MATERIALIZED VIEW obs.synthese_obs_nflou; REFRESH MATERIALIZED VIEW obs.synthese_obs_flou;' | /sbin/runuser postgres -c "psql mydb"
