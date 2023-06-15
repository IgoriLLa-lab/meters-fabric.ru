<?php

namespace core\user\controller;

use core\user\helpers\CatalogHelper;
use core\user\model\Model;
use core\base\controller\BaseController;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\user\projectHelpers\ProjectHelper;
use libraries\TextModify;

class BaseUser extends BaseController
{

    use CatalogHelper;

    use ProjectHelper;

    protected $model;

    protected $userModel;

    protected $projectTables;

    protected $set;
    protected $menu;
    protected $table;

    protected $title;
    protected $keywords;
    protected $description;
    protected $h1;
    protected $metaData;

    protected $breadcrumbs;

    protected $data;

    /*Проектные свойства*/

    /**
     * @var ссылки в футере и подвале сайта
     */

    protected $modals;

    /**
     * @var array hit, sale, week_sale, new
     */

    /*Проектные свойства*/

    protected function inputData()
    {

        $this->init();

        $this->model = Model::instance();

        $this->checkAuth();

        $this->projectTables = Settings::get('projectTables');

        $this->set = $this->model->get('settings', [
            'order' => 'id',
            'limit' => 1,
            'single' => true
        ]);

        if (!$this->isAjax()) {

            $this->getCartData();

        }

        define('QTY', ($this->set['catalog_qty'] ?? _QTY)); //кол-во элеметов на странице

        //$this->modals = $this->render(TEMPLATE . 'include/modals');

        if (method_exists($this, 'beforeInput')) {

            $this->beforeInput();

        }

    }

    protected function outputData()
    {

        $args = func_get_arg(0);
        $vars = $args ?: [];

        if (method_exists($this, 'beforeOutput')) {

            $this->beforeOutput($vars);

        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . TEMPLATE . 'include/modals.php')) {

            $this->modals = $this->render(TEMPLATE . 'include/modals', $vars);

        }

        if (!$this->content) {

            if (!$this->breadcrumbs) {

                empty($vars['ext_crumbs']) &&
                method_exists($this, 'createBreadcrumbsSeo') &&
                $crumbs = $this->createBreadcrumbsSeo();

                empty($crumbs) && $this->createBreadcrumbs($vars['data'] ?? [], $vars['ext_crumbs'] ?? []);

                !empty($crumbs) && $vars['ext_crumbs'] = $crumbs;

            }

            !$this->h1 && $this->h1 = $vars['data']['h1'] ?? ($vars['data']['name'] ?? (!empty($crumbs) ? $crumbs[count($crumbs) - 1]['name'] : ''));
            !$this->title && $this->title = $vars['data']['title'] ?? ($this->metaData['title'] ?? ($vars['data']['name'] ?? ''));

            if (!$this->title) $this->title = $this->metaData['title'] ?? ($this->h1 ?? ($this->metaData['name'] ?? ''));

            !$this->keywords && $this->keywords = $vars['data']['keywords'] ?? ($this->metaData['keywords'] ?? '');
            !$this->description && $this->description = $vars['data']['description'] ?? ($this->metaData['description'] ?? '');


            $this->content = $this->render($this->template, $vars);
        }

        if (!$this->title) $this->title = $this->set['title'] ?? ($this->set['name'] ?? '');
        if (!$this->keywords) $this->keywords = $this->set['keywords'] ?? '';
        if (!$this->description) $this->description = $this->set['description'] ?? '';
        if (!$this->h1) $this->h1 = $this->set['h1'] ?? ($this->set['name'] ?? '');

        $this->header = $this->render(TEMPLATE . 'include/header', $vars);

        $this->footer = $this->render(TEMPLATE . 'include/footer', $vars);

        if (method_exists($this, 'afterOutput')) {

            $this->afterOutput($vars);

        }

