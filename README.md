Projet symfony : Wanted
=======================
* Nom du projet : Wanted 
* Groupe : Loïc GRECO, Yohan QUINQUIS, Romain LENTZ
* Technologies utilisées : PHP 8.2.2, Symfony, SQLITE, CSS, TWIG, GitHub
* Lien du git : https://github.com/RomainLENTZ/B3-Wanted.git

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
>   * waller:Wallet (OneToOne) : portefeuille de l'utilisateur dans lequel il recoit ses primes
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




