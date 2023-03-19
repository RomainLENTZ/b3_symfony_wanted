Projet symfony : Wanted
=======================
* Nom du projet : Wanted 
* Groupe : Loïc GRECO, Yohan QUINQUIS, Romain LENTZ
* Technologies utilisées : PHP 8.2.2, Symfony (5.4.21), SQLITE, SCSS, TWIG, GitHub
* Lien du git : https://github.com/RomainLENTZ/B3-Wanted.git
 
__________________________________
Comment contribuer au projet :
--------------------------------
Cloner le git ````git clone https://github.com/RomainLENTZ/B3-Wanted.git````.    
Allez à la racine du projet et lancer la commande : ````symfony serve````

---------------------------------------------

Présentation du site
---------------------
> Wanted est un site web professionnel mettant en relation des policier et des tueurs à gage.
> Un policier peux poster un contrat concernant une victime et un tueur a gage peut alors s'attribuer
> la chasse pour y particieper. Une fois terminée cette dernière peut etre cloturer par le policier
> et ainsi une somme d'argent peut être reversé au tueur à gage ayant completé sa mission

Pourquoi est-ce innovant?
-------------------------
>Avec Wanted, nous souhaitons faciliter le contact entre policier et tueur à gage, ce qui n'a encore
> jamais été fait. Pour résumé, Wanted est un réseau sociale de tueur.

Aspect technique :
------------------
>Le projet est composé de 5 tables et 4 entitées :
> * Hunt (entité représentant les chasses) : elle est composées des propriétés suivantes :
>   * id
>   * name:String : nom de la chasse
>   * target:Target (OneTOne) : cible de la chasse, une chasse ne peut avoir qu'une seule cible et une cible ne peut appartenir qu'a une seule chasse
>   * createdAt:DateTimeImmutable : date de création
>   * author:User (ManyToOne) : créateur de la chasse
>   * hunters:Collection (ManyToMany) : avec user. Représente la liste des participants à une chasse. Plusieurs participants peuvent participer à plusieurs chasses
>   * isOpen:Bool : statut de la chasse
> * User (entité représentant les utilisateurs : policier ou chasseur)
>   * id
>   * username:String
>   * password:String
>   * email:String
>   * description:String
>   * photo:String
>   * hunts:Collection (OneToMany) : liste de chasse dont l'utilisateur est l'auteur
>   * myHunts:Collection (ManyToMany) : liste de chasse auxquelles participe l'utilisateur
>   * waller:Wallet (OneToOne) : portefeuille de l'utilisateur dans lequel il recoit ses primes ( crée automatiquement lors de la création du compte )
> * Target (entité représentant une cible)
>   * id
>   * name:String
>   * description:String
>   * image:String
>   * hunt:Hunt (OneToOne) : chasse à laquelle la cible est affiliée
> * Wallet (entité représentant un portefeuille attribué à un chasseur)
>   * id
>   * owner:User (OnToOne) : Détenteur du portefeuille
>   * amount:Int : montant se trouvant sur le compte

Fonctionnalités :
-----------------
> Le sites comportes 2 roles différents : 
> * Policier: utilisateur pouvant consulter les avis de recherche,
> les ajouter, les cloturer et définir un "gagnant" ce qui déclanchera le versement sur le compte du gagnant de la somme annoncée.
> Ils peuvent aussi en créeant une chasse ajouter une cible.
> * Chasseur: utilisateur pouvant participer à un avis de recherche
>
> Tous les utilisateurs peuvent en plus : s'inscrire, se connecter et modifier leur profile.

Relation de hiérarchie :
------------------------


Ajouter une chasse :
---------------------
> Pour ajouter une chasse il faut avoir un compte crée en temps que policer et être connecté.
> Ensuite cliquer sur "Hunts" dans le header, puis sur "Ajouter une chasse" vous devrez ensuite ajouter une "target" 
> une fois la target renseignée, vous pourrez continuer a créer la chasse. Une fois tous les champs renseigné, vous serez 
> redirigier vers la page des "Hunts" et vous verrez apparaitre votre chasse.
> 
> *Note: vous pouvez retrouver vos chassesa tout moment en cliquant sur "mes chasses" dans le header*

Cloturer une chasse :
---------------------
>Vous pouvez cloturer une chasse soit depuis la page "mes chasses" soit depuis l'onglet "Hunts". Pour ce faire
> cliquer sur le bouton "cloturer" se trouvant a coté du titre. Vous serez redirigé vers un formulaire vous demandant de choisir 
> un utilisateur a qui reverser la sommes associée à la chasse. Une fois fermée, la chasse apparaitra alors sous le titre "chasse terminées".

Ajouter un hunter à ca brigade :
--------------------------------
>Pour ajouter un hunter à sa brigade, cliquer sur "hunters" dans ke header puis sur "ajouter" sur sur le heuter que vous souhaitez accueillir.

Participer à une chasse : 
-------------------------
>Pour participer à une chasse, il faut être connecté en temps que "hunter". Ensuite, cliquer sur "hunts" puis sur participer à coté de la chasse à laquelle vous souhaitez participer

Modifier son profile :
----------------------
> Il est possible de modifier son profile à tout moment en cliquant sur votre pseudo en haut à droite.
