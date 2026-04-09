<?php

namespace Siarme\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Security controller.
 *
 * @Route("security")
 */
class SecurityControllerController extends Controller
{

	/**
	* @Route("/login", name="usuario_login")
	*/
	public function loginAction()
	{

		//proporciona métodos para obtener información relacionada con el proceso de login

		$authUtils = $this->get('security.authentication_utils');

		return $this->render('UsuarioBundle:Security:login.html.twig',
					  array('last_username' => $authUtils->getLastUsername(),
				   			'error' => $authUtils->getLastAuthenticationError(),
		));

	}

	/**
	* @Route("/login_check", name="usuario_login_check")
	*/
	public function loginCheckAction()
	{
		// el "login check" lo hace Symfony automáticamente, por lo que
		// no hay que añadir ningún código en este método
	}

	/**
	* @Route("/logout", name="usuario_logout")
	*/
	public function logoutAction()
	{
		// el logout lo hace Symfony automáticamente, por lo que
		// no hay que añadir ningún código en este método
	}
}
