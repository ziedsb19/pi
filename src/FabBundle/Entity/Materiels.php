<?php

namespace FabBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Materiels
 *
 * @ORM\Table(name="materiels")
 * @ORM\Entity(repositoryClass="FabBundle\Repository\MaterielsRepository")
 */
class Materiels
{
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="urlphoto", type="string", length=255, nullable=true)
     */
    private $urlphoto;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")
     *
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

    /**
     * @var string
     *
     * @ORM\Column(name="propriete", type="string", length=255, nullable=true)
     */
    private $propriete;


    /**
     * @var int
     *
     * @ORM\ManyToOne (targetEntity="CatgMateriel")
     */
    private $catgMateriel;

    /**
     * @var int
     *
     * @ORM\ManyToOne (targetEntity="FabLab")
     */
    private $fabLab;



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
     * Set nom
     *
     * @param string $nom
     *
     * @return Materiels
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Materiels
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
     * Set urlphoto
     *
     * @param string $urlphoto
     *
     * @return Materiels
     */
    public function setUrlphoto($urlphoto)
    {
        $this->urlphoto = $urlphoto;

        return $this;
    }

    /**
     * Get urlphoto
     *
     * @return string
     */
    public function getUrlphoto()
    {
        return $this->urlphoto;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }



    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Materiels
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Materiels
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set propriete
     *
     * @param string $propriete
     *
     * @return Materiels
     */
    public function setPropriete($propriete)
    {
        $this->propriete = $propriete;

        return $this;
    }

    /**
     * Get propriete
     *
     * @return string
     */
    public function getPropriete()
    {
        return $this->propriete;
    }

    /**
     * @return int
     */
    public function getCatgMateriel()
    {
        return $this->catgMateriel;
    }

    /**
     * @param int $catgMateriel
     */
    public function setCatgMateriel($catgMateriel)
    {
        $this->catgMateriel = $catgMateriel;
    }

    /**
     * @return int
     */
    public function getFabLab()
    {
        return $this->fabLab;
    }

    /**
     * @param int $fabLab
     */
    public function setFabLab($fabLab)
    {
        $this->fabLab = $fabLab;
    }




}

