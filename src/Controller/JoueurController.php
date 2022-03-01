<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Equipe;
use App\Repository\JoueurRepository;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\String\Slugger\SluggerInterface;


class JoueurController extends AbstractController
{
    private EntityManagerInterface $em;

    private JoueurRepository $joueurRepository;

    private EquipeRepository $equipeRepository;

    /**
     * JoueurController constructor.
     * @param EntityManagerInterface $em
     * @param JoueurRepository $joueurRepository
     * @param EquipeRepository $equipeRepository
     */
    public function __construct(EntityManagerInterface $em, JoueurRepository $joueurRepository, EquipeRepository $equipeRepository)
    {
        $this->em = $em;
        $this->joueurRepository = $joueurRepository;
        $this->equipeRepository = $equipeRepository;
    }


    /**
     * @Route("/joueur/combat", name="combat")
     */
    public function combat(Request $request): Response
    {
        //Récupération des valeurs des équipes choicies
        $post_data = json_decode($request->getContent());

        //recherche des informations de chaque équipe
        $infosEquipe1 = $this->equipeRepository->find($post_data->equipe1);
        $infosEquipe2 = $this->equipeRepository->find($post_data->equipe2);
        
        //Recherche du nb de joueurs pour chaque équipe
        $countequipe1 = $this->joueurRepository->countJoueur($post_data->equipe1);
        $countequipe2 = $this->joueurRepository->countJoueur($post_data->equipe2);
        
        //Recherche d'un joueur aleatoire par équipe
        $nbAleatoireJoueur1 = rand(1, $countequipe1);
        $nbAleatoireJoueur2 = rand(1, $countequipe2);

       

        //Recherche des informations de chaque joueur
        $infosjoueur1 = $this->joueurRepository->findOneBy(
            [
                'numJoueurEquipe'=>$nbAleatoireJoueur1,
                'equipe'=>$post_data->equipe1
            ]
        );

        $infosjoueur2 = $this->joueurRepository->findOneBy(
            [
                'numJoueurEquipe'=>$nbAleatoireJoueur2,
                'equipe'=>$post_data->equipe2
            ]
        );

        //Calcul des coups de chaque joueur 

        $sumJoueur1 = ($infosjoueur1->getPointDefense() * 2) + ($infosjoueur1->getPointAttaque() *2) + ($infosjoueur1->getPointVitesse() *1.5) +($infosjoueur1->getPointEndurence() * 2);
        $sumJoueur2 = ($infosjoueur2->getPointDefense() * 2) + ($infosjoueur2->getPointAttaque() *2) + ($infosjoueur2->getPointVitesse() *1.5) +($infosjoueur2->getPointEndurence() * 2);

        $sumVDJoueur1 = $infosjoueur1->getNbVictoire() -  $infosjoueur1->getNbDefaite();
        $sumVDJoueur2 = $infosjoueur2->getNbVictoire() -  $infosjoueur2->getNbDefaite();

        $sumJoueur1 = $sumJoueur1 + $sumVDJoueur1;
        $sumJoueur2 = $sumJoueur2 + $sumVDJoueur2;

        //Contrôle du joueur gagnant et maj des tables 
        if($sumJoueur1>$sumJoueur2) {
            $reponse = "C'est l'équipe ".$infosjoueur1->getEquipe()->getNomEquipe(). " qui gagne"; 
            $infosjoueur1->setNbVictoire($infosjoueur1->getNbVictoire()+1);
            $infosjoueur2->setNbDefaite($infosjoueur2->getNbDefaite()+1);

            $infosEquipe1->setNbPartieGagne($infosEquipe1->getNbPartieGagne()+1);
            $infosEquipe2->setNbPartiePerdue($infosEquipe2->getNbPartiePerdue()+1);

            $this->em->persist($infosjoueur1);
            $this->em->persist($infosjoueur2);

            $this->em->persist($infosEquipe1);
            $this->em->persist($infosEquipe2);

            $this->em->flush();
        }else if($sumJoueur1<$sumJoueur2) {
            $reponse = "C'est l'équipe ".$infosjoueur2->getEquipe()->getNomEquipe(). " qui gagne";
            $infosjoueur2->setNbVictoire($infosjoueur2->getNbVictoire()+1);
            $infosjoueur1->setNbDefaite($infosjoueur1->getNbDefaite()+1);

            $infosEquipe2->setNbPartieGagne($infosEquipe2->getNbPartieGagne()+1);
            $infosEquipe1->setNbPartiePerdue($infosEquipe1->getNbPartiePerdue()+1);

            $this->em->persist($infosjoueur1);
            $this->em->persist($infosjoueur2);

            $this->em->persist($infosEquipe1);
            $this->em->persist($infosEquipe2);

            $this->em->flush();
        }    
        else {
            $reponse = "Aucun gagnant"; 
            $infosEquipe1->setNbPartieNull($infosEquipe1->getNbPartieNull()+1);
            $infosEquipe2->setNbPartieNull($infosEquipe2->getNbPartieNull()+1);

            $this->em->persist($infosEquipe1);
            $this->em->persist($infosEquipe2);

            $this->em->flush();
        } 
        
        return new JsonResponse([
            'messagegagnante' =>  $reponse
        ]); 
    }
}