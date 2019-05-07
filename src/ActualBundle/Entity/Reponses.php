<?php

namespace ActualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponses
 *
 * @ORM\Table(name="reponses", indexes={@ORM\Index(name="id_commentaire", columns={"id_commentaire"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class Reponses
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
     * @ORM\Column(name="id_commentaire", type="integer", nullable=false)
     */
    private $idCommentaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=false)
     */
    private $idUtilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="text", length=65535, nullable=false)
     */
    private $reponse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;


}

