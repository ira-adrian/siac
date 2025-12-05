<?php
// src/Controller/MailerController.php
namespace Siarme\DocumentoBundle\Service;

use Siarme\AusentismoBundle\Util\Util;
use Symfony\Component\Mime\Email;
use \Swift_Mailer;
use Symfony\Component\Templating\EngineInterface;
use Twilio\Rest\Client;


class EmailService
{

    private $mailer;
    private $templating;

    public function __construct(Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }


    public function sendSms($telefono, $texto)
    {
        // Tu Account SID y Auth Token de Twilio
        $sid    = $this->container->getParameter('twilio_account_sid');
        $token  = $this->container->getParameter('twilio_auth_token');
        $twilio = new Client($sid, $token);

        // Número de Twilio con capacidad para SMS
        // $from = $this->container->getParameter('twilio_phone_sms');
          $from = $this->container->getParameter('twilio_phone_sms');
        
        // Número de destino del SMS
        $to = "+549".$telefono; 
        
        // Contenido del mensaje
        $messageBody = Util::limpiarParaGSM7($messageBody);

        try {
            $message = $twilio->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $messageBody,
                ]
            );

            return true;
            // new Response('Mensaje SMS enviado correctamente. SID: ' . $message->sid);
        } catch (\Exception $e) {
            return  false;
            //new Response('Error al enviar el SMS: ' . $e->getMessage());
        }
    }

    public function enviarEmail($email,$nombre, $asunto, $mensaje)
    {

            $body = $this->templating->render('ExpedienteBundle:Email:enviar_mensaje.html.twig',
                        ['nombre' => $nombre,'mensaje' => $mensaje ]);

            try {

                $message = (new \Swift_Message("asunto de pruenba"))
                ->setFrom('soporte@compras-sca.online')
                ->setTo($email)
                ->setBody($body, 'text/html');
                $this->mailer->send($message);

                return true; 
            } catch (\Swift_TransportException $e) {
                return  false;
            }
            
    }

}
