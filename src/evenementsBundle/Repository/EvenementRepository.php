<?php

namespace evenementsBundle\Repository;

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

    public function lastEvents($limit){
        $query= $this->_em->createQuery("select e from evenementsBundle:Evenement e where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1 order by e.dateModification desc")->setMaxResults($limit);
        return $query->getResult();
    }

    public function allEventsByViews(){
        $query= $this->_em->createQuery("select e,u from evenementsBundle:Evenement e join e.user u
         where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1 order by e.vues DESC , e.date ASC");
        return $query;
    }

    public function allEventsByDateModif(){
        $query= $this->_em->createQuery("select e,u from evenementsBundle:Evenement e join e.user u
         where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1 order by e.dateModification DESC");
        return $query;
    }

    public function allEventsByDate(){
        $query= $this->_em->createQuery("select e,u from evenementsBundle:Evenement e join e.user u
         where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1 order by e.date ASC");
        return $query;
    }

    //public function search ($str){
    //    return $this->_em->createQuery("select e from evenementsBundle:Evenement e where e.titre like '$str%'")->getResult();
    //}

}
