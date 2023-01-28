<?php

class App{
    // controlleur par defaut
    protected $controller = 'home';
    // method par defaut
    protected $method = 'index';
    // Les parametres de l'url
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        if(is_null($url)){
            $url = [$this->controller];
        }
        // verifier si le controlleur existe
        if(file_exists('../app/controllers/'.$url[0].'.php'))
        {   
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/'.$this->controller.'.php';
        
        $this->controller = new $this->controller;

        // verifier si la methode existe
        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        } 

        // attribuer les parametres de l'url qui restent après les unsets
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if(isset($_GET['url'])){
            return $url = explode(('/'), filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}