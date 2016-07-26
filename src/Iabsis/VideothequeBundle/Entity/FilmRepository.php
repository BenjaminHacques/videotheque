<?php

namespace Iabsis\VideothequeBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FilmRepository extends EntityRepository
{
    /**
     * Retourne la liste des films correspondant au genre passé en paramètre (id)
     * Si aucun genre n'est spécifié, la liste de tous les films est renvoyée.
     * @param int $idGenre Id du genre à rechercher
     * @return Iabsis\Bundle\VideothequeBundle\Entity\Film[] Liste des films du genre demandé
     */
    public function findByGenre($idGenre = 0)
    {
        /* Création de la requète avec le query builder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select("f, g")
                     ->from("IabsisVideothequeBundle:Film", "f")
                     ->leftJoin("f.listeDesGenres", "g");
       
        /* Si on reçoit un id de genre valide alors on recherche les Films de ce genre là uniquement */
        if ((int)$idGenre > 0) {
            $queryBuilder->where("g.id=:idGenre")->setParameter("idGenre", (int)$idGenre);
        }
        /* Puis on retourne la liste des films du genre demandé */
        return $queryBuilder->getQuery()->getResult();
    }
    
    /**
     * Retourne le nombre de film au total
     * @return int Renvoie le nombre de films total
     */
    public function countAll()
    {
        /*
         * On aurait tendance à vouloir faire un count() d'un fetchAll() sur le FilmRepository()
         * Cette solution foncitonne mais demande à doctrine de charger toutes les données en mémoire.
         * Si on a des milliers de films, doctrine va alors créer des milliers d'objets Film dans la ram
         * ce qui n'est pas du tout optimisé.
         * 
         * En faisant un count comme ceci, aucune données n'est chargée en mémoire, une simple requête sql est 
         * lancée et le nombre est alors renvoyé.
         */
        
        /* Création de la requète count avec le query builder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select("count(f) as total")
                     ->from("IabsisVideothequeBundle:Film", "f");
       
        /**
        * Comme le seul résultat qui nous intéresse est le count et pas du tout l'entité,
        * On utilise getSingleScalarResult() afin de renvoyer une valeur scalaire unique
        */
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
