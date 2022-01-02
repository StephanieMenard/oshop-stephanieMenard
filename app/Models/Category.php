<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    //! NOUVELLE NOTION : méthode statique
    //* On déclare une méthode statique. 
    //* Cette méthode n'est plus liée à l'objet courant, mais à la classe
    //* On pourra donc l'appeler dans notre Controller commme ceci : Category::find($idCategory)
    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     * 
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
        return $category;
    }

    //! NOUVELLE NOTION : méthode statique
    //* On déclare une méthode statique. 
    //* Cette méthode n'est plus liée à l'objet courant, mais à la classe
    //* On pourra donc l'appeler dans notre Controller commme ceci : Category::findAll()
    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     * 
     * @return Category[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     * 
     * @return Category[]
     */
    public static function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $categories;
    }

    /**
     * Méthode permettant d'ajouter un enregistrement dans la table category
     * L'objet courant doit contenir toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     * 
     * @return bool
     */
    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        //! NOUVELLE NOTION : Requête SQL préparée
        // 1 - On écrit notre requête SQL en remplacant les valeurs à insérer par des paramètres identifiés avec (:)
        //* https://www.php.net/manual/fr/pdo.prepare.php
        $sql = "
            INSERT INTO `category` (name, subtitle, picture)
            VALUES (:name, :subtitle, :picture)
        ";

        // 2 - On lance la préparation de notre requête
        // Elle attend qu'on associes des valeurs aux paramètres définit plus haut avec (:)
        $pdoStatement = $pdo->prepare($sql);

        // 3 - On execute la requete en lui passant un tableau avec les valeurs à associer
        // La méthode retourne true en cas de succès ou false si une erreur survient
        $inserted = $pdoStatement->execute(
            [
                // On vient 'binder' / lier des valeurs à nos paramètres
                'name' => $this->name,
                'subtitle' => $this->subtitle,
                'picture' => $this->picture,
            ]
        );

        // 4 - Si l'execution s'est bien passée
        if ($inserted) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

    /**
     * Méthode permettant de mettre à jour un enregistrement dans la table category
     * L'objet courant doit contenir l'id, et toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     * 
     * @return bool
     */
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête préparée UPDATE
        $sql = "
            UPDATE `category`
            SET
                name = :name,
                subtitle = :subtitle,
                picture = :picture,
                updated_at = NOW()
            WHERE id = :id
        ";

        // On met notre requete en état de préparation
        $pdoStatement = $pdo->prepare($sql);

        // On execute la requête en bindant les paramètres avec leur valeur
        // La méthode execute(), nous renvoie true ou false selon si la requete passe ou non
        $updated = $pdoStatement->execute(
            [
                // On vient 'binder' / lier des valeurs à nos paramètres
                'name' => $this->name,
                'subtitle' => $this->subtitle,
                'picture' => $this->picture,
                'id' => $this->id,
            ]
        );

        // On retourne la réponse de la requete (true ou false)
        return $updated;
    }

    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête préparée UPDATE
        $sql = "
            DELETE FROM `category` 
            WHERE id = :id
        ";

        // On met notre requete en état de préparation
        $pdoStatement = $pdo->prepare($sql);

        // On execute la requête en bindant les paramètres avec leur valeur
        // La méthode execute(), nous renvoie true ou false selon si la requete passe ou non
        $deleted = $pdoStatement->execute(
            [
                // On vient 'binder' / lier des valeurs à nos paramètres
                'id' => $this->id,
            ]
        );

        // On retourne la réponse de la requete (true ou false)
        return $deleted;
    }

    public static function updateHomeOrder($ids)
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Notre requête SQL contient 6 UPDATE
        // On peut faire plusieurs requêtes en une seule fois en les séparant par des point-virgules «;»
        // Contrairement à toutes les requêtes qu'on a faites en S06, on n'utilise pas de paramètres nommés
        // mais plutot des paramètres identifiés par un simple point d'interrogation «?»
        $sql = '
            UPDATE `category` SET home_order = 0;
            UPDATE `category` SET home_order = 1 WHERE `id` = ?;
            UPDATE `category` SET home_order = 2 WHERE `id` = ?;
            UPDATE `category` SET home_order = 3 WHERE `id` = ?;
            UPDATE `category` SET home_order = 4 WHERE `id` = ?;
            UPDATE `category` SET home_order = 5 WHERE `id` = ?;
        ';
        // Ces requètes vont mettre tous les home_order à 0
        // pour ensuite bien remettre les 5 numéros d'emplacements aux catégories concernés

        // On met notre requete en état de préparation
        $pdoStatement = $pdo->prepare($sql);

        // Lorsqu'on utilise des «?» dans la requête,
        // il suffit d'envoyer à execute() un tableau indexé avec la liste des valeurs 
        // Ex : [2, 4 ,6 ,5 , 9]
        // $ids est justement un tableau d'id qui viennent du formulaire
        // Il suffit donc de passer $ids à execute()
        $updated = $pdoStatement->execute($ids);

        // On retourne la réponse de la requete (true ou false)
        return $updated;
    }
}
