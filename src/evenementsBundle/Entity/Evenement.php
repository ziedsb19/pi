<?php

namespace evenementsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Evenement
 *
 * @ORM\Table(name="evenements")
 * @ORM\Entity(repositoryClass="evenementsBundle\Repository\EvenementRepository")
 * @Vich\Uploadable
 */
class Evenement
{
    /**
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User")
     * ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="techEventsBundle\Entity\User")
     * ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id")
     */
    private $evenementSauvegardes;

    /**
     * @ORM\ManyToMany(targetEntity="evenementsBundle\Entity\Categorie" )
     */
    private $categories;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @Vich\UploadableField(mapping="evenement_image", fileNameProperty="urlImage")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="url_image", type="string", length=255, nullable=true)
     */
    private $urlImage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="billets_restants", type="integer", nullable=true)
     */
    private $billetsRestants;

    /**
     * @var int
     *
     * @ORM\Column(name="disponibilite", type="smallint")
     */
    private $disponibilite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=false)
     */
    private $dateModification;


    public function __construct()
    {
        $this->dateModification= new \DateTime();
        $this->categories= new ArrayCollection();
        $this->evenementSauvegarde = new ArrayCollection();
        $this->disponibilite=1;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile( $imageFile = null)
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(){
        return $this->imageFile;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Evenement
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Evenement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set urlImage
     *
     * @param string $urlImage
     *
     * @return Evenement
     */
    public function setUrlImage($urlImage)
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    /**
     * Get urlImage
     *
     * @return string
     */
    public function getUrlImage()
    {
        return $this->urlImage;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
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
     * Set prix
     *
     * @param float $prix
     *
     * @return Evenement
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Evenement
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set billetsRestants
     *
     * @param integer $billetsRestants
     *
     * @return Evenement
     */
    public function setBilletsRestants($billetsRestants)
    {
        $this->billetsRestants = $billetsRestants;

        return $this;
    }

    /**
     * Get billetsRestants
     *
     * @return int
     */
    public function getBilletsRestants()
    {
        return $this->billetsRestants;
    }

    /**
     * Set disponibilite
     *
     * @param integer $disponibilite
     *
     * @return Evenement
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return int
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Evenement
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set user
     *
     * @param \techEventsBundle\Entity\User $user
     *
     * @return Evenement
     */
    public function setUser(\techEventsBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \techEventsBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add category
     *
     * @param \evenementsBundle\Entity\Categorie $category
     *
     * @return Evenement
     */
    public function addCategory(\evenementsBundle\Entity\Categorie $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \evenementsBundle\Entity\Categorie $category
     */
    public function removeCategory(\evenementsBundle\Entity\Categorie $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add evenementSauvegarde
     *
     * @param \techEventsBundle\Entity\User $evenementSauvegarde
     *
     * @return Evenement
     */
    public function addEvenementSauvegarde(\techEventsBundle\Entity\User $evenementSauvegarde)
    {
        $this->evenementSauvegardes[] = $evenementSauvegarde;

        return $this;
    }

    /**
     * Remove evenementSauvegarde
     *
     * @param \techEventsBundle\Entity\User $evenementSauvegarde
     */
    public function removeEvenementSauvegarde(\techEventsBundle\Entity\User $evenementSauvegarde)
    {
        $this->evenementSauvegardes->removeElement($evenementSauvegarde);
    }

    /**
     * Get evenementSauvegardes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvenementSauvegardes()
    {
        return $this->evenementSauvegardes;
    }
}
