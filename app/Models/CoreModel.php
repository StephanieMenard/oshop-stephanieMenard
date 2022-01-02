<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
//! NOUVELLE NOTION : Classe abstraite 
// CoreModel est désormais une classe abstraite
// Ça veut dire qu'il est interdit par PHP d'instancier CoreModel : new CoreModel() est impossible
// Ça veut aussi dire qu'on peut ajouter des méthodes, sans les coder,
// pour imposer à toutes les clases qui étendent CoreModel d'avoir des méthodes portant ces noms
abstract class CoreModel {
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;

    // On crée des fonctions abstraites pour s'assurer qu'elles existeront dans les classes filles
    // On ne déclare pas le fonctionnement de ces méthodes : il n'y a pas d'accolades
    // On précise cependant que find() s'utilise avec un paramètre
    // PHP impose donc que la méthode find existe, 
    // qu'elle soit public, static et qu'elle aie un paramètre et un seul
    // Chaque classe de Model (Brand, Category, Product et Type) 
    // doivent donc toutes avoir une méthode find qui respectent ces conditions
    abstract public function insert();
    abstract public static function find($id);
    abstract public static function findAll();
    abstract public function update();
    abstract public function delete();

    /**
     * Sauvegarer le modèle courant (soit en le mettant à jour s'il existe, en créant un enregistrement en base sinon)
     * 
     * @return void
     */
    public function save()
    {
        // si le modèle courant a un id supérieur à 0, c'est qu'il a déjà été enregistré en base
        // donc on veut faire un update
        if ($this->getId() > 0) {
            return $this->update();
        }
        // sinon, c'est que le modèle n'a jamais été enregistré, donc on veut le créer en base
        else {
            return $this->insert();
        }
    }

    /**
     * Get the value of id
     *
     * @return  int|null
     */ 
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */ 
    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */ 
    public function getUpdatedAt() : string
    {
        return $this->updated_at;
    }
}
