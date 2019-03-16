<?php

namespace evenementsBundle\Repository;

/**
 * EvenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvenementRepository extends \Doctrine\ORM\EntityRepository
{
    public function evenementsNbr(){
        $query = $this->_em->createQuery("select count(e) from evenementsBundle:Evenement e where e.disponibilite = 1 and e.date > CURRENT_TIMESTAMP() ");
        return $query->getSingleScalarResult();
    }

    public function myEvents($userId){
        $query = $this->_em->createQuery("select count(e) from evenementsBundle:Evenement e join e.user u where e.disponibilite =1 and u.id = ".$userId);
        return $query->getSingleScalarResult();
    }

    public function savedEvents($userId){
        $query = $this->_em->createQuery("select count(e) from evenementsBundle:Evenement e join e.user u where e.disponibilite =1 and u.id = ".$userId);
        return $query->getSingleScalarResult();
    }

    public function subscribedEvents($userId){
        $query = $this->_em->createQuery("select count(i) from evenementsBundle:Inscription i join i.user u join i.evenement e where e.disponibilite =1 and u.id = ".$userId);
        return $query->getSingleScalarResult();
    }
}
