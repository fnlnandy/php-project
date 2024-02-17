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
* Create namespaces for queries.php and init.php..[X]
    - Maybe fuse the files ?......................[X]
* Fuse repeating files into helpers...............[X]
* Create namespaces for the handler.js file(s)....[ ]
* In the dates for the affectation, try to control input..[ ]
* After using the searchbar, selected values shall be preserved, like searchbar content etc...[X]
* Use UpdateDataTracker when it's possible........[X]

## Last-To-Do
* Strict code review
    * Variables [ ]
        * Named correctly [ ]
        * Named meaningfully [ ]
    * Functions [ ]
        * Are in appropriate containers [ ]
        * Are fused if there is redundancy [ ]
        * Named correctly [ ]
        * Named meaningfully [ ]
    * Containers [ ]
        * Are fused if there is redundancy [ ]
        * Named correctly [ ]
        * Named meaningfully [ ]
    * Comments [ ]
        * Functions are *all* commented with `/**/` [ ]
        * Subprocesses inside functions are commented [ ]
        * Containers are documented [ ]
        * HTML Pages are commented [ ]
        * CSS Stylesheets are commented [ ]