<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel
{
    // ==========
    // PROPRIETES
    // ==========

    // Propriétés qui correspondent aux champs de la table app_user
    // On ne doit pas déclarer $id, $created_at et $updated_at car déjà dans CoreModel
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $role;
    private $status;

    // ==========
    // METHODES
    // ==========

    public function insert()
    {
        // On recup un objet PDO
        $pdo = Database::getPDO();

        // Préparation de la requête
        $sql = '
            INSERT INTO 
            `app_user` (lastname, firstname, email, password, role, status) 
            VALUES (:lastname, :firstname, :email, :password, :role, :status)
        ';

        // On lance la préparation de notre requête
        $pdoStatement = $pdo->prepare($sql);

        // On execute la requête en lui passant un tableau avec les valeurs à associer
        // La méthode retourne true en cas de succès ou false si une erreur survient 
        $inserted = $pdoStatement->execute(
            [
                // On vient 'binder' / lier nos paramètres avec leurs valeurs
                'lastname' => $this->getLastname(),
                'firstname' => $this->getFirstname(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword(),
                'role' => $this->getRole(),
                'status' => $this->getStatus()
            ]
        );

        // Si on enregistre bien une entrée en table
        if ($inserted) {
            $this->id = $pdo->lastInsertId();
            // On renvoie true car l'enregistrement s'est bien passé
            return true;
        }

        // On renvoie false si on n'affecte aucune ligne
        // => Aucun enregistrement effectué
        return false;
    }

    public static function find($id)
    {
        $pdo = Database::getPDO();

        $sql = 'SELECT * FROM `app_user` WHERE `id` = :id';

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(
            [
                'id' => $id
            ]
        );

        // Le raccourci ::class permet d'obtenir le FQCN d'une classe
        // (fully Qualified Class Name), c'est-à-dire le nom de la classe
        // avec son namespace
        // Ici, AppUser::class == 'App\Models\AppUser'
        // Pour du code propre, plus lisible et plus facile à maintenir,
        // on préférera cette écriture.
        // D'ailleurs, pour aller plus loin, on utilisera régulièrement le mot self
        // pour faire référence à la classe dans laquelle on code.
        // self::class est donc une formule magique pour retrouver le FQCN 
        // de la classe où on vient d'écrire cette formule
        $user = $pdoStatement->fetchObject(self::class);

        return $user;
    }

    /**
     * Méthodes permettant de recup une entrée depuis la tablea app_user
     * grâce à l'email renseigné
     *
     * @param string $email
     * @return AppUser|bool
     */
    public static function findByEmail($email)
    {
        $pdo = Database::getPDO();

        $sql = 'SELECT * FROM `app_user` WHERE `email` = :email';

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(
            [
                'email' => $email
            ]
        );

        return $pdoStatement->fetchObject(self::class);
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->prepare($sql);

        // On utilise désormais prepare+execute partout, notamment pour des raisons de sécurité
        // quand on a au moins un paramètre dans la requête.
        // Cependant ici on n'a aucun paramètre. On utilise tout de même prepare+execute pour avoir du code propre.
        // Si un jour on souhaite ajouter des paramètres, la méthode execute() est déjà appelée,
        // il suffira de modifier son argument.
        $pdoStatement->execute();
        $users = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class); // self::class === "App\Models\AppUser"

        return $users;
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }

    
    // =================
    // GETTERS & SETTERS
    // =================

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}