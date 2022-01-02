# Atelier Révision

## Sujets à réviser

- static methods
- Abstract methods & class
- PDO::prepare()

### Méthodes & classes statiques

- Syntaxe méthode statique ?

```php
public static function find($id){}
```

- Comment l'utiliser ?

```php
AppUser::find($id)
```

- Pourquoi l'utiliser ?

Car il est plus logique d'utiliser certaines méthodes sans avoir à créer un nouvel objet.  
Le fait de devoir créer un objet utilisateur pour récupérer un certain utilisateur en DB me semble quelque peu illogique.  
Et accessoirement, le fait de pouvoir exectuer une méthode en une seule ligne au lieu de deux, c'est cool ;)

- Comment savoir si on doit déclarer une méthode statique ou non ?

Une méthode statique, c'est une méthode liée à la classe qui l'implémente. Elle n'est pas liée à l'objet qui l'utilise.  
C'est à dire, qu'il est impossible d'utiliser le mot clée ```$this``` dans une méthode statique.  En effet ```$this``` fait référence à l'objet courant.  

On choisi donc de créer des méthodes statiques lorsqu'elles n'ont pas besoin de faire références à des propriétées / méthodes de l'objet.  
Un exemple typique de méthodes statiques sont les méthodes finder (find, findAll, findByEmail etc etc)

### Classes & méthodes abtraites

- Qu'est ce que c'est ?
  - Classe abstraite ?
    => Une classe qui ne peut pas être instanciée. Une classe qui aura pour but d'être étendue (ex : CoreModel)
  - Méthode abstraite ?
    => Méthode déclarée dans une classe abtraite et qui va être obligatoirement déclarée dans les classes filles (en respectant la visibilité, le statisme, le mot-clé de déclaration de fonction, lenom de la fonction & les paramètres)

- Syntaxe

```php
abstract class CoreModel
{
    // [...]
    abstract public static function find($id);
    // [...]
}
```

- Pourquoi et quand l'utiliser ?

Cela nous permet de nous assurer que certains éléments figurent bien dans les classes étendues et permet d’éviter certains problèmes de compatibilité en nous assurant que les classes étendues possèdent une structure de base commune. On vient donc définir un cadre de developpement stricte.  
Super important lorsqu'on développe à plusieurs sur un même projet (true story).

### Requêtes préparées

- Syntaxe

```php
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

```

- Pourquoi et quand les utiliser ?

Lorsqu'on travail avec des DB, il est important de penser à la sécurités des données.  
Il ne faut pas que quelq'uun de l'exterieur puisse y accèder ou même les manipuler.  
On parle ici d'injection SQL => injection de données dans une chaîne de caractère.  
Pour sécuriser nos requête SQL, on vient utiliser

```php
PDO::prepare() + PDOStatement::execute()
```

Logiquement, on utilise les requêtes préparée seulement lorsqu'il faut utiliser des valeurs contenues dans des variables. Car nos méthodes prepare() & execute() viennent sécuriser le contenue des variables.  
Cependant, il est intelligent d'utiliser ces méthodes dans toutes nos requêtes pour des soucis d'évolutivités.
