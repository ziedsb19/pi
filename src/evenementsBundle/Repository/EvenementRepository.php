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

    public function EventsOrganiseRecents($id){
        $query= $this->_em->createQuery("select e,u from evenementsBundle:Evenement e join e.user u
         where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1 and u.id = ".$id." order by e.vues DESC , e.date ASC")->getResult();
        return $query;
    }

    public function EventsOrganisePasses($id){
        $query= $this->_em->createQuery("select e,u from evenementsBundle:Evenement e join e.user u
         where e.date< CURRENT_TIMESTAMP() and e.disponibilite = 1 and u.id =".$id." order by e.vues DESC , e.date ASC")->getResult();
        return $query;
    }

    public function EventsEnregistreRecents(){
        $query= $this->_em->createQuery("select e,u,eu from evenementsBundle:Evenement e join e.user u join e.evenementSauvegardes eu
         where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1  order by e.vues DESC , e.date ASC")->getResult();
        return $query;
    }


    public function EventsEnregistreNbr(){
        $query= $this->_em->createQuery("select e,u from evenementsBundle:Evenement e join e.user u join e.evenementSauvegardes eu
         where e.disponibilite = 1 ")->getResult();
        return $query;
    }

    public function custom(){
        $query= $this->_em->createQuery("select e,u,c from evenementsBundle:Evenement e join e.user u join e.evenementSauvegardes eu
         left join e.categories c where e.date> CURRENT_TIMESTAMP() and e.disponibilite = 1  order by e.vues DESC , e.date ASC")->getResult();
        return $query;
    }

    //public function search ($str){
    //    return $this->_em->createQuery("select e from evenementsBundle:Evenement e where e.titre like '$str%'")->getResult();
    //}

    public function getCategories(){
        $query = $this->_em->createQuery("select count(e),c.nom from evenementsBundle:Evenement e  join e.categories c 
        group by c");
        return $query->getResult();
    }

    public function chart(){
        //return $this->_em->createNamedQuery("select month(date_modification), count(e.id) from evenements e group by month(date_modification)")->getArrayResult();
        return $this->_em->createQuery("select month(e.dateModification), count(e), from evenementsBundle:Evenement e 
        group by month(e.dateModification)")->getArrayResult();
    }
}
