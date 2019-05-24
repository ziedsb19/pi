<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\EventListener\UploadImageListener;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Offres
 *
 * @ORM\Table(name="offres", indexes={@ORM\Index(name="id_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\OffresRepository")
 * @Vich\Uploadable
 */
class Offres
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
     * @ORM\Column(name="description_offre", type="text", length=65535, nullable=false)
     */
    private $descriptionOffre;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_sponsors", type="string", length=255, nullable=false)
     */
    private $nomSponsors;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modif", type="datetime", nullable=false)
     */
    private $dateModif = 'CURRENT_TIMESTAMP';

    /**
     * @ORM\Column(name="url_image", type="string")
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
        $this->dateModif =(new \DateTime("now"));
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
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="e_mail", type="string", length=255, nullable=false)
     */
    private $eMail;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User")
     * @ORM\JoinColumn(name="id_utilisateur",referencedColumnName="id")
     */
    private $idUtilisateur;

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
     * Set descriptionOffre
     *
     * @param string $descriptionOffre
     *
     * @return Offres
     */
    public function setDescriptionOffre($descriptionOffre)
    {
        $this->descriptionOffre = $descriptionOffre;

        return $this;
    }

    /**
     * Get descriptionOffre
     *
     * @return string
     */
    public function getDescriptionOffre()
    {
        return $this->descriptionOffre;
    }

    /**
     * Set nomSponsors
     *
     * @param string $nomSponsors
     *
     * @return Offres
     */
    public function setNomSponsors($nomSponsors)
    {
        $this->nomSponsors = $nomSponsors;

        return $this;
    }

    /**
     * Get nomSponsors
     *
     * @return string
     */
    public function getNomSponsors()
    {
        return $this->nomSponsors;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return Offres
     * @throws \Exception
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = new \DateTime("now");

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Offres
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
     * Set eMail
     *
     * @param string $eMail
     *
     * @return Offres
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
     * Set idUtilisateur
     *
     * @param \techEventsBundle\Entity\User $idUtilisateur
     *
     * @return Offres
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

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @return Offres
     * @throws \Exception
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;
        if ($imageFile)
            $this->dateModif = new \DateTimeImmutable();

        return $this;
    }

}
