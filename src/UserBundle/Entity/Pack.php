<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pack
 *
 * @ORM\Table(name="pack", indexes={@ORM\Index(name="id_utilisateur", columns={"id_utilisateur", "id_demande"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\PackRepository")
 */
class Pack
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
     * @ORM\Column(name="nom_pack", type="string", length=255, nullable=false)
     */
    private $nomPack;

    /**
     * @var string
     *
     * @ORM\Column(name="description_pack", type="string", length=255, nullable=false)
     */
    private $descriptionPack;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_participants", type="integer", nullable=false)
     */
    private $nbParticipants;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_limite", type="date", nullable=false)
     */
    private $dateLimite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modifpack", type="datetime", nullable=false)
     */
    private $dateModifpack = 'CURRENT_TIMESTAMP';

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User")
     * @ORM\JoinColumn(name="id_utilisateur",referencedColumnName="id")
     */
    private $idUtilisateur;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Demandes")
     * @ORM\JoinColumn(name="id_demande",referencedColumnName="id")
     */
    private $idDemande;

    public function __construct()
    {
        $this->dateModifpack = (new \DateTime("now"));
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
     * Set nomPack
     *
     * @param string $nomPack
     *
     * @return Pack
     */
    public function setNomPack($nomPack)
    {
        $this->nomPack = $nomPack;

        return $this;
    }

    /**
     * Get nomPack
     *
     * @return string
     */
    public function getNomPack()
    {
        return $this->nomPack;
    }

    /**
     * Set descriptionPack
     *
     * @param string $descriptionPack
     *
     * @return Pack
     */
    public function setDescriptionPack($descriptionPack)
    {
        $this->descriptionPack = $descriptionPack;

        return $this;
    }

    /**
     * Get descriptionPack
     *
     * @return string
     */
    public function getDescriptionPack()
    {
        return $this->descriptionPack;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Pack
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
     * Set nbParticipants
     *
     * @param integer $nbParticipants
     *
     * @return Pack
     */
    public function setNbParticipants($nbParticipants)
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }

    /**
     * Get nbParticipants
     *
     * @return integer
     */
    public function getNbParticipants()
    {
        return $this->nbParticipants;
    }

    /**
     * Set dateLimite
     *
     * @param \DateTime $dateLimite
     *
     * @return Pack
     */
    public function setDateLimite($dateLimite)
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    /**
     * Get dateLimite
     *
     * @return \DateTime
     */
    public function getDateLimite()
    {
        return $this->dateLimite;
    }

    /**
     * Set dateModifpack
     *
     * @param \DateTime $dateModifpack
     *
     * @return Pack
     */
    public function setDateModifpack($dateModifpack)
    {
        $this->dateModifpack = $dateModifpack;

        return $this;
    }

    /**
     * Get dateModifpack
     *
     * @return \DateTime
     */
    public function getDateModifpack()
    {
        return $this->dateModifpack;
    }

    /**
     * Set idUtilisateur
     *
     * @param \techEventsBundle\Entity\User $idUtilisateur
     *
     * @return Pack
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
     * Set idDemande
     *
     * @param \UserBundle\Entity\Demandes $idDemande
     *
     */
    public function setIdDemande(\UserBundle\Entity\Demandes $idDemande = null)
    {
        $this->idDemande = $idDemande;

    }

    /**
     * Get idDemande
     *
     * @return \UserBundle\Entity\Demandes
     */
    public function getIdDemande()
    {
        return $this->idDemande;
    }
}
