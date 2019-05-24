<?php

namespace techEventsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="techEventsBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prenom", type="string", length=255, nullable=true)
     */
    protected $nomPrenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    protected $dateNaissance;

    /**
     * @var int
     *
     * @ORM\Column(name="sexe", type="smallint", nullable=true)
     */
    protected $sexe;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_tel", type="bigint", nullable=true)
     */
    protected $numeroTel;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    protected $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url_photo_profil", type="string", length=255, nullable=true)
     */
    protected $urlPhotoProfil;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime")
     */
    protected $dateInscription;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=11, nullable=true)
     */
    protected $status;

    /**
     * @var int
     *
     * @ORM\Column(name="admin", type="smallint", nullable=true)
     */
    protected $admin;

    /**
     * @var string
     *
     * @ORM\Column(name="connecte", type="string", length=255, nullable=true)
     */
    protected $connecte;

   /**
     * @var string
     *
     * @ORM\Column(name="charge_id", type="string", length=255, nullable=true)
     */
    private $chargeId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="premium", type="boolean", nullable=false)
     */
    private $premium;


    public function __construct()
    {
        parent::__construct();
        $this->dateInscription = new \DateTime();
        $this->eventsSaved = new ArrayCollection();
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
     * Set nomPrenom
     *
     * @param string $nomPrenom
     *
     * @return User
     */
    public function setNomPrenom($nomPrenom)
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    /**
     * Get nomPrenom
     *
     * @return string
     */
    public function getNomPrenom()
    {
        return $this->nomPrenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set sexe
     *
     * @param integer $sexe
     *
     * @return User
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return int
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set numeroTel
     *
     * @param integer $numeroTel
     *
     * @return User
     */
    public function setNumeroTel($numeroTel)
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    /**
     * Get numeroTel
     *
     * @return int
     */
    public function getNumeroTel()
    {
        return $this->numeroTel;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
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
     * Set description
     *
     * @param string $description
     *
     * @return User
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
     * Set urlPhotoProfil
     *
     * @param string $urlPhotoProfil
     *
     * @return User
     */
    public function setUrlPhotoProfil($urlPhotoProfil)
    {
        $this->urlPhotoProfil = $urlPhotoProfil;

        return $this;
    }

    /**
     * Get urlPhotoProfil
     *
     * @return string
     */
    public function getUrlPhotoProfil()
    {
        return $this->urlPhotoProfil;
    }

    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     *
     * @return User
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set admin
     *
     * @param integer $admin
     *
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return integer
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set connecte
     *
     * @param string $connecte
     *
     * @return User
     */
    public function setConnecte($connecte)
    {
        $this->connecte = $connecte;

        return $this;
    }

    /**
     * Get connecte
     *
     * @return string
     */
    public function getConnecte()
    {
        return $this->connecte;
    }

    /**
     * Set chargeId
     *
     * @param string $chargeId
     *
     * @return User
     */
    public function setChargeId($chargeId)
    {
        $this->chargeId = $chargeId;

        return $this;
    }

    /**
     * Get chargeId
     *
     * @return string
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    /**
     * Set premium
     *
     * @param boolean $premium
     *
     * @return User
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * Get premium
     *
     * @return boolean
     */
    public function getPremium()
    {
        return $this->premium;
    }
}
