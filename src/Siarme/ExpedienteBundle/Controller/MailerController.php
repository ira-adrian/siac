<?php
// src/Controller/MailerController.php
namespace Siarme\ExpedienteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Mime\Email;
use Siarme\ExpedienteBundle\Entity\Tarea;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;

/**
 * Mensaje controller.
 *
 * @Route("email")
 */
class MailerController extends Controller
{
    /**
     * Lists all mensaje entities.
     *
     * @Route("/sms", name="enviar_sms")
     * @Method("GET")
     */
    public function sendSmsAction(Request $request)
    {
        // Tu Account SID y Auth Token de Twilio
        $sid    = $this->container->getParameter('twilio_account_sid');
        $token  = $this->container->getParameter('twilio_auth_token');
        $twilio = new Client($sid, $token);

        // Número de Twilio con capacidad para SMS
        // $from = $this->container->getParameter('twilio_phone_sms');
          $from = $this->container->getParameter('twilio_phone_sms');
        
        // Número de destino del SMS
        $to = '+543834338608'; // Reemplaza con el número de teléfono del destinatario
        
        // Contenido del mensaje
        $messageBody = 'Desde compras-sca.online Symfony y Twilio!';

        try {
            $message = $twilio->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $messageBody,
                ]
            );

            return new Response('Mensaje SMS enviado correctamente. SID: ' . $message->sid);
        } catch (\Exception $e) {
            return new Response('Error al enviar el SMS: ' . $e->getMessage());
        }
    }

     /**
     * @Route("/enviar", name="email_enviar")
    * @Method("POST")
     */
    public function enviarEmail(Request $request)
    {

            $to = $request->request->get('to');
            $texto = $request->request->get('texto');
            $subjet= $request->request->get('subjet');
           
           // dump($to);
            //exit();[$to, 'ira.adrian@gmail.com' => 'Adrian Ibañez']

 /** Give the message a subject
  ->setSubject('Your subject')

  // Set the From address with an associative array
  ->setFrom(['john@doe.com' => 'John Doe'])

  // Set the To addresses with an associative array (setTo/setCc/setBcc)
  ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])

  // Give it a body
  ->setBody('Here is the message itself')

  // And optionally an alternative body
  ->addPart('<q>Here is the message itself</q>', 'text/html')

  // Optionally add any attachments
  ->attach(Swift_Attachment::fromPath('my-document.pdf'))

  */       $enviado = true;
            try {
                $message = \Swift_Message::newInstance()
                ->setSubject($subjet)
                ->setFrom('soporte@compras-sca.online')
                ->setTo($to)
              //  ->setCc('ira.adrian@gmail.com')
               // ->setBcc(['ira.adrian@gmail.com'=>'ESTO ES UNA PRUEBA'])

                //->addCC('copiado@hotmail.com')
                
                ->setBody($texto,
                        'text/html'
                 /*   $this->renderView(
                        'HelloBundle:Hello:email.txt.twig',
                        ['name' => $name]
                    )*/
                );
           // $message->addBCC('ira.adrian@gmail.com');
            $mailer = $this->get('mailer');
            $result = $mailer->send($message);
                
            } catch (\Swift_TransportException $e) {

            $msj= 'No se ha podido enviar el email al correo:'.$to;
            //$msj = $e->getMessage();
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $enviado = false;
            }

            if ($enviado) {
                $msj= 'Se ha enviado el email al correo '.$to;
                        $this->get('session')->getFlashBag()->add(
                                'mensaje-info',
                                $msj);

            }
               $referer= $request->headers->get('referer');
    return $this->redirect($referer);
    }


    /**
     * @Route("/{id}/tarea/enviar", name="email_enviar_tarea")
    * @Method("POST")
     */
    public function enviarTareaEmail(Request $request, Tarea $tarea)
    {

            $texto = $request->request->get('texto');
            $tramite = $tarea->getTramite();
            $subjet= "Mensaje sobre Tarea ".$tramite->getTipoTramite().' N°: '.$tramite->getNumeroTramite();
            //dump($texto);
            //exit();
            try {
                $message = \Swift_Message::newInstance()
                ->setSubject($subjet)
                ->setFrom('soporte@compras-sca.online')
                ->setTo($tarea->getUsuario()->getAgente()->getEmail())
                ->setBody($texto,
                        'text/html'
                 /*   $this->renderView(
                        'HelloBundle:Hello:email.txt.twig',
                        ['name' => $name]
                    )*/
                );
            $mailer = $this->get('mailer');
            $mailer->send($message);
                
            } catch (\Swift_TransportException $e) {
            $msj= 'No se ha podido enviar el email al correo:'.$tarea->getUsuario()->getAgente()->getEmail();
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            }


           $msj= 'Se ha enviado el email al correo '.$tarea->getUsuario()->getAgente()->getEmail();
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
    $referer= $request->headers->get('referer');
    return $this->redirect($referer);
    }

    /**
     * @Route("/test", name="email_test")
    * @Method("GET")
     */
    public function testEmail(Request $request)
    {
            $message = \Swift_Message::newInstance()
                ->setSubject('Hola mi primer email')
                ->setFrom('soporte@compras-sca.online')
                ->setTo('ira.adrian@gmail.com')
                ->setBody('Esto es una prueba'
                 /*   $this->renderView(
                        'HelloBundle:Hello:email.txt.twig',
                        ['name' => $name]
                    )*/
                )
            ;
            $mailer = $this->get('mailer');
            $mailer->send($message);

           $msj= 'Se ha enviado el email:';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
    $referer= $request->headers->get('referer');
    return $this->redirect($referer);
    }
}
