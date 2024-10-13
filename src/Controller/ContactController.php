<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact->setName($form->getName());
            $contact->setEmail($form->getData()->getEmail());
            $contact->setMessage($form->getData()->getMessage());
            $contact->setObject($form->getData()->getObject());
            $today = new \DateTimeImmutable();
            $contact->setCreatedAt($today);

            $em->persist($contact);

            $em->flush();

            return $this->redirectToRoute('home');


        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
