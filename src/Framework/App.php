<?php

namespace SAmvc\Framework;

/**
 * Class App
 * @package SAmvc\Framework
 */
class App {

    private $_url = null;
    private $_utf8 = null;
    private $_controller = null;

    private $_controllerPath = 'App\Controllers';
    private $_errorController = 'Error';
    private $_defaultController = 'Index';
    private $_defaultMethod = 'index';
    private $_controllerSuffix = 'Controller';

    /**
     * Starts the App
     *
     * @return boolean
     */
    public function init()
    {
        // starts all the init functions in the config namespace
        $this->_start();
        // Sets the protected $_url
        $this->_getUrl();
        // sub folder controller
        $folder = str_replace('\\', '/', $this->_controllerPath.'/'.$this->_url[0]);
        $folder = str_replace('App/', '', $folder);
        while (isset($this->_url[0]) && is_dir($folder) && $this->_url[0])
        {
            $this->setControllerPath($this->_controllerPath.'\\'.$this->_url[0]);
            $folder .= '/'.$this->_url[0];
            array_shift($this->_url);
        }
        // Load the default controller if no url is set
        if (empty($this->_url[0]))
        {
            $this->_loadDefaultController();

            return false;
        }

        $this->_loadExistingController();
        $this->_callControllerMethod();
    }

    /**
     * initialize the app
     */
    private function _start()
    {
        $namespace = 'Config';

        // Relative namespace path
        $namespaceRelativePath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);

        // Iterate include paths
        $classArr = array();
        $path = PUBLIC_HTML.DIRECTORY_SEPARATOR.$namespaceRelativePath;
        if (is_dir($path))
        { // Does path exist?
            $dir = dir($path); // Dir handle
            while (false !== ($item = $dir->read()))
            {  // Read next item in dir
                $matches = array();
                if (preg_match('/^(?<class>[^.].+)\.php$/', $item, $matches))
                {
                    $classArr[] = $matches['class'];
                }
            }
            $dir->close();
        }

        foreach ($classArr as $name)
        {
            $class = "App\\$namespace\\$name";
            if (class_exists($class)) {
              // Initialize the class
              $class::init();
            }
        }
    }

    /**
     * Fetches the $_GET from 'url'
     */
    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');

        $utf8 = urldecode($url);
        $this->_utf8 = explode('/', $utf8);

        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);
    }

    /**
     * (Optional) Set a custom path to controllers
     * @param string $path
     */
    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/');
    }

    /**
     * This loads if there is no GET parameter passed
     */
    private function _loadDefaultController()
    {
        $class = $this->_controllerPath.'\\'.$this->_defaultController.$this->_controllerSuffix;
        $this->_controller = new $class;
        $this->_controller->index();
    }

    /**
     * Load an existing controller if there IS a GET parameter passed
     *
     * @return boolean|string
     */
    private function _loadExistingController()
    {
        $class = $this->_controllerPath.'\\'.ucfirst($this->_url[0]).$this->_controllerSuffix;
        if (class_exists($class))
        {
            $this->_controller = new $class;
        } else
        {
            $this->_error();

            return false;
        }
    }

    /**
     * Display an error page if nothing exists
     *
     * @return boolean
     */
    private function _error()
    {
        $class = $this->_controllerPath.'\\'.$this->_errorController.$this->_controllerSuffix;
        $this->_controller = new $class;
        $this->_controller->index();
        exit;
    }

    /**
     * If a method is passed in the GET url paremter
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function _callControllerMethod()
    {
        $length = count($this->_url);
        $isPage = isset($this->_controller->_type) && $this->_controller->_type == 'page';

        // Determine what to load
        if ($length == 1) {
            //Controller->index()
            $method = $this->_defaultMethod;
            $params = [];
        } else if ($length == 2 && $isPage) {
            //Controller->index(Page Name)
            $method = $this->_defaultMethod;
            $params = [$this->_utf8[1]];
        } else {
            //Controller->Method(Param1, Param2, Param3, Param4)
            $method = $this->_url[1];
            $params = array_slice($isPage ? $this->_utf8 : $this->_url,2);
        }
        if (!method_exists($this->_controller, $method))
        {
            $this->_error();
        }
        call_user_func_array([$this->_controller,$method], $params);
    }

}
