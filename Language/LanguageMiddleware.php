<?php

namespace App\Middlewares\Language;

/*
Slim Request & Response
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*
PHP DI
*/
use Interop\Container\ContainerInterface;

/*
Twig
*/
use Slim\Views\Twig;


class LanguageMiddleware {

    protected $twig;
    protected $container;
    protected $requestLang = "";
    protected $availableLang;
    protected $requestUrl;
    protected $defaultLang;
    protected $langDir;


    public function __construct(array $config, ContainerInterface $c, Twig $twig){
        
        $this->availableLang = $config['availableLang'];
        $this->defaultLang = $config['defaultLang'];
        $this->langDir = $config['langFolder'];
        //Container and Twig
        $this->container = $c;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, callable $next){

        $this->requestLang = $this->getUserLang($request);
        $this->requestUrl = $request->getUri()->getPath();
        
        if ($this->requestUrl[0] == '/'){
            $this->requestUrl = substr($request->getUri()->getPath(), 1);
        }

        if ($this->requestUrl == '/' || empty($this->requestUrl) || preg_match("/^[a-z]{2}\//", $this->requestUrl) ||
            preg_match("/^[a-z]{2}$/", $this->requestUrl)){

            if (!empty($this->requestLang) && $this->ifLangExist($this->requestLang) == true){

                $language = new Language($this->requestLang, $this->langDir);

                $langArray = $language->getFileAsArray();

                $this->twig->getEnvironment()->addGlobal('lang', $langArray);
                
                $this->container->set('lang', $this->requestLang);
                $this->container->set('words', $langArray);

                return $next($request, $response);

            }
            else{

                $browserLang = $this->getBrowserLang();
                
                if ($this->ifLangExist($browserLang)){
                    return $response->withRedirect($request->getUri()->getBasePath() . '/' . $browserLang .'/');
                }else{
                    return $response->withRedirect($request->getUri()->getBasePath() . '/' . $this->defaultLang .'/');
                }
            }

        }else{
            return $next($request, $response);
        }
    }

    public function ifLangExist($lang){
        if (in_array($lang, $this->availableLang)){
            return true;
        }else{
            return false;
        }
    }

    public function getUserLang(Request $request){

        $url = $request->getUri()->getPath();
        if ($url[0] == '/'){
            $url = substr($url, 1);
        }
        return explode('/', $url)[0];
    }

    public function getBrowserLang(){
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }else{
            return $this->defaultLang;
        }
    }


}