        return $this->render(TEMPLATE . 'layout/default');

    }

    public function __get($property)
    {

        $baseProperty = $property;

        $limit = null;

        if (preg_match('/\d+$/', $property, $matches)) {

            $property = preg_replace('/\d+$/', '', $property);

            $limit = $matches[0];

        }

        $property = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $property));

        $tables = $this->model->showTables();

        if (!in_array($property, $tables)) {

            $propertyArr = preg_split('/_/', $property);

            if (count($propertyArr) === 1) return null;

            $property = '';

            $part = '';

            foreach ($propertyArr as $key => $item) {

                $part .= !$part ? $item : '_' . $item;

                unset($propertyArr[$key]);

                if (in_array($part, $tables)) {

                    $property = $part;

                    break;

                }

            }

        }

        if ($property) {

            $columns = $this->model->showColumns($property);

            $order = null;

            $orderDirection = null;

            $where = null;

            if (!empty($columns['menu_position'])) {

                $order = 'menu_position';

            } elseif (!empty($columns['date'])) {

                $order = 'date';

                $orderDirection = 'DESC';

            }

            if (!empty($columns['visible'])) {

                $where['visible'] = 1;

            }

            $this->$baseProperty = $this->model->get($property, [
                'where' => $where,
                'order' => $order,
                'order_direction' => $orderDirection,
                'limit' => $limit
            ]) ?: [];

            if (!empty($columns['external_alias']) && !empty($this->$baseProperty)) {

                foreach ($this->$baseProperty as $key => $item) {

                    if (!empty($item['external_alias'])) {

                        if (!preg_match('/^\s*http/i', $item['external_alias'])) {

                            if (preg_match('/^\s*[^\/]+\./i', $item['external_alias'])) {

                                $this->$baseProperty[$key]['external_alias'] = 'http://' . $item['external_alias'];

                            } elseif (!preg_match('/^\s*\//i', $item['external_alias'])) {

                                $this->$baseProperty[$key]['external_alias'] = '/' . $item['external_alias'];

                            }

                        }

                    }

                }

            }

            if (!empty($propertyArr)) {

                $row = '';

                foreach ($propertyArr as $item) {

                    $row .= $row ? '_' . $item : $item;

                }

                if (!empty($columns[$row])) {

                    foreach ($this->$baseProperty as $key => $item) {

                        if (!empty($item[$row])) {

                            unset($this->$baseProperty[$key]);

                            $this->$baseProperty[$item[$row]] = $item;

                        }

                    }

                }

            }

            return $this->$baseProperty;

        }

        return null;

    }

    protected function translateEl($alias, $elName = 'el_name')
    {

        $data = $this->translateElementsAlias;

        $translateData = false;

        if (preg_match('/[а-яё]/ui', $alias)) {

            $translateData = $alias;

            $alias = (new TextModify())->translit($alias);

        } else {

            $alias = strtolower($alias);

        }

        if (!empty($data[$alias])) {

            return $data[$alias][$elName] ?: $data[$alias]['name'];

        }

        if ($translateData && in_array('translate_elements', $this->model->showTables())) {

            if ($this->model->add('translate_elements', [
                'fields' => ['name' => $translateData, 'alias' => $alias]
            ])) {

                return $this->translateElementsAlias[$alias]['name'] = $translateData;

            }

        }

        return null;

    }

    protected function textCut($str, $length = false)
    {

        if ($str) {

            if (!$length || mb_strlen($str) > $length) {

                $res = (new TextModify())->textCutting($str, $length);

                if (!empty($res[0])) $str = $res[0] . '...';

            }

        }

        return $str;

    }

    protected function getMetaData($table_name = false)
    {

        $table = explode('controller', strtolower((new \ReflectionClass($this))->getShortName()))[0];

        $tables = $this->model->showTables();

        if (in_array('metadata', $tables)) {

            if (!$table_name) {

                if ($this->table) {

                    $table_name = $this->table;

                } else {

                    if (in_array($table, $tables)) {

                        $table_name = $table;

                    }

                }

            }

            $this->metaData = $this->model->get('metadata');

            if ($this->metaData) {

                foreach ($this->metaData as $key => $item) {

                    unset($this->metaData[$key]);

                    $this->metaData[$item['table_name']] = $item;

                }

            }

        }

        return $this->metaData;

    }

    protected function createBreadcrumbs($data = false, $ext_crumbs = [])
    {

        if ($this->table || $ext_crumbs) {

            $crumbs[0]['name'] = !empty($_COOKIE['language']) ? 'Main page' : 'Главная';

            $crumbs[0]['alias'] = PATH;

            if (!$ext_crumbs) {

                $crumbs[1]['name'] = Settings::get('projectTables')[$this->table]['name'] ?? '';
                $crumbs[1]['alias'] = PATH . $this->table;

                if ($this->parameters['alias']) {
                    $data = $data ?: $this->data;

                    if (!empty($data['name'])) {

                        $crumbs[2]['name'] = $data['name'];
                        $crumbs[2]['alias'] = PATH . $this->table . '/' . $this->parameters['alias'];

                    }
                }
            } else {

                ksort($ext_crumbs);

                for ($i = 0; $i < count($ext_crumbs); $i++) {
                    if ($ext_crumbs[$i]['name'])
                        $crumbs[$i + 1] = $ext_crumbs[$i];
                }
            }

            $this->breadcrumbs = $this->render(TEMPLATE . 'include/breadcrumbs', ['crumbs' => $crumbs]);
        }
    }

    protected function getMeta()
    {

        if ($this->title) {

            if (preg_match('/##/', $this->title)) {

                if ($this->h1)
                    $this->title = str_replace('##', $this->h1, $this->title);
                else
                    $this->title = preg_replace('/\s*##\s*/', '', $this->title);
            }

            echo '<title>' . $this->title . '</title>' . "\r\n";

        }

        if ($this->description) {

            if (preg_match('/##/', $this->description)) {

                if ($this->h1)
                    $this->description = str_replace('##', $this->h1, $this->description);
                else
                    $this->description = preg_replace('/\s*##\s*/', '', $this->description);


            }

            echo '<meta name="description" content="' . $this->description . '">' . "\r\n";
        }

        if ($this->keywords) {

            if (preg_match('/##/', $this->keywords)) {

                if ($this->h1)
                    $this->keywords = str_replace('##', $this->h1, $this->keywords);
                else
                    $this->keywords = preg_replace('/\s*##\s*/', '', $this->keywords);


                $this->keywords = preg_replace('/\s*,\s*,\s*/', ', ', $this->keywords);

            }

            echo '<meta name="keywords" content="' . $this->keywords . '">' . "\r\n";

        }

    }

    protected function img($img = '', $tag = false, $set = [])
    {

        if (!$img && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . 'default_images')) {

            $dir = scandir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . 'default_images');

            $img = preg_grep('/' . $this->getController() . '\./i', $dir) ?: preg_grep('/default\./i', $dir);

            $img && $img = array_shift($img);

        }

        if ($img) {

            $path = PATH . UPLOAD_DIR . $img;

            $class = isset($set['class']) && $set['class'] ?
                ' class="' . (is_array($set['class']) ? implode(' ', $set['class']) : $set['class']) . '" ' : '';

            $alt = isset($set['alt']) && $set['alt'] ? ' alt="' . $set['alt'] . '" ' : '';

            $title = isset($set['title']) && $set['title'] ? ' title="' . $set['title'] . '" ' : '';

            $style = isset($set['style']) && $set['style'] ?
                ' style="' . (is_array($set['style']) ? implode(';', $set['style']) : $set['style']) . '" ' : '';

            $data = '';

            if (isset($set['data']) && $set['data']) {

                if (is_array($set['data'])) {

                    foreach ($set['data'] as $key => $item) {

                        if (stripos($key, 'data-') === false)
                            $data .= 'data-';

                        $data .= $key . '="' . $item . '"';

                    }

                } else {

                    if (!preg_match('/^\s*data[^=]+=/i', $set['data']))
                        $data = 'data-attribute="' . $set['data'] . '"';
                    else $data = $set['data'];

                }

            }

            if (!$tag)
                return $path;

            echo '<img src="' . $path . '"' . $alt . $title . $class . $style . ' ' . $data . ' >';

        }

        return '';

    }

    protected function alias($alias = '', $queryString = '')
    {

        $str = '';

        if ($queryString) {

            if (is_array($queryString)) {

                foreach ($queryString as $key => $item) {

                    if (is_array($item)) {

                        $key .= '[]';

                        foreach ($item as $v) $str .= (!$str ? '?' : '&') . $key . '=' . $v;

                    } else {

                        $str .= (!$str ? '?' : '&') . $key . '=' . $item;

                    }


                }

            } else {

                if (strpos($queryString, '?') === false) $str .= '?' . $queryString;
                else $str .= $queryString;

            }

        }

        if (is_array($alias)) {

            $aliasStr = '';

            foreach ($alias as $key => $item) {

                if (!is_numeric($key) && $item) {

                    $aliasStr .= $key . '/' . $item . '/';

                } elseif (is_numeric($key) && $item) {

                    $aliasStr .= $item . '/';

                }

            }

            $alias = trim($aliasStr, '/');

        }

        if (!$alias || $alias === '/') return PATH . $str;

        if (preg_match('/^https?:\/\//', $alias))
            return $alias . $str;

        return preg_replace('/\/{2,}/', '/', PATH . $alias . END_SLASH . $str);

    }

    protected function getVideo($video)
    {

        if (!$video) return '';

        if (preg_match('/<iframe/', $video)) {

            $content = $video;

        } else {

            if (strpos($video, 'embed') === false) {

                $arr = explode('/', $video);

                $video = 'https://youtube.com/embed/' . $arr[count($arr) - 1];

            }

            $content = '<iframe src="' . $video . '"
                                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>';

        }

        return $content;

    }

    protected function checkAlias()
    {

        if (!isset($this->parameters['alias']) || !$this->parameters['alias'])
            throw new RouteException('Отсутствует алиас страницы при запросе контроллера ' . __CLASS__);

    }

    protected function setError($table = false, $alias = false)
    {

        !$table && $table = $this->table ?: $this->getController();

        !$alias && $alias = !empty($this->parameters['alias']) ? $this->parameters['alias'] : false;

        $message = 'Не найдены записи в ' . $table;

        if ($alias) {

            $message .= ' по ссылке ' . $this->parameters['alias'];

        }

        throw new RouteException($message);

    }

    protected function wordsForCounter($counter, $arrElement = 'products')
    {

        $arr = [
            'products' => [
                'Товаров',
                'Товар',
                'Товара'
            ],
            'duration' => [
                'дней',
                'день',
                'дня'
            ],
        ];

        if (is_array($arrElement)) {

            $arr = $arrElement;

        } else {

            $arr = $arr[$arrElement] ?? array_shift($arr);

        }

        $char = (int)substr($counter, -1);

        $counter = (int)substr($counter, -2);

        if (($counter >= 10 && $counter <= 20) || ($char >= 5 && $char <= 9) || $char === 0) return $arr[0];
        elseif ($char === 1) return $arr[1];
        else return $arr[2];

    }

    protected function createColors($data, $degrees = 90)
    {

        if ($data) {

            $colorsArr = preg_split('/\s*;\s*/', $data, 0, PREG_SPLIT_NO_EMPTY);

            $countColors = count($colorsArr);

            if ($countColors === 1) {

                return $this->setColorType($colorsArr[0]);

            }

            $resultColor = "linear-gradient({$degrees}deg";

            $percent = 100 / ($countColors - 1);

            foreach ($colorsArr as $key => $item) {

                $key *= $percent;

                if ($key > 100) {

                    $key = 100;

                }

                $resultColor .= ', ' . $this->setColorType($item) . ' ' . $key . '%';

            }

            $data = $resultColor . ');';

        }

        return $data;

    }

    protected function setColorType($color)
    {

        $colorNew = preg_replace('/\s+/', '', $color);

        if (preg_match('/^(#)|(rgb)/i', $colorNew)) {

            return $color;

        }

        $colorNew = preg_replace('/[\(\)]/', '', $colorNew);

        $colorArr = preg_split('/,/', $colorNew, 0, PREG_SPLIT_NO_EMPTY);

        $count = count($colorArr);

        switch ($count) {

            case 1:

                $colorNew = '#' . $color;

                break;

            case 3:

                $colorNew = 'rgb(' . (implode(',', $colorArr)) . ')';

                break;

            case 4:

                $colorNew = 'rgba(' . (implode(',', $colorArr)) . ')';

                break;

            default:

                $colorNew = $color;

        }

        return $colorNew;

    }

    protected function setFormValues($key, $property = null, $arr = []){

        if(!$arr){

            $arr = $_SESSION['res'] ?? [];

        }

        if(!empty($arr[$key])){

            return $arr[$key];

        }elseif ($property && !empty($this->$property[$key])){

            return $this->$property[$key];

        }

        return '';

    }

}