<?php
require PATH.'/core/errorHandler.php';

class Router
{
    /*
     * Redirect to a specific controller
     */
    private $routes;
    const CONTROLLER = 1;
    const ACTION = 2;

    public function __construct()
    {
        $this->routes = require(PATH.'/config/routes.php');
    }

    private function getUrl($url)
    {
        /*
         *  Creating an array based on url
         *  Argument - (string) $url
         *  Return array
         */
        return explode('/', $url);
    }

    private function getDataRoute($type, $route)
    {
        /*
         * Creating name controller or action
         * Arguments - (const) $type, (string) $route
         * Return string
         */
        $controller_name = explode('/', $route);
        if($type == self::CONTROLLER) return 'controller'.ucfirst($controller_name[0]);
        return 'action'.ucfirst($controller_name[1]);
    }

    private function getPathController($controller)
    {
        /*
         * Creating the path to the controller
         * Arguments - (string) $controller
         * Return string
         */
        return PATH.'/controllers/'.$controller.'.php';
    }

    private function getUrlArgument($url)
    {
        /*
         * Get the argument of string query
         * Argument - (string) $url
         * Return array
         */
        array_shift($url);
        return $url;
    }

    public function run($url)
    {
        /*
         * Run the router
         */
        foreach($this->routes as $url_pattern => $route)
        {
            if(preg_match($url_pattern, $url))
            {
                $url = $this->getUrl($url);

                $controller_name = $this->getDataRoute(self::CONTROLLER, $route);
                $action_name = $this->getDataRoute(self::ACTION, $route);

                $path_controller = $this->getPathController($controller_name);

                if(file_exists($path_controller))
                {
                    require($path_controller);
                } else {
                    throw new Exception("This file doesn't exists");
                }

                if(count($url) > 1)
                {
                    $argument = $this->getUrlArgument($url);

                    $controller = new $controller_name;
                    call_user_func_array([$controller_name, $action_name], $argument);
                } else {
                    $controller = new $controller_name;
                    $controller->$action_name();
                }
                return true;
            }
        }
        errorHandler::errorNotFound();
    }

}

?>
