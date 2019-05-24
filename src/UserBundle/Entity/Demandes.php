<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\EventListener\UploadImageListener;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Demandes
 *
 * @ORM\Table(name="demandes", indexes={@ORM\Index(name="id_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\DemandesRepository")
 * @Vich\Uploadable
 */
class Demandes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description_evenement", type="text", length=65535, nullable=false)
     */
    private $descriptionEvenement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\NotBlank(message="Vous devez entrer une date ")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=false)
     */
    private $dateModification = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=false)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="e_mail", type="string", length=255, nullable=false)
     */
    private $eMail;

    /**
     * @var string
     *
     * @ORM\Column(name="description_organisateur", type="text", length=65535, nullable=false)
     */
    private $descriptionOrganisateur;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User")
     * @ORM\JoinColumn(name="id_utilisateur",referencedColumnName="id")
     */
    private $idUtilisateur;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="offre_image", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;


    public function __construct()
    {
        $this->dateModification = (new \DateTime("now"));
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
     *
     * @return Demandes
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
     * Set descriptionEvenement
     *
     * @param string $descriptionEvenement
     *
     * @return Demandes
     */
    public function setDescriptionEvenement($descriptionEvenement)
    {
        $this->descriptionEvenement = $descriptionEvenement;

        return $this;
    }

    /**
     * Get descriptionEvenement
     *
     * @return string
     */
    public function getDescriptionEvenement()
    {
        return $this->descriptionEvenement;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Demandes
     */
    public function setDate($date)
    {
        $this->date =$date;
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
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Demandes
     * @throws \Exception
     */
    public function setDateModification($dateModification)
    {

        $this->dateModification = new \DateTime("now");

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
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Demandes
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set eMail
     *
     * @param string $eMail
     *
     * @return Demandes
     */
    public function setEMail($eMail)
    {
        $this->eMail = $eMail;

        return $this;
    }

    /**
     * Get eMail
     *
     * @return string
     */
    public function getEMail()
    {
        return $this->eMail;
    }

    /**
     * Set descriptionOrganisateur
     *
     * @param string $descriptionOrganisateur
     *
     * @return Demandes
     */
    public function setDescriptionOrganisateur($descriptionOrganisateur)
    {
        $this->descriptionOrganisateur = $descriptionOrganisateur;

        return $this;
    }

    /**
     * Get descriptionOrganisateur
     *
     * @return string
     */
    public function getDescriptionOrganisateur()
    {
        return $this->descriptionOrganisateur;
    }

    /**
     * Set idUtilisateur
     *
     * @param \techEventsBundle\Entity\User $idUtilisateur
     *
     * @return Demandes
     */
    public function setIdUtilisateur(\techEventsBundle\Entity\User $idUtilisateur = null)
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    /**
     * Get idUtilisateur
     *
     * @return \techEventsBundle\Entity\User
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @return Demandes
     * @throws \Exception
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;
        if ($imageFile)
            $this->dateModification = new \DateTimeImmutable();

        return $this;
    }



}
