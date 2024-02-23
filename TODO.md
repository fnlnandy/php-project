## Core
* Lieus
    - Listage.......[X]
    - Création......[X]
    - Modification..[X]
    - Suppression...[X]
* Employés
    - Listage.......[X]
    - Création......[X]
    - Modification..[X]
    - Suppression...[X]
    - Recherche par nom/prénom.........................[X]
    - Historique des affectations......................[X]
    - Notifier par mail qu'un Employé va être affecté..[ ]
    - Liste des employés pas encore affectés...........[X]
* Affectations
    - Listage.......[X]
    - Création......[X]
    - Modification..[X]
    - Suppression...[X]
    - Génerer un PDF d'un arrêté....................[ ]
    - Listage d'affect effectuées entre deux dates..[X]

## Misc
* Ajouter des checks pour les overflow de nombre de caractères
* Changer la fonction de modification pour qu'elle ne depende pas de x.length
* Prendre en charge les nombres qui commencent par '0'.......[ ]
* Prendre en charge la reorganization des ids d'affectation..[ ]
* Ajuster le formulaire et la table d'Affectation
    - Remplacer IDs par texte dans le formulaire..[ ]
    - Remplacer IDs par texte dans la table.......[ ]

## Improvements
* Ajouter des namespaces pour queries.php and init.php..[X]
    - Fusionner les fichiers ?..........................[X]
* Fusionner les fichiers répétés vers des 'headers'.....[X]
* Créer des namespaces pour les fichiers handler.js....[ ]
* Contrôler l'entrée des dates pour les affectations...[ ]
* Après avoir utilisé la barre de recherche, les valeurs cherchées doivent être préservées...[X]
* ^ Même chose pour les dates d'affectation...[ ]
* Utiliser UpdateDataTracker partout........[X]

## Last-To-Do
* Code Review
    * Variables...[ ]
        * Nommées correctement...[ ]
        * Nommées significativement...[ ]
    * Fonctions...[ ]
        * Dans des conteneurs appropriés...[]
        * Fusionnées si redondantes...[ ]
        * Nommées correctement...[ ]
        * Nommées significativement...[ ]
    * Conteneurs...[ ]
        * Fusionnés si redondants...[ ]
        * Nommés correctement...[ ]
        * Nommés significativement...[ ]
    * Commentairess [ ]
        * *Toutes* les fonctions sont commentées avec `/**/`...[ ]
        * Les pseudo-processus dans les fonctions sont documentés...[ ]
        * Les conteneurs sont documentés...[ ]
        * Les pages HTML sont documentées...[ ]
        * Les feuilles de style CSS sont documentées...[ ]