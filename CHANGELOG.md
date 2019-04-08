# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added
- Ajout de la couche IR de IGN
- Le rang taxonomique apparait lors de d'une saisie d'observation
- Possibilité de rechercher, lors d'une saisie, d'une combinaison de mots clés 'Genre espèce'
- Ajout standard SINP v2.0 avec les champs `comportement` et `nom cité` et mise à jour du référentiel
- Gestion des relations *Observateur -> organisme -> études* avec filtres lors de la saisie
- Sécurisation de l'architecture de la base de données avec des clés étrangères
- Ajout de compteurs sur le dashboard (biblio, données publiques/privées, études)
- Ajout d'un champ `precision` dans la fiche et du référentiel correspondant

### Changed
- Mise à jour vers TAXREF v12 à l'installation
- Refonte du layout du dashboard d'accueil
- Amélioration de l'affichage détaillé des observations
- Amélioration de l'affichage lors de la sasie
- Filtre des espèces du module Cartographie insensible à la casse sur la première lettre du genre
- Passage du champ `etude` de la table observation à la table fiche
- Elargissement du champ `protocole` à `type d'acquisition` et le référentiel correspondant
- Le type de donnée *privé* fait dorénavant référence aux observations d'origine privée (réalisées en temps qu'organisme indépendant)
- Identification sur l'adresse de courriel à la place du prénom
- Type de hash passé de `sha1()` à la fonction `password_hash()` pour une meilleure sécurité, **/!\** nécessité de rehasher les mots de passe après la mise à jour

### Removed
- Suppression du choix de type de donnée dans les préférences utilisateur et à la saisie (car conditionné par le choix de l'organisme et/ou de étude)

### Fixed
- Bug sur les noms de communes trop long -> referentiel.commune de VARYING(45) à VARYING(145)
- Bug lorsqu'aucune clé IGN n'est utilisée (problème avec update de l'altitude)
- Correction de syntaxe dans les encodages des requêtes SQL

## [1.0.0] - 2018-XX-XX
