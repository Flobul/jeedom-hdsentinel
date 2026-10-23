# Changelog

## Non publié

## Version 1.03.0 (14/10/2026)

- Refonte de la structure du code afin d'améliorer sa lisibilité et sa maintenabilité.
- Ajout de méthodes dans la classe `sshmanager` et amélioration des attributs de données dans le code HTML (`a3f9c4f`).
- Ajout du workflow `CI` orchestrant le front-end, le PHP, Jeedom, la sécurité et les traductions (`1bde97d`).
- Mise à jour du README, de la licence dans `info.json` et ajout de `SECURITY.md` (`dac1cda`).

## Version 1.02.01 (19/07/2026)

- Amélioration de la sécurité et de la gestion des rapports HTML : ajout de la fonction de nettoyage pour supprimer les éléments dangereux et validation de la taille des rapports.
- Amélioration de la gestion des fichiers journaux

## Version 1.01.00 (21/06/2026)

- Amélioration des contrôles de validation pour les réponses d'installation et de récupération des journaux dans hdsentinel.js.
- Correction d'un accès incorrect à la propriété remoteDaemonAuto dans hdsentinel.js.
- Ajout d'un contrôle de valeur nulle pour le gestionnaire SSH dans health.php afin d'éviter d'éventuelles erreurs.
- Modification de page.php pour gérer en toute sécurité la récupération des fichiers pour les rapports HTML de hdsentinel.
- Amélioration de la logique de calcul de l'état de santé dans hdsentinel.php afin d'éviter les divisions par zéro.
- Mise à jour de configuration.php pour améliorer l'interface utilisateur dédiée à la gestion des équipements à distance.
- Affinement de la logique d'installation dans install.php afin de garantir une gestion correcte des configurations Windows et manuelles.
- Amélioration du script hdsentinel_to_jeedom_pub.sh pour une meilleure détection des commandes et une meilleure gestion des erreurs.
- Amélioration du script install_apt.sh pour une meilleure gestion des paquets et un meilleur déroulement de l'installation.

## Version 1.00.00 (02/01/2025)

- Passage de la gestion client sur le plugin SSH Manager. (installé automatiquement si non installé)
- Passage en jsvanilla.
- Corrections mineures.
- Ajout d'un bouton Community.

## Version 0.92 (04/05/2024)

- Passage sur la lib phplibsec3.
- Ajout de possible de connexion par clé SSH.

## Version 0.80 (26/01/2022)

- Correction affichage eqLogic 'NaN' quand aucun disque.
- Page Santé, affichage de la date en français.
- CSS dark mode pour page Html

## Version 0.70 (21/01/2022)

- Ajout de possibilité de choisir l'interval de cron.
- Ajout d'un panel reprenant la page Santé.
- Ajout d'infos dans configuration de l'équipement.
- Conversion des dates, durées en français.

## Version 0.60 (21/01/2022)

- Ajout de l'historique des valeurs dans a page Santé.
- Ajout de la quantité écrite dans la page Santé.
- Ajout de pastille d'info sur l'équipement (santé et nb de disques)

## Version 0.50 (20/01/2022)

- Ajout bouton de gestion de tous les cron depuis configuration plugin.
- Ajout bouton arret et suppression du cron.
- Ajout page Page html pour afficher la page Html générée par HdSentinel.
- Ajout page Santé.

## Version 0.40 (18/01/2022)

- Première version

![](https://flobul-domotique.fr/wp-content/uploads/2022/01/hdsentinel_icon.png)

- [Présentation et documentation du plugin](https://flobul-domotique.fr/presentation-et-documentation-du-plugin-hdsentinel-pour-jeedom/)
- Liste des versions du plugin(changelog)
- [Forum (communauté Jeedom)](https://community.jeedom.com/t/beta-hard-disk-sentinel/76672)
