<?php

namespace evenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventSignales
 *
 * @ORM\Table(name="event_signales")
 * @ORM\Entity(repositoryClass="evenementsBundle\Repository\EventSignalesRepository")
 */
class EventSignales
{

    /**
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User")
     * ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="evenementsBundle\Entity\Evenement")
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
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255)
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_signalisation", type="datetime")
     */
    private $dateSignalisation;


    public function __construct()
    {
        $this->dateSignalisation = new \DateTime();
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
     * Set sujet
     *
     * @param string $sujet
     *
     * @return EventSignales
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return EventSignales
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
     * Set dateSignalisation
     *
     * @param \DateTime $dateSignalisation
     *
     * @return EventSignales
     */
    public function setDateSignalisation($dateSignalisation)
    {
        $this->dateSignalisation = $dateSignalisation;

        return $this;
    }

    /**
     * Get dateSignalisation
     *
     * @return \DateTime
     */
    public function getDateSignalisation()
    {
        return $this->dateSignalisation;
    }

    /**
     * Set user
     *
     * @param \techEventsBundle\Entity\User $user
     *
     * @return EventSignales
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
     * @return EventSignales
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
