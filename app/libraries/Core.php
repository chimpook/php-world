<?php

/**
 * Class Core
 * 
 * @property string $currentControllerName 
 * @property string @currentMethodName
 * @property array @params
 * @property StdController|ApiController $currentController
 */
class Core
{
    /**
     * Default action is Regions/index
     */
    protected $currentControllerName = 'Regions';
    protected $currentMethodName = 'index';
    protected $params = [];
    protected $currentController;
    
    /**
     * @var string default mode is standard
     */
    protected $mode = 'std';

    public function __construct()
    {
        $url = $this->getUrl();

        // Check mode - api or default ?
        if (isset($url[0])) {
            if ($url[0] === 'api') {
                $this->mode = $url[0];
                array_shift($url);
            }
        }

        if (isset($url[0])) {
            if (file_exists('../app/controllers/' 
            . $this->mode . '/'
            . ucwords($url[0]) . '.php')) {
                $this->currentControllerName = ucwords($url[0]);
                unset($url[0]);
            }
        }

        require_once '../app/controllers/'
            . $this->mode . '/'
            . $this->currentControllerName . '.php';
        

        $this->currentController = new $this->currentControllerName();

        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethodName = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([
            $this->currentController,
            $this->currentMethodName
        ], $this->params);

    }

    /**
     * Getting current URL
     * 
     * @return string|boolean
     */
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            return $url;
        }

        return false;
    }
}

