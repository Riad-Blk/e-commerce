<?php
namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Concerts;
use App\Repository\UserRepository;
use App\Controller\AccountController;
use App\Service\Panier\PanierService;
use App\Controller\SecurityController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{   
    
    /**
     * @Route("/panier", name="panier_index")
     */
    public function index(PanierService $panierService){

        //setcookie('panier',json_encode($panierService->getFullPanier()),time()+60*60*24*30);
        $concerts = $this->getdoctrine()->getRepository(Concerts::class)->findall();
        //dd($concerts);
        return $this->render('panier/index.html.twig', [
            'concerts'=>$concerts,
            'items' =>$panierService->getFullPanier(),
            'total' => $panierService->getTotal()
        ]);    
    }

    /**
     * @Route("/panier/facture", name="panier_facture")
     */
    public function facture(PanierService $panierService,Request $request,EntityManagerInterface $manager){
        
        $user = $this->getUser();
        $point = $request->get('point', 'none');// correspond à $_POST['point ']
        $concert = $this->getdoctrine()
            ->getRepository(Concerts::class)
            ->findOneBy(['id' => $point]);
        //dd($concert);
        //dd($point);

        // options du pdf
        $pdfOptions = new Options();

        // police
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        //istance de dompdf
        $dompdf = new Dompdf($pdfOptions);
        $items=$panierService->getFullPanier();
        $total=$panierService->getTotal();
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
                'items' =>$items,
                'total' =>$total,
                'concert' =>$concert                           
            ]
        ]);
        $dompdf->setHttpContext($context);

        // on genere le HTML
        $html = $this-> renderView('panier/facturePDF.html.twig',[
            'items'=>$items,
            'total' =>$total,
            'concert' =>$concert
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','landscape');
        $dompdf->render();

        // on genere un nom de fichier
        $fichier='Facture'.$this->getUser()->getId().'.pdf';

        //envoi au navigateur
        $dompdf->stream($fichier, [
            'attachment' => true
        //inserion donnees
        //$commande->setPanier(json_encode($panierService->getFullPanier()))
        //         ->setTotal($total)
        //         ->setPointR($concert)   
        //    $manager->persist($commande);
        //    $manager->flush();
        ]);
        return new Response();
         
    }

    /**
     * @Route("/panier/facture/download", name="panier_facture_download")
     */
    public function facture_download(PanierService $panierService){

        // options du pdf
        $pdfOptions = new Options();

        // police
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        //istance de dompdf
        $dompdf = new Dompdf($pdfOptions);
        $items=$panierService->getFullPanier();
        $total=$panierService->getTotal();
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
                'items' =>$items,
                'total' =>$total,                           
            ]
        ]);
        $dompdf->setHttpContext($context);

        // on genere le HTML
        $html = $this-> renderView('panier/facturePDF.html.twig',[
            'items'=>$items,
            'total' =>$total 
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','landscape');
        $dompdf->render();

        // on genere un nom de fichier
        $fichier='Facture'.$this->getUser()->getId().'.pdf';

        //envoi au navigateur
        $dompdf->stream($fichier, [
            'attachment' => true
        ]);
        return new Response();
         
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add(int $id, PanierService $panierService){
        $panierService->add($id);
        return $this->redirectToRoute("panier_index");
    }
    /**
     * @Route("/panier/subst/{id}", name="panier_subst")
     */
    public function subst(int $id, PanierService $panierService){
        $panierService->subst($id);
        return $this->redirectToRoute("panier_index");
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove(int $id, PanierService $panierService){
        $panierService->remove($id);
        return $this->redirectToRoute("panier_index");
    }
    
}