<?php

namespace App\Controller;

use App\Services\EmailSender;
use App\Entity\EmailModel;
use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{

    #[Route('/', name: 'contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository, EmailSender $emailsender): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);

            //Envoie d'email
            $user = (new User())
                    ->setEmail('tricotenduo@gmail.com')
                    ->setFirstname('Tricot en Duo')
                    ->setLastname('shop');

            $email = (new EmailModel())
                    ->setTitle('Bonjour'. $user->getFullName())
                    ->setSubject('Nouveau contact sur votre site')
                    ->setContent('<br>De :' . $contact->getEmail()
                                .'<br>Nom :' . $contact->getName()
                                .'<br>Sujet :' . $contact->getSubject()
                                .'<br><br>'.$contact->getContent());

            $emailsender->sendEmailNotificationByMailJet($user, $email);

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            $this->addFlash('contact_success', 'Votre message a bien été envoyé, un conseiller vous répondra très rapidement!');
        }

        if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('contact_error', 'Votre formulaire de contact comporte des erreurs merci de les corriger et réessayez');
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }
}
