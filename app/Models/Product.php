<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel 
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var float
     */
    private $price;
    /**
     * @var int
     */
    private $rate;
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $brand_id;
    /**
     * @var int
     */
    private $category_id;
    /**
     * @var int
     */
    private $type_id;
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table Product en fonction d'un id donné
     * 
     * @param int $productId ID du produit
     * @return Product
     */
    public static function find($productId)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM product
            WHERE id = ' . $productId;

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $pdoStatement->fetchObject('App\Models\Product');
        
        return $result;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table product
     * 
     * @return Product[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `product`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');
        
        return $results;
    }

    /**
     * Récupérer les 5 derniers produits
     * 
     * @return Product[]
     */
    public static function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT * FROM `product`
            ORDER BY `id` DESC
            LIMIT 5
        ';
        $pdoStatement = $pdo->query($sql);
        $products = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');
        
        return $products;
    }

    /**
     * Méthode permettant d'ajouter un enregistrement dans la table product
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
            INSERT INTO `product` (
                name, 
                description, 
                picture,
                price,
                rate,
                status,
                brand_id,
                category_id,
                type_id
            )
            VALUES (
                :name, 
                :description, 
                :picture,
                :price,
                :rate,
                :status,
                :brand_id,
                :category_id,
                :type_id
            )
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
                'description' => $this->description, 
                'picture' => $this->picture,
                'price' => $this->price,
                'rate' => $this->rate,
                'status' => $this->status,
                'brand_id' => $this->brand_id,
                'category_id' => $this->category_id,
                'type_id' => $this->type_id
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
     * Méthode permettant de mettre à jour un enregistrement dans la table product
     * L'objet courant doit contenir l'id, et toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     * 
     * @return bool
     */
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête préparée UPDATE
        $sql = '
            UPDATE `product`
            SET
                `name` = :name,
                `description` = :description,
                `picture` = :picture,
                `price` = :price,
                `rate` = :rate,
                `status` = :status,
                `brand_id` = :brand_id,
                `category_id` = :category_id,
                `type_id` = :type_id,
                `updated_at` = NOW()
            WHERE id = :id
        ';

        // On met notre requete en état de préparation
        $pdoStatement = $pdo->prepare($sql);

        // On execute la requête en bindant les paramètres avec leur valeur
        // La méthode execute(), nous renvoie true ou false selon si la requete passe ou non
        $updated = $pdoStatement->execute(
            [
                // On vient 'binder' / lier des valeurs à nos paramètres
                'name' => $this->name,
                'description' => $this->description,
                'picture' => $this->picture,
                'price' => $this->price,
                'rate' => $this->rate,
                'status' => $this->status,
                'brand_id' => $this->brand_id,
                'category_id' => $this->category_id,
                'type_id' => $this->type_id,
                'id' => $this->id,
            ]
        );

        // On retourne la réponse de la requete (true ou false)
        return $updated;
    }

    public function delete()
    {
        // TODO
    }

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
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get the value of picture
     *
     * @return  string
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @param  string  $picture
     */ 
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of price
     *
     * @return  float
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  float  $price
     */ 
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Get the value of rate
     *
     * @return  int
     */ 
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set the value of rate
     *
     * @param  int  $rate
     */ 
    public function setRate(int $rate)
    {
        $this->rate = $rate;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */ 
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * Get the value of brand_id
     *
     * @return  int
     */ 
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * Set the value of brand_id
     *
     * @param  int  $brand_id
     */ 
    public function setBrandId(int $brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /**
     * Get the value of category_id
     *
     * @return  int
     */ 
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @param  int  $category_id
     */ 
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get the value of type_id
     *
     * @return  int
     */ 
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set the value of type_id
     *
     * @param  int  $type_id
     */ 
    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }
}
