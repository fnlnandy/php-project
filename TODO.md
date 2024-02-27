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
    - Notifier par mail qu'un Employé va être affecté..[X]
    - Liste des employés pas encore affectés...........[X]
* Affectations
    - Listage.......[X]
    - Création......[X]
    - Modification..[X]
    - Suppression...[X]
    - Génerer un PDF d'un arrêté....................[X]
    - Listage d'affect effectuées entre deux dates..[X]
    - Modifier les affectations affecte aussi les employés concernés...[X]

## Misc
* Ajouter des checks pour les overflow de nombre de caractères...[X]
* Changer la fonction de modification pour qu'elle ne depende pas de x.length...[X] (What did I mean by this ???)
* Prendre en charge les nombres qui commencent par '0'.......[ ] (Will I ever do this lol ?)
* Prendre en charge la reorganization des ids d'affectation..[ ] (Uuuhh too lazy)
* Ajuster le formulaire et la table d'Affectation
    - Remplacer IDs par texte dans le formulaire..[X]
    - Remplacer IDs par texte dans la table.......[X]
* Faire en sorte que les formulaires soient considérés comme envoyés après AJAX...[ ]
* Mettre les boutons d'actions en haut à droite pour ne pas avoir à les retrouver à chaque fois...[ ]
* ^ Même chose pour la barre de navigation...[ ]
* Mettre à jour le lieu d'un Employé quand il est affecté...[ ]
* Verifier que l'ancien lieu n'est pas identique au nouveau lieu pendant une affectation...[ ]

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