<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Model\Category;

class CategoryRepository
{
    //Attributs
    private \PDO $connect;

    //Constructeur
    public function __construct()
    {
        //Injection de dépendance
        $this->connect = (new Mysql())->connectBDD();
    }

    //Méthodes
    //Ajouter une category
    public function saveCategory(Category $category): void
    {
        try {
            //Requête SQL
            $sql = "INSERT INTO category(`name`) VALUE(?)";
            //péparation
            $req = $this->connect->prepare($sql);
            //Assignation du paramètre
            $req->bindValue(1, $category->getName(), \PDO::PARAM_STR);
            //Exécution de la requête
            $req->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    
    //Afficher une category (par son id)
    public function findAllCategoryById(int $id): array
    {
        return [];
    }
    
    //Afficher toutes les categories
    public function findAllCategories():array
    {
        try {
            //Requête SQL
            $sql = "SELECT c.id, c.name FROM category AS c ORDER BY c.name";
            //péparation
            $req = $this->connect->prepare($sql);
            //Exécution de la requête
            $req->execute();
            //Fetch
            $categories = $req->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        //Retour de la liste des categories
        return $categories;
    }

    //Méthode qui verifie si une categorie existe
    public function isCategoryExistsByName(string $name) :bool
    {
        try {
            //Ecrire la requête
            $sql = "SELECT id FROM category WHERE `name` = ?";
            //Préparer la requête
            $req = $this->connect->prepare($sql);
            //Assigner le paramètre
            $req->bindParam(1, $name, \PDO::PARAM_STR);
            //Exécuter la requête
            $req->execute();
            //Fetch le resultat
            $category = $req->fetch(\PDO::FETCH_ASSOC);
            //Test si la categorie n'existe pas
            if (empty($category)) {
                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    } 
}