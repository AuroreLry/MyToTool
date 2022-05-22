<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, UserRepository $userRepository, Session $session): Response
    {

        if($session->get('user')) {
            return $this->redirectToRoute('app_home');
        }
        if($request->isMethod('post')) {
            $identifiant = $request->request->get('email');
            $utilisateur = $userRepository->findOneBy(['login' => $identifiant]);

            if($utilisateur != null) {
                $session->set('user', $utilisateur);
                return $this->redirectToRoute('app_home');
            }
            $this->addFlash('error', 'Erreur dans la matrice');
        }
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController'
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(Session $session) {
        $session->clear();
        return $this->redirectToRoute('app_login');
    }
}
