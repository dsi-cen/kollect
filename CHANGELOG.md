# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added
- Ajout de la couche IR de IGN
- Le rang taxonomique apparait lors de d'une saisie d'observation
- Possibilité de rechercher, lors d'une saisie, d'une combinaison de mots clés 'Genre espèce'
- Sécurisation de l'architecture de la base de données avec des clés étrangères
- Ajout de compteurs sur le dashboard (biblio, données publiques/privées, études)

### Changed
- Refonte du layout du dashboard d'accueil
- Filtre des espèces du module Cartographie insensible à la casse sur la première lettre du genre

### Removed

### Fixed
- Bug sur les nom de communes trop long -> referentiel.commune de VARYING(45) à VARYING(145)

## [1.0.0] - 2018-XX-XX