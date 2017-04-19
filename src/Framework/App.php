<?php
namespace SAmvc\Framework;

class App {

    private $_url = null;
    private $_utf8 = null;
    private $_controller = null;

    private $_controllerPath = 'App\Controllers';
    private $_errorController = 'Error';
    private $_defaultController = 'Index';
    private $_controllerSuffix = 'Controller';

    /**
     * Starts the App
     *
     * @return boolean
     */
    public function init()
    {
        // Sets the protected $_url
        $this->_getUrl();
        // sub folder controller
        $folder = str_replace('\\','/',$this->_controllerPath.'/'.$this->_url[0]);
        $folder = str_replace('App/','',$folder);
        while( isset($this->_url[0]) && is_dir($folder) && $this->_url[0]){
          $this->setControllerPath($this->_controllerPath.'\\'.$this->_url[0]);
          array_shift($this->_url);
        }
        // Load the default controller if no Env::get('url') is set
        if (empty($this->_url[0])) {
          $this->_loadDefaultController();
          return false;
        }

        // small fix for wallart api
        // original case: <user_id>/projects/<id>/email_message
        // new case: users/<user_id>/projects/id/email_message
        if( $this->_url[0] == 'wallart' && isset($this->_url[1]) && is_numeric($this->_url[1]) ){
          array_splice($this->_url, 1, 0, 'users');
        }

        $this->_loadExistingController();
        $this->_callControllerMethod();

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
      if (class_exists($class)) {
        $this->_controller = new $class;
      }else{
        $this->_error();
        return false;
      }
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

        // Make sure the method we are calling exists
        if ($length > 1) {
            if (isset($this->_controller->_type) && $this->_controller->_type == 'page'){
                $this->_url[1] = $this->_utf8[1];
                if(isset($this->_url[2]))
                  $this->_url[2] = $this->_utf8[2];
            }else if (!method_exists($this->_controller, $this->_url[1])) {
                $this->_error();
            }
        }

        // Determine what to load
        switch ($length) {
            case 6:
              //Controller->Method(Param1, Param2, Param3, Param4)
              $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4], $this->_url[5]);
              break;

            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4]);
                break;

            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3]);
                break;

            case 3:
                //Controller->Method(Param1)
                $this->_controller->{$this->_url[1]}($this->_url[2]);
                break;

            case 2:
                if (isset($this->_controller->_type) && $this->_controller->_type == 'page'){
                  //Controller->index(Page Name)
                  $this->_controller->index($this->_url[1]);
                }else{
                  //Controller->Method()
                  $this->_controller->{$this->_url[1]}();
                }
                break;

            default:
                $this->_controller->index();
                break;
        }
    }

    /**
     * Display an error page if nothing exists
     *
     * @return boolean
     */
    private function _error() {
      $class = $this->_controllerPath.'\\'.$this->_errorController.$this->_controllerSuffix;
      $this->_controller = new $class;
      $this->_controller->index();
      exit;
    }

}
