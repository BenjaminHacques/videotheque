<?php
# Fichier Film.php

/*******************************************************************************************
 *  Namespace dans lequel se trouvera notre objet "Film" .
 * Les namespace servent à définir un espace de noms dans lesquels seront stockés notre objet.
 * Ici on dit que notre classe Genre fait partit de l'espace de Nom Entity, 
 * ainsi  Symfony saura qu'il s'agit bien d'une entité.
 * 
 * Dès lors qu'on utilisera l'instruction "use Iabsis\Bundle\VideothequeBundle\Entity\Film" dans un fichier PHP, 
 * on pourra accéder à notre entité sans utiliser à chaque fois une référence complète vers l'objet !
 * 
 * On pourra donc faire new Film() au lieu de new Iabsis\Bundle\VideothequeBundle\Entity\Film();
 ********************************************************************************************/

namespace Iabsis\VideothequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/*******************************************************************************************
 * 
 * Ci-dessous, nous allons décrire notre table, telle qu'elle sera gérée par Doctrine.
 * 
 * Vous allez voir des commentaires faisant apparaître le mot clé @ORM,
 * ces balises sont très importantes, elles permettent principalement de définir de quel
 * type de champ il s'agit. Ainsi Doctrine saura comment créé ce champ dans la base
 * de données de votre choix.
 * 
 *******************************************************************************************/
/**
 * @ORM\Entity(repositoryClass="Iabsis\VideothequeBundle\Entity\FilmRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Film
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
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="listeDesFilms")
     * @ORM\JoinTable(name="_assoc_film_genre",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="film_id", referencedColumnName="id")}
     * )
     */
    protected $listeDesGenres;
    
    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $titre;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $description;


    protected $image;
    private $imageASupprimer;

    /**
     * @var string
     *
     * @ORM\Column(name="cheminfichier", type="string", length=255)
     */
    private $url;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeDesGenres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     * @return Film
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Film
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add listeDesGenres
     *
     * @param \Iabsis\VideothequeBundle\Entity\Genre $listeDesGenres
     * @return Film
     */
    public function addListeDesGenre(\Iabsis\VideothequeBundle\Entity\Genre $listeDesGenres)
    {
        $this->listeDesGenres[] = $listeDesGenres;

        return $this;
    }

    /**
     * Remove listeDesGenres
     *
     * @param \Iabsis\VideothequeBundle\Entity\Genre $listeDesGenres
     */
    public function removeListeDesGenre(\Iabsis\VideothequeBundle\Entity\Genre $listeDesGenres)
    {
        $this->listeDesGenres->removeElement($listeDesGenres);
    }

    /**
     * Get listeDesGenres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListeDesGenres()
    {
        return $this->listeDesGenres;
    }

    /**
     * Affichage d'une entité Genre avec echo
     * @return string Représentation du film
     */
    public function __toString()
    {
        return $this->titre;
    }



    /**
     * Set image
     *
     * @param string $image
     *
     * @return Film
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Film
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }





    protected function getUploadRootDir(){
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir(){
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de
        //problème lorsqu'on affiche le document/image dans la vue.
        return 'uploads/images';
    }

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload(){
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->image) {
            return;
        }
        // Le nom du fichier est son id, on stocke juste son extension
        $this->url= $this->image->guessExtension();
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload(){
        if (null === $this->image){
            return;
        }
        // On déplace le fichier envoyé dans le répertoire d'upload
        $this->image->move($this->getUploadRootDir(), $this->id.'.'.$this->url);
    }

    /**
    * @ORM\PreRemove()
    */
    public function preRemoveUpload(){
        $this->imageASupprimer= $this->getUploadRootDir().'/'.
        $this->id.'.'.$this->url;
    }

    /**
    * @ORM\PostRemove()
    */
    public function removeUpload(){
        // En PostRemove, on n'a pas accès à l'id
        if (file_exists($this->imageASupprimer)) {
            // On supprime le fichier
            unlink($this->imageASupprimer);
        }
    }
}
