<?php

namespace Iabsis\VideothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iabsis\VideothequeBundle\Entity\Genre;
use Iabsis\VideothequeBundle\Entity\Film;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_index", defaults={"label"=""})
     * @Route("/filter-by-genre/{label}", name="_filter_by_genre", defaults={"label"=""})
     * @Template()
     */
    public function indexAction($label)
    {
        /* Tableau qui va stocker toutes les données à remplacer dans le template twig */
        $variables = array();

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $entityManager = $this->getDoctrine()->getManager();

        // On recherche dans la table Genre l'enregistrement qui correspond au label reçut
        // par l'url et on stocke l'objet Genre dans une variable
        $selectedGenre = $entityManager
                            ->getRepository("IabsisVideothequeBundle:Genre")
                            ->findOneBy(array("label" => $label));

        if ($selectedGenre) {
            // Si le genre passé par l'url existe bien, on passe l'id à notre template
            $variables['selected_genre'] = $selectedGenre->getId();
        } else {
            // Sinon on renvoie 0, aucun genre n'a été sélectionné
            $variables['selected_genre'] = null;
        }

        // Récupération de la liste des films grâce à notre méthode findByGenre.
        $listeDeFilms = $entityManager
                            ->getRepository("IabsisVideothequeBundle:Film")
                            ->findByGenre($variables['selected_genre']);

        $variables['films'] = $listeDeFilms;

        // On récupère le nombre total de films en comptant simplement le resultat de la recheche de tous les films
        $variables['total_nb_films'] = $entityManager->getRepository("IabsisVideothequeBundle:Film")->countAll();

        /* On récupère la liste des genres avec le nombre de films associés pour notre sidebar */
        $variables['genres'] = $entityManager
                                    ->getRepository('IabsisVideothequeBundle:Genre')
                                    ->fetchAllWithFilmsCount();

        return $this->render('IabsisVideothequeBundle:Default:videotheque_home.html.twig', $variables);
    }
}
