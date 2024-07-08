<?php

namespace App\Controller;

use App\Entity\ContactEmail;
use App\Form\ContactType;
use App\Notifications\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class HomeController extends AbstractController
{
    protected mixed $header;
    protected mixed $footer;
    protected mixed $cookie;

    public function __construct()
    {
        $header = Yaml::parseFile(__DIR__.'/../Entity/header.yaml');
        $this->header = $header;

        $footer = Yaml::parseFile(__DIR__.'/../Entity/footer.yaml');
        $this->footer = $footer;

        $cookie = Yaml::parseFile(__DIR__.'/../Entity/cookie.yaml');
        $this->cookie = $cookie;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/', name: 'home')]
    public function index(Request $request, ContactNotification $notification): Response
    {
        $home = Yaml::parseFile(__DIR__.'/../Entity/home.yaml');
        $services = Yaml::parseFile(__DIR__.'/../Entity/service.yaml');
        $benefit = Yaml::parseFile(__DIR__.'/../Entity/benefit.yaml');
        $location = Yaml::parseFile(__DIR__.'/../Entity/location.yaml');
        $opinions = Yaml::parseFile(__DIR__.'/../Entity/opinion.yaml');
        $contact = Yaml::parseFile(__DIR__.'/../Entity/contact.yaml');

        $contactEmail = new ContactEmail();

        $contactForm = $this->createForm(ContactType::class, $contactEmail);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $notification->sendEmail($contactEmail);
            $this->addFlash(
                'success',
                'contact.success'
            );

            return $this->redirect('/#hw-contact');
        } elseif ($request->isXmlHttpRequest() && !$contactForm->isValid()) {
            $this->addFlash(
                'danger',
                'contact.error'
            );

            return $this->redirect('/#hw-contact');
        } else {
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'header' => $this->header,
                'home' => $home,
                'services' => $services,
                'benefit' => $benefit,
                'location' => $location,
                'opinions' => $opinions,
                'contact' => $contact,
                'cookie' => $this->cookie,
                'footer' => $this->footer,
                'contactForm' => $contactForm->createView(),
            ]);
        }
    }

    #[Route(path: '/a-propos', name: 'about')]
    public function about(): Response
    {
        $about = Yaml::parseFile(__DIR__.'/../Entity/about.yaml');

        return $this->render('about/about.html.twig', [
            'controller_name' => 'HomeController',
            'header' => $this->header,
            'about' => $about,
            'cookie' => $this->cookie,
            'footer' => $this->footer,
        ]);
    }

    #[Route(path: '/policy/{slug}', name: 'policy')]
    public function policy(string $slug): Response
    {
        $policy = Yaml::parseFile(__DIR__.'/../Entity/policy.yaml');

        return $this->render('home/policy.html.twig', [
            'controller_name' => 'HomeController',
            'header' => $this->header,
            'policy' => $policy,
            'cookie' => $this->cookie,
            'footer' => $this->footer,
        ]);
    }

    #[Route(path: '/cgv/{slug}', name: 'cgv')]
    public function cgv(string $slug): Response
    {
        $cgv = Yaml::parseFile(__DIR__.'/../Entity/cgv.yaml');

        return $this->render('home/cgv.html.twig', [
            'controller_name' => 'HomeController',
            'header' => $this->header,
            'cgv' => $cgv,
            'cookie' => $this->cookie,
            'footer' => $this->footer,
        ]);
    }
}
