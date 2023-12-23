# syntax=docker/dockerfile:1
FROM postgres

RUN apt-get update && apt-get  install -y postgresql-16-postgis-3 

EXPOSE 5432