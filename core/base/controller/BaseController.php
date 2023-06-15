<?php

namespace core\base\controller;

use core\base\exceptions\LowLevelException;
use core\base\exceptions\RouteException;
use core\base\model\UserModel;
use core\base\settings\Settings;


abstract class BaseController
{

    use \core\base\controller\BaseMethods;

    protected $header;
    protected $content;
    protected $footer;
    protected $page;

    protected $errors;

    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    protected $template;
    protected $styles;
    protected $scripts;

    protected $userData;

    protected $data;
    protected $ajaxData;

    public function route(){

        $controller = str_replace('/', '\\', $this->controller);

        try{

            $object = new \ReflectionMethod($controller, 'request');

            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod
            ];

            $object->invoke(new $controller, $args);
        }
        catch (\ReflectionException $e){

            throw new RouteException($e->getMessage());

        }

    }

    public function request($args){

        $this->parameters = $args['parameters'];

        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        try{

            $data = $this->$inputData();

        }catch (LowLevelException $e){

        }

        if(method_exists($this, $outputData)){

            $page = $this->$outputData($data);
            if($page) $this->page = $page;

        }elseif($data){
            $this->page = $data;
        }


        if($this->errors){
            $this->writeLog($this->errors);
        }

        $this->getPage();
    }

    protected function render($path = '', $parameters = []){

       if(is_array($parameters)) extract($parameters);

       if(!$path){

           $class = new \ReflectionClass($this);

           $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
           $routes = Settings::get('routes');

           if($space === $routes['user']['path']) $template = TEMPLATE;
                else $template = ADMIN_TEMPLATE;

           $path = $template . $this->getController();
       }

       ob_start();

       if(!include $path . '.php') throw new RouteException('Отсутствует шаблон - '.$path);

       return ob_get_clean();

    }

    protected function getPage(){

        if(defined('DEVELOPMENT_MODE') && !empty(DEVELOPMENT_MODE)){

            header("HTTP/1.1 404 Not Found", true, 404);
            header ('Status: 404 Not Found');

        }

        if(is_array($this->page)){
            foreach ($this->page as $block) echo $block;
        }else{
            echo $this->page;
        }
        exit;

    }


    /**
     * Инициализация стилей и скриптов админа и юзера (12 0:01)
     *
     * @param bool $admin
     * @return void
     */
    protected function init(bool $admin = false) : void
    {

        if(defined('BASE_CSS_JS')){

            if(!empty(BASE_CSS_JS['styles'])){

                foreach(BASE_CSS_JS['styles'] as $item)
                    $this->styles[] = (!preg_match('/^\s*https?:\/\//i', $item) ? PATH : '') . trim($item, '/');

            }

            if(!empty(BASE_CSS_JS['scripts'])){

                foreach(BASE_CSS_JS['scripts'] as $item)
                    $this->scripts[] = (!preg_match('/^\s*https?:\/\//i', $item) ? PATH : '') . trim($item, '/');

            }

        }

        if(!$admin){
            if(USER_CSS_JS['styles']){
                foreach(USER_CSS_JS['styles'] as $item)
                    $this->styles[] = (!preg_match('/^\s*https?:\/\//i', $item) ? PATH . TEMPLATE : '') . trim($item, '/');
            }

            if(USER_CSS_JS['scripts']){
                foreach(USER_CSS_JS['scripts'] as $item)
                    $this->scripts[] = (!preg_match('/^\s*https?:\/\//i', $item) ? PATH . TEMPLATE : '') . trim($item, '/');
            }
        }else{
            if(ADMIN_CSS_JS['styles']){
                foreach(ADMIN_CSS_JS['styles'] as $item)
                    $this->styles[] = (!preg_match('/^\s*https?:\/\//i', $item) ? PATH . ADMIN_TEMPLATE : '') . trim($item, '/');
            }

            if(ADMIN_CSS_JS['scripts']){
                foreach(ADMIN_CSS_JS['scripts'] as $item)
                    $this->scripts[] = (!preg_match('/^\s*https?:\/\//i', $item) ? PATH . ADMIN_TEMPLATE : '') . trim($item, '/');
            }
        }

    }

    protected function checkAuth($type = false){

        if(!($this->userData = UserModel::instance()->checkUser(false, $type))){

            $type && $this->redirect(PATH);

        }

        if(property_exists($this, 'userModel'))
            $this->userModel = UserModel::instance();

        if(property_exists($this, 'model') && $this->model)
            $this->model->userData = $this->userData;

    }

}