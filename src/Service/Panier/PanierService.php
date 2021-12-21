<?php

namespace App\Service\Panier;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService{

    protected $session;
    protected $produitsRepository;

    public function __construct(SessionInterface $session, produitsRepository $produitsRepository){

        $this->session = $session;
        $this->produitsRepository = $produitsRepository;


    }

    public function add(int $id) {
     
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id]= 1 ;
        }
        
        $this->session->set('panier', $panier);
    }

    public function subst(int $id) {
    
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id]) || $panier[$id] > 1 ){
            $panier[$id]--;
            if ( $panier[$id] == 0 ){
                unset($panier[$id]);
            }
        }
        $this->session->set('panier', $panier);
    }

    public function remove(int $id) {
        $panier = $this->session->get('panier',[]);

        if(!empty($panier[$id])) {
            unset($panier[$id]); 
        }

        $this->session->set('panier', $panier);
    }
    
    public function getFullPanier() : array {

        $panier = $this->session->get('panier', []);
        $panierWithData =[];

        foreach($panier as $id=>$quantity){
            $panierWithData[] = [
                'produit' => $this->produitsRepository->find($id),
                'quantité' => $quantity
            ];
        }

        return $panierWithData;
    }

    
    public function getTotal() : float {
        $total = 0 ;
        foreach( $this->getFullPanier() as $item){
            $total += $item['produit']->getPrixpromo() * $item['quantité'];
        }
        return $total;
    }
}