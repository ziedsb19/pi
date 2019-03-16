<?php

namespace evenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageEvenement
 *
 * @ORM\Table(name="images_evenements")
 * @ORM\Entity(repositoryClass="evenementsBundle\Repository\ImageEvenementRepository")
 */
class ImageEvenement
{
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;


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
     * Set url
     *
     * @param string $url
     *
     * @return ImageEvenement
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return ImageEvenement
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set evenement
     *
     * @param \evenementsBundle\Entity\Evenement $evenement
     *
     * @return ImageEvenement
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
