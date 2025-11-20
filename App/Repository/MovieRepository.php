<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Model\Movie;
use App\Model\Category;

class MovieRepository
{
    //Attributs
    private \PDO $connect;

    public function __construct()
    {
        //Injection de dépendance
        $this->connect = (new Mysql())->connectBDD();
    }

    //Méthodes
    public function saveMovie(Movie $movie): void
    {
        try {
            //Ecrire la requête
            $sql = "INSERT INTO movie(title, `description`, publish_at)VALUE(?,?,?)";
            //Préparer la requête
            $req = $this->connect->prepare($sql);
            //Assigner les paramètres
            $req->bindValue(1, $movie->getTitle(), \PDO::PARAM_STR);
            $req->bindValue(2, $movie->getDescription(), \PDO::PARAM_STR);
            $req->bindValue(3, $movie->getPublishAt()->format("Y-m-d"), \PDO::PARAM_STR);
            //Exécuter la requête
            $req->execute();
            
            //Récupérer l'id du film ajouté
            $id = $this->connect->lastInsertId();
            
            //Boucle pour associer les categories à la table association
            foreach ($movie->getCategories() as $category) {
                //Requête table association movie_category
                $sqlAsso = "INSERT INTO movie_category(id_movie, id_category) VALUE(?,?)";
                //Préparer la requête
                $reqAsso = $this->connect->prepare($sqlAsso);
                //Assigner les paramètres
                $reqAsso->bindParam(1, $id, \PDO::PARAM_INT);
                $reqAsso->bindValue(2, $category->getId(), \PDO::PARAM_INT);
                //Exécuter la requête
                $reqAsso->execute();
            }
            
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}