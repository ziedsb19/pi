<?php

namespace FabBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Logiciel
 *
 * @ORM\Table(name="logiciel")
 * @ORM\Entity(repositoryClass="FabBundle\Repository\LogicielRepository")
 */
class Logiciel
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
     * *@ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")

     */

    private $urlphoto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesortie", type="date", nullable=true)
     */
    private $datesortie;


    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrlicence", type="integer", nullable=true)
     */
    private $nbrlicence;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;


    /**
     * @var int
     *
     * @ORM\ManyToOne (targetEntity="TypeLog")
     */
    private $typeLog;

    /**
     * @var int
     *
     * @ORM\ManyToOne (targetEntity="FournisseurLog")
     */
    private $fournisseurLog;

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
     * @return Logiciel
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
     * @return mixed
     */
    public function getUrlphoto()
    {
        return $this->urlphoto;
    }

    /**
     * @param mixed $urlphoto
     */
    public function setUrlphoto($urlphoto)
    {
        $this->urlphoto = $urlphoto;
    }

    /**
     * @return \DateTime
     */
    public function getDatesortie()
    {
        return $this->datesortie;
    }

    /**
     * @param \DateTime $datesortie
     */
    public function setDatesortie($datesortie)
    {
        $this->datesortie = $datesortie;
    }






    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Logiciel
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
     * Set nbrlicence
     *
     * @param integer $nbrlicence
     *
     * @return Logiciel
     */
    public function setNbrlicence($nbrlicence)
    {
        $this->nbrlicence = $nbrlicence;

        return $this;
    }

    /**
     * Get nbrlicence
     *
     * @return int
     */
    public function getNbrlicence()
    {
        return $this->nbrlicence;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Logiciel
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
     * @return int
     */
    public function getTypeLog()
    {
        return $this->typeLog;
    }

    /**
     * @param int $typeLog
     */
    public function setTypeLog($typeLog)
    {
        $this->typeLog = $typeLog;
    }

    /**
     * @return int
     */
    public function getFournisseurLog()
    {
        return $this->fournisseurLog;
    }

    /**
     * @param int $fournisseurLog
     */
    public function setFournisseurLog($fournisseurLog)
    {
        $this->fournisseurLog = $fournisseurLog;
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

