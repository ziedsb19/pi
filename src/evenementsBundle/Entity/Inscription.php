<?php

namespace evenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity(repositoryClass="evenementsBundle\Repository\InscriptionRepository")
 */
class Inscription
{
    /**
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="evenementsBundle\Entity\Evenement")
     * ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id")
     */
    private $evenement;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime")
     */
    private $dateInscription;


    public function __construct()
    {
        $this->dateInscription= new \DateTime();
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
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     *
     * @return Inscription
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
     * Set user
     *
     * @param \techEventsBundle\Entity\User $user
     *
     * @return Inscription
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
     * Set evenement
     *
     * @param \evenementsBundle\Entity\Evenement $evenement
     *
     * @return Inscription
     */
    public function setEvenement(\evenementsBundle\Entity\Evenement $evenement = null)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \evenementsBundle\Entity\Evenement
     */
    public function getEvenement()
    {
        return $this->evenement;
    }
}
