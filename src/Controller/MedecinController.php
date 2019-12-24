<?php

namespace App\Controller;
use App\Entity\Medecin;
use App\Entity\Service;
use App\Entity\Specialite;
use App\Form\MedcinType;
use App\Form\Type;
use App\Repository\MedecinRepository;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class MedecinController extends AbstractController
{
    /**
     * @Route("/medecin", name="medecin")
     */
    public function showmMedcin(MedecinRepository $repos)
    {
        
        $medcin =$repos->findAll();
     
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(Medecin::class, $medcin);
        
        $em->flush();

        return $this->render('medecin/index.html.twig', [
            'medcin' =>$medcin,
        ]);
    }
     /**
     * @Route("/medecin/add", name="medecin.add")
     */
    public function news(Request $request) 
    {
        $medecinlast = $this->getDernierMed() + 1;
        $medcin =new Medecin();
        $form =$this->createForm(MedcinType::class,$medcin);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $twofirstListter =\strtoupper(\substr($medcin->getService()->getLibelle(),0,2));
            $longId = strlen((string)$medecinlast);
            $matricule = \str_pad("M".$twofirstListter,8 - $longId,"0").$medecinlast;
            $medcin->setMatricule($matricule);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($medcin);
            $entityManager->flush();
            return $this->redirectToRoute('medecin');
            

        }


        return $this->render('medecin/ajout.html.twig', [
            'ajouter' =>$form->createView(),
        ]);
    }
    public function getDernierMed(){
        $ripo = $this->getDoctrine()->getRepository(Medecin::class);
        $medecinlast = $ripo->findBy([],['id'=>'DESC']);
        if($medecinlast == null){
            return $id = 0;
        }else{
            return  $medecinlast[0]->getId();
        }
        

    }




}
