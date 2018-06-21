<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Joueur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * Accueil
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', []);
    }

    /**
     * @Route("/login", name="login")
     *
     * Connexion
     */
    public function loginAction(Request $request, SessionInterface $session)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $joueur = $this->getEm()->getRepository('AppBundle:Joueur')->findOneBy(array(
            'email' => $email,
            'password' => $password
        ));

        if(!$joueur)
        {
            return $this->render('default/index.html.twig', [
                'errorLogin' => true
            ]);
        }

        // Création de la session du joueur
        $session->set($joueur->getNom(), 0);

        return $this->redirectToRoute('game', [
            'joueur' => $joueur->getId()
        ]);
    }

    /**
     * @Route("/signup", name="signup")
     *
     * Page d'inscription
     */
    public function singupAction(Request $request)
    {
        // Création du formulaire
        $newJoueur = new Joueur();

        $form = $this->createFormBuilder($newJoueur)
            ->add('nom', TextType::class, array(
                'label' => 'Nom',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Entrez votre nom'
                )
            ))
            ->add('prenom', TextType::class, array(
                'label' => 'Prenom',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Entrez votre prenom'
                )
            ))
            ->add('email', TextType::class, array(
                'label' => 'Email',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Entrez votre email'
                )
            ))
           ->add('ville', EntityType::class, array(
               'class' => 'AppBundle:Ville',
               'choice_label' => 'nom',
           ))
            ->add('sign', SubmitType::class, array('label' => 'Je m\'inscris !'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération du joueur créé
            $joueur = $form->getData();

            $password = $request->request->get('password');
            $niveau = $request->request->get('niveau');

            $joueur->setPassword($password);
            $joueur->setNiveau($niveau);

            $this->getEm()->persist($joueur);
            $this->getEm()->flush(); // Enregistrement du joueur dans la base de données

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/game/joueur/{joueur}", name="game", requirements={"joueur"="\d+"})
     *
     * Page de jeu
     */
    public function gameAction($joueur, SessionInterface $session)
    {
        // Joueur connecté
        $joueur = $this->getEm()->getRepository('AppBundle:Joueur')->findOneById($joueur);

        $verbe = $this->_generateVerbs();

        return $this->render('default/game.html.twig', [
            'joueur' => $joueur,
            'verbe' => $verbe[0],
            'score' => $session->get($joueur->getNom())
        ]);
    }

    /**
     * @Route("/correction", name="correction")
     *
     * Connexion
     */
    public function correctionAction(Request $request, SessionInterface $session)
    {
        $id_joueur = $request->request->get('id_joueur');
        $id_verbe = $request->request->get('id_verbe');

        $preterit = $request->request->get('preterit');
        $participePasse = $request->request->get('participePasse');

        $joueur = $this->getEm()->getRepository('AppBundle:Joueur')->findOneById($id_joueur);
        $verbe = $this->getEm()->getRepository('AppBundle:Verbe')->findOneById($id_verbe);

        // Gestion du score
        if(($verbe->getPreterit() == $preterit) && ($verbe->getParticipePasse() == $participePasse))
        {
            $score = $session->get($joueur->getNom()) + 1;

            $session->set($joueur->getNom(), $score);

            return $this->redirectToRoute('game', [
                'joueur' => $joueur->getId()
            ]);
        }
        else
        {
            return $this->redirectToRoute('homepage');
        }
    }


    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     *
     * Permet de se connecter à la base de données afin de faire les requêtes
     */
    private function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return array
     *
     * Recherche de verbes au hasard
     */
    private function _generateVerbs()
    {
        $verbe = $this->getEm()->getRepository('AppBundle:Verbe')->findAll();

        shuffle($verbe); // Mélange de verbe

        return $verbe;
    }
}
