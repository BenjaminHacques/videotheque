<?php
# Fichier Genre.php

namespace Iabsis\VideothequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Iabsis\VideothequeBundle\Entity\GenreRepository")
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Bidirectional 
     *
     * @ORM\ManyToMany(targetEntity="Film", mappedBy="listeDesGenres")
     */
    protected $listeDesFilms;
    
    /**
     * @ORM\Column(type="string", length=90)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Le genre doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le genre ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    protected $label;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeDesFilms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Genre
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add listeDesFilms
     *
     * @param \Iabsis\VideothequeBundle\Entity\Film $listeDesFilms
     * @return Genre
     */
    public function addListeDesFilm(\Iabsis\VideothequeBundle\Entity\Film $listeDesFilms)
    {
        $this->listeDesFilms[] = $listeDesFilms;

        return $this;
    }

    /**
     * Remove listeDesFilms
     *
     * @param \Iabsis\VideothequeBundle\Entity\Film $listeDesFilms
     */
    public function removeListeDesFilm(\Iabsis\VideothequeBundle\Entity\Film $listeDesFilms)
    {
        $this->listeDesFilms->removeElement($listeDesFilms);
    }

    /**
     * Get listeDesFilms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListeDesFilms()
    {
        return $this->listeDesFilms;
    }

    /**
     * Affichage d'une entité Genre avec echo
     * @return string Représentation du genre
     */
    public function __toString()
    {
        return $this->label;
    }
}
