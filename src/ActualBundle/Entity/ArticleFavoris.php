<?php

namespace ActualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleFavoris
 *
 * @ORM\Table(name="article_favoris", indexes={@ORM\Index(name="id_actualite", columns={"id_actualite"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class ArticleFavoris
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
     * @var integer
     *
     * @ORM\Column(name="id_actualite", type="integer", nullable=false)
     */
    private $idActualite;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=false)
     */
    private $idUtilisateur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;


}

