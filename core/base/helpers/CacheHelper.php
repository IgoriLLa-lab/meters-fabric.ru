<?php

namespace core\base\helpers;

use core\base\exceptions\CacheException;
use core\base\exceptions\LowLevelException;
use core\base\settings\Settings;

trait CacheHelper
{

    protected $dynamicCache = true;

    protected $usingTables = [];

    protected $cacheInit = false;

    protected $changeDirectory = [];

    protected $excludeTables = ['settings'];

    protected $excludeControllers = ['cart', 'lk', 'ajax', 'send_mail'];

    protected $excludeProperties = ['content', 'header', 'footer', 'styles', 'scripts'];

    protected $cacheTimeSettings = 'set';

    protected $cacheTimeFields = ['cache_time'];

    protected $cacheDefaultTime = 0;

    protected $cacheControlTable = 'cached_tables';

    protected $cacheControlTableName = 'name';

    protected $cacheHelperModel;

    protected $debugMode = false;

    protected function cacheInit($model = null, $properties = []){

        $model = $this->cacheHelperModel = $model;

        foreach ($properties as $name => $item){

            $this->$name = $item;

        }

        if(!$this->cacheHelperModel){

            $this->cacheHelperModel = $this->model;

        }

        $this->cacheInit = true;

    }

    protected function checkCache($returnException = true){

        if(!$this->isHttpRequest() || $this->debugMode || !$this->dynamicCache ||
            (!empty($this->excludeControllers) && in_array($this->getController(), $this->excludeControllers))){

            return $this->dynamicCache = false;

        }

        !$this->cacheInit && $this->cacheInit();

        if(!file_exists($this->getCacheFile()) || !$this->getCache() || !$this->usingTables){

            return $this->dynamicCache = false;

        }

        $cachedTables = [];

        if(in_array($this->cacheControlTable, $this->cacheHelperModel->showTables())){

            if(($cached = $this->cacheHelperModel->get($this->cacheControlTable, ['where' => [$this->cacheControlTableName => array_keys($this->usingTables)]]))){

                foreach ($cached as $item){

                    $item['date'] && $cachedTables[$item[$this->cacheControlTableName]] = new \DateTime($item['date']);

                }

            }

        }

        $dateTime = new \DateTime();

        $settings = $this->cacheTimeSettings;

        $setArr = &$this->$settings;

        if($this->cacheTimeFields){

            foreach ($this->cacheTimeFields as $item){

                if(!empty($setArr[$item])){

                    $this->cacheDefaultTime = $this->clearNum($setArr[$item]);

                }

            }

        }

        foreach ($this->usingTables as $table => $cacheDate){

            $date = new \DateTime($cacheDate);

            if(!in_array($table, $this->excludeTables) &&
                ((!empty($cachedTables[$table]) && $cachedTables[$table] > $date) ||
                $this->cacheDefaultTime && $dateTime > $date->modify("+$this->cacheDefaultTime second"))){

                unset($this->usingTables);

                return $this->dynamicCache = false;

            }

        }

        if($returnException)
            throw new LowLevelException();

        return true;

    }

    protected function createCache($args = [], $template = ''){

        if(!$this->isHttpRequest() || $this->debugMode || $this->dynamicCache || empty($this->cacheHelperModel)){

            return false;

        }

        if(!$template){

            !$this->usingTables && $this->usingTables = $this->cacheHelperModel->getRealTables();

            $object = $this;

            $object->___vars = $args;

            $template = serialize($object);

        }

        if(function_exists('gzdeflate')){

            $template = gzdeflate($template, 9);

        }

        if(!file_put_contents($this->getCacheFile(), $template)){

            return false;

        }

        return true;

    }

    protected function getCache(&$vars = []){

        static $objectVars = null;

        if($this->dynamicCache && !isset($objectVars)){

            $obj = @file_get_contents($this->getCacheFile());

            if(function_exists('gzinflate')){

                $obj = @gzinflate($obj);

            }

            $obj = unserialize($obj);

            if($obj && !is_string($obj)){

                foreach ($obj as $key => $item){

                    if($key !== '___vars' && !in_array($key, $this->excludeProperties) && (!isset($this->$key) || (!$this->$key && $item))){

                        $this->$key = $item;

                    }

                }

                $objectVars = !empty($obj->___vars) ? $obj->___vars : [];

            }

        }

        if(isset($objectVars)){

            !is_array($vars) && $vars = [];

            $vars = array_merge($objectVars, $vars);

            return true;

        }

        return false;

    }

    protected function getCachePath(){

        static $cachePath = null;

        $settingsPath = Settings::get('cachePath');

        $settingsPath && preg_replace('/\/+\s*$/', '', $settingsPath);

        return $cachePath ?: $cachePath = ($settingsPath ?: 'cache') . '/' . $_SERVER['SERVER_NAME'] . '/';

    }

    protected function getCacheFile(){

        static $cacheFile = null;

        if($cacheFile)
            return $cacheFile;

        $dir = $this->getController();

        if(!empty($this->changeDirectory[$dir]))
            $dir = $this->changeDirectory[$dir];

        $fullPath = $_SERVER['DOCUMENT_ROOT'] . PATH . $this->getCachePath() . $dir . $this->subDirectory();

        if(!is_dir($fullPath)){

            if(!mkdir($fullPath, 0777, true)){

                throw new CacheException();

            }

        }

        return $cacheFile = $fullPath . $this->fileName() . '.php';

    }

    protected function subDirectory(){

        static $subDirectory = null;

        if($subDirectory)
            return $subDirectory;

        $matches = null;

        preg_match('/^\/?([^\/?]+\/[^a-z1-9?]*([a-z1-9]+))|([^a-z1-9]*([a-z1-9]+))/i', $_SERVER['REQUEST_URI'], $matches);

        $subDirectory = '/';

        if($matches){

            if(!empty($matches[4])){

                $subDirectory .= $matches[4] . '/';

            }elseif (!empty($matches[2])){

                $subDirectory .= $matches[2] . '/';

            }

        }

        return $subDirectory;

    }

    protected function fileName(){

        static $fileName = null;

        if(!$fileName)
            $fileName = md5($_SERVER['REQUEST_URI']);

        return $fileName;

    }

    protected function clearCache($cachePath = ''){

        !$cachePath && $cachePath = $this->getCachePath();

        if(!preg_match('/\/\s*$/', $cachePath))
            $cachePath .= '/';

        $list = scandir($cachePath);

        if($list){

            foreach ($list as $file){

                if($file !== '.' && $file !== '..'){

                    if(is_dir($cachePath . $file)){

                        $this->clearCache($cachePath . $file);

                        @rmdir($cachePath . $file);

                    }else{

                        @unlink($cachePath . $file);

                    }

                }

            }

        }

    }

}