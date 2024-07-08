<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Yaml\Yaml;

class SecurityController extends AbstractController
{
    protected mixed $header;
    protected mixed $footer;

    public function __construct()
    {
        $header = Yaml::parseFile(__DIR__.'/../Entity/header.yaml');
        $this->header = $header;

        $footer = Yaml::parseFile(__DIR__.'/../Entity/footer.yaml');
        $this->footer = $footer;
    }

    #[Route(path: '/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('articles_list');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('danger', $error->getMessage());
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, ['email' => $authenticationUtils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'header' => $this->header,
            'footer' => $this->footer,
            'formView' => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout()
    {
        //        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
