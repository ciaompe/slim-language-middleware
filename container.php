<?php

use App\Middlewares\Language\LanguageMiddleware;


return [

	LanguageMiddleware::class => function(ContainerInterface $c) {
		return new LanguageMiddleware([
			'availableLang' => $c->get('app')['availableLang'],
	    	'defaultLang' => $c->get('app')['lang'],
	    	'langFolder' => APP_PATH.'/lang/'
		], $c, $c->get(Twig::class));
	},
];