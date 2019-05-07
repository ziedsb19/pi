<?php

namespace ActualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaires
 *
 * @ORM\Table(name="commentaires", indexes={@ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="id_actualite", columns={"id_actualite"})})
 * @ORM\Entity
 */
class Commentaires
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
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=false)
     */
    private $idUtilisateur;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_actualite", type="integer", nullable=false)
     */
    private $idActualite;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", length=65535, nullable=false)
     */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;


}

