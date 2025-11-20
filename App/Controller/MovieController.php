<?php

namespace App\Controller;

use App\Model\Movie;
use App\Model\Category;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use App\Utils\Tools;

class MovieController
{
    //Attributs
    private MovieRepository $movieRepository;
    private CategoryRepository $categoryRepository;

    //Constructeur
    public function __construct()
    {
        $this->movieRepository = new MovieRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    //Méthodes
        /**
     * Méthode pour rendre une vue avec un template
     * @param string $template Le nom du template à inclure
     * @param string|null $title Le titre de la page
     * @param array $data Les données à passer au template
     * @return void
     */
    public function render(string $template, ?string $title, array $data = []): void
    {
        include __DIR__ . "/../../template/template_" . $template . ".php";
    }

    //Méthode pour ajouter un film (Movie)
    public function addMovie()
    {
        //Tableau avec les messages pour la vue
        $data = [];
        //Tester si le formulaire est soumis
        if (isset($_POST["submit"])) {
            //Test les champs obligatoires sont renseignés
            if (
                !empty($_POST["title"]) &&
                !empty($_POST["description"]) &&
                !empty($_POST["publish_at"])
                ) {

                //Nettoyer les entrées utilsiateur ($_POST du formulaire)
                $title = Tools::sanitize($_POST["title"]);
                $description = Tools::sanitize($_POST["description"]);
                $publishAt = Tools::sanitize($_POST["publish_at"]);
                //Créer un objet Movie
                $movie = new Movie();
                //Setter les valeurs
                $movie->setTitle($title);
                $movie->setDescription($description);
                $movie->setPublishAt(new \DateTimeImmutable($publishAt));
                //Setter les categories à $movie
                foreach ($_POST["categories"] as $category) {
                    //Créer un objet Category
                    $newCategory = new Category("");
                    //Setter l'ID
                    $newCategory->setId((int) $category);
                    //Ajouter la categorie à la liste des Category de Movie
                    $movie->addCategory($newCategory);
                }
                //Appeler la méthode saveMovie du MovieRepository
                $this->movieRepository->saveMovie($movie);
                $data["valid"] = "Le film : " . $movie->getTitle() . " a été ajouté en BDD";
            }
            //Afficher un message d'erreur
            else {
                $data["error"] = "Veuillez renseigner les champs du formulaire";
            }
        }
        //Récupération des catégories
        $categories = $this->categoryRepository->findAllCategories();
        //Ajout au tableau $data
        $data["categories"] = $categories;
        
        return $this->render("add_movie", "Add Category", $data);
    }
}
