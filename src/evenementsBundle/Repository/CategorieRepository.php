<?php

namespace evenementsBundle\Repository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    public function categories(){
        $query = $this->_em->createQuery("select c, count(c) from evenementsBundle:Categorie c join ");
    }
}
