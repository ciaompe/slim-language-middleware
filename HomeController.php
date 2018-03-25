<?php

namespace App\Controllers;

/**
*  Base Controller
*/

use Slim\Views\Twig;

/*
PHP DI
 */
use Interop\Container\ContainerInterface;


/*
Slim Router
 */
use Slim\Interfaces\RouterInterface;


class HomeController
{
	protected $container, $router, $view;

	public function __construct(ContainerInterface $c, RouterInterface $router, Twig $view){
		$this->container = $c;
		$this->router = $router;
		$this->view = $view;
	}

	public function test(Request $request, Response $response,) {
		echo $this->container->get('lang');
		
	}
	
}
