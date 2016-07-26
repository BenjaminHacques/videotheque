<?php

namespace Iabsis\VideothequeBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GenreRepository extends EntityRepository
{
    public function fetchAllWithFilmsCount()
    {
       /* Création de la requète avec le query builder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select("g as genre, count(f) as nbConcernedFilms")
                     ->from("IabsisVideothequeBundle:Genre", "g")
                     ->leftJoin("g.listeDesFilms", "f")
                     ->groupBy("g");
       /* On retourne les enregistrements, il s'agira d'un table multidimentionnel
        * Le premier paramètre "genre" contiendra notre entité genre
        * Le 2ème paramètre nbConcernedFilms contient le nombre de films du genre
        * On aurait pu tout simplement faire un findAll() en y mettant une jointure et compter
        * simplement le nombre de genre associés, mais cela chargerait les données des genres qui
        * nous sont ici inutiles.
        */
        return $queryBuilder->getQuery()->getResult();
       
    }
}
