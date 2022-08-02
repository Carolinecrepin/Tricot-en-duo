<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\EmailModel;
use Mailjet\Client;
use Mailjet\Resources;

class EmailSender{
    
    public function sendEmailNotificationByMailJet(User $user, EmailModel $email){

        $mj = new Client($_ENV['MJ_API_KEY_PUBLIC'], $_ENV['MJ_API_KEY_PRIVATE'],true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
            [
                'From' => [
                'Email' => "tricotenduo@gmail.com",
                'Name' => "Tricot en Duo contact"
                ],
                'To' => [
                [
                    'Email' => $user->getEmail(),
                    'Name' => $user->getFullName()
                ]
                ],
                'TemplateID' => 4108221,
                'TemplateLanguage' => true,
                'Subject' => $email->getSubject(),
                'Variables' => [
                    'title'=> $email->getTitle(),
                    'content' => $email->getContent()
                ]
            ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //$response->success() && dd($response->getData());
    }
}