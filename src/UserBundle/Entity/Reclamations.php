<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamations
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ReclamationsRepository")
 */
class Reclamations
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
     /**
     * @ORM\ManyToOne(targetEntity="techEventsBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateurs;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255)
     */
   
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="contenue", type="text")
     */
    private $contenue;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="text")
     */
    private $reponse;


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
     * Set sujet
     *
     * @param string $sujet
     * @return Reclamations
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
     * Set contenue
     *
     * @param string $contenue
     * @return Reclamations
     */
    public function setContenue($contenue)
    {
        $this->contenue = $contenue;

        return $this;
    }

    /**
     * Get contenue
     *
     * @return string 
     */
    public function getContenue()
    {
        return $this->contenue;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     * @return Reclamations
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return string 
     */
    public function getReponse()
    {
        return $this->reponse;
    }
    
    /**
     * Set utilisateurs
     *
     * @param \UserBundle\Entity\Utilisateur $utilisateurs
     * @return Reclamations
     */
    public function setUtilisateurs(\UserBundle\Entity\Utilisateur $utilisateurs)
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    /**
     * Get utilisateurs
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }
}
