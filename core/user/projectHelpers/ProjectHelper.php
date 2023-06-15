<?php

namespace core\user\projectHelpers;

trait ProjectHelper
{

    protected $rootAlias;

    protected function beforeInput()
    {

        if ($this->styles && file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . TEMPLATE . 'assets/css/' . $this->getController() . '.css')) {

            if (!preg_grep('/' . preg_quote($this->getController() . '.css') . '/i', $this->styles)) {

                $this->styles[] = PATH . TEMPLATE . 'assets/css/' . $this->getController() . '.css';

            }

            if($this->getController() === 'lk'){

                $this->styles[] = PATH . TEMPLATE . 'assets/css/catalog.css';

            }

        }

    }

    protected function beforeOutput(&$vars)
    {

        $menu = $this->model->get('catalog', [
            'where' => ['visible' => 1],
            'order' => ['parent_id', 'menu_position']
        ]);

        if (($this->getController() === 'catalog' || $this->getController() === 'goods') && !empty($vars['data'])) {

            if (!isset($vars['data']['parent_id'])) {

                $el = $vars['data'];

            } else {

                $el = $this->recursiveSearch($this->recursiveArr($menu), $vars['data']['parent_id'], function (&$el, &$set) {

                    if (!isset($el['parent_id'])) {

                        return true;

                    }

                    $set['search'] = $el['parent_id'];

                    $el = false;

                });

                $recommendationId = $this->getController() === 'catalog' ? $vars['data']['id'] : $vars['data']['parent_id'];

                $vars['recommendations'] = $this->getRecommendations($this->model->get('catalog_goods', [
                    'fields' => ['goods_id'],
                    'where' => ['catalog_id' => $recommendationId],
                    'return_query' => true
                ]));

                if (empty($vars['recomendations'])) {

                    $parentIds = $this->getParents($recommendationId, 'catalog');

                    if ($parentIds && count($parentIds) > 1) {

                        $vars['recommendations'] = $this->getRecommendations($parentIds);

                    }

                }

            }

            $_SESSION['ROOT_CATALOG'] = $el['id'] ?? 0;

        }

        if (!empty($_SESSION['ROOT_CATALOG']) && $this->getController() !== 'index') {

            $menuItems = $this->recursiveArr($menu, 1, $_SESSION['ROOT_CATALOG']);

            $menuItems = array_merge($menuItems, ...array_column($menuItems, 'sub'));

            $menuItems = array_filter($menuItems, function ($item) {

                return !array_key_exists('old_parent_id', $item);

            });

            $ids = array_column($menuItems, 'id');

            $ids[] = $_SESSION['ROOT_CATALOG'];

            $this->menu['new'] = $this->model->get('goods', [
                'fields' => ['id'],
                'where' => ['visible' => 1, 'parent_id' => $ids, 'new' => 1],
                'limit' => 1
            ]);

            $designersName = 'дизайнеры';

            $this->menu['designers'] = $this->model->get('filters', [
                'where' => ['visible' => 1, '!parent_id' => null],
                'join' => [
                    'filters f' => [
                        'type' => 'inner',
                        'fields' => null,
                        'where' => ['name{lower}' => $designersName, 'visible' => 1, 'parent_id' => null],
                        'on' => ['parent_id' => 'id']
                    ],
                    'filters_goods' => [
                        'fields' => null,
                        'on' => [
                            'table' => 'filters',
                            'fields' => ['id' => 'filters_id']
                        ],
                    ],
                    'goods' => [
                        'fields' => null,
                        'on' => ['goods_id' => 'id'],
                        'where' => ['parent_id' => $ids, 'visible' => 1]
                    ],
                    'catalog' => [
                        'on' => ['parent_id' => 'id']
                    ]
                ],
                'join_structure' => true
            ]);

            $this->menu['root'] = $this->recursiveArr($menu, 2);

            if (!empty($this->menu['root'][$_SESSION['ROOT_CATALOG']]['sub']) && !empty($this->menu['designers'])) {

                foreach ($this->menu['root'][$_SESSION['ROOT_CATALOG']]['sub'] as $key => &$item) {

                    $item['designers'] = [];

                    foreach ($this->menu['designers'] as $k => $designer) {

                        if (!empty($item['designers'][$k])) {

                            continue;

                        }

                        if (!empty($designer['join']['catalog'][$key])) {

                            $item['designers'][$k] = $designer;

                        } elseif (!empty($item['sub'])) {

                            if (array_intersect(array_keys($designer['join']['catalog']), array_keys($item['sub']))) {

                                $item['designers'][$k] = $designer;

                            }

                        }

                    }

                }

                unset($item);

                $this->menu['root'][$_SESSION['ROOT_CATALOG']]['current'] = true;

                $this->menu['sub'] = $this->menu['root'][$_SESSION['ROOT_CATALOG']]['sub'] ?? [];

                $this->rootAlias = $this->menu['root'][$_SESSION['ROOT_CATALOG']]['alias'];

                $this->menu['new'] && $this->menu['new'] = $this->alias(['catalog' => $this->rootAlias, 'label' => 'new']);

            }

        }

        if (!isset($this->menu['root'])) {

            $this->menu['root'] = $this->recursiveArr($menu);

        }

        if (!isset($this->menu['sub'])) {

            $this->menu['sub'] = $this->menu['root'];

        }

    }

    protected function getRecommendations($ids)
    {

        if ($ids) {

            return $this->model->getGoods([
                'where' => [
                    'id' => $ids,
                    'visible' => 1
                ],
                'join' => [
                    'catalog' => [
                        'fields' => 'name',
                        'on' => ['parent_id' => 'id'],
                        'single' => true
                    ]
                ]
            ], ...[false, false]);

        }

        return false;

    }

    protected function getSales($data)
    {

        $sales = [];

        if ($data) {

            $res = $this->model->get('sales', [
                'where' => [
                    'visible' => 1,
                    '!parent_id' => null,
                    'parent_id' => $data['id']
                ],
                'join' => [
                    'sales_goods' => [
                        'on' => ['id' => 'sales_id']
                    ],
                    'goods' => [
                        'on' => ['goods_id' => 'id']
                    ]
                ],
                'order' => 'menu_position',
                'join_structure' => true
            ]);

            if ($res) {

                $counter = -1;

                foreach ($res as $item) {

                    $template = '';

                    if (!empty($item['join']['goods'])) {

                        $item['join']['goods'] = array_filter($item['join']['goods'], function ($el) {

                            return !empty($el['visible']);

                        });

                    }

                    if (!empty($item['join']['goods'])) {

                        if (empty($item['first_external_alias'])) {

                            $item['first_external_alias'] = $item['second_external_alias'];

                        }

                        $item['join']['goods'] = $this->model->getGoods([
                            'where' => ['id' => array_keys($item['join']['goods']), 'visible' => 1],
                            'join' => [
                                'catalog' => [
                                    'fields' => 'name',
                                    'on' => ['parent_id' => 'id'],
                                    'single' => true
                                ]
                            ]
                        ], ...[false, false]);

                        $template = 'goods_section';

                    } else {

                        switch (true) {

                            case (!++$counter && !empty($item['first_img']) && !empty($item['second_img'])):

                                $template = 'first_section';

                                break;

                            case (!empty($item['first_img']) && !empty($item['second_img'])):

                                $template = 'default_section';

                                break;

                            case (!empty($item['first_img']) || !empty($item['second_img'])):

                                $template = 'single_section';

                                $arrEmptyColumns = ['external_alias', 'img', 'title', 'short_content'];

                                foreach ($arrEmptyColumns as $column) {

                                    if (empty($item['first_' . $column])) {

                                        $item['first_' . $column] = $item['second_' . $column];

                                    }

                                }

                                break;

                        }

                    }

                    if ($template) {

                        $sales[] = $this->render(TEMPLATE . 'include/sales_blocks/' . $template, ['data' => $item]);

                    }

                }

            }

        }

        return $sales;

    }

    protected function setProjectCartData($data, &$moreData)
    {

        if (!empty($_SESSION['viewed'])){

            $moreData['viewed'] = $this->model->getGoods([
                'where' => ['visible' => 1, 'id' => array_keys($_SESSION['viewed'])],
                'join' => [
                    'catalog' => [
                        'field' => ['name'],
                        'on' => ['parent_id' => 'id'],
                        'single' => true
                    ]
                ]
            ]);

        }

        if (!empty($_SESSION['delayed'])){

            $moreData['delayed'] = $this->model->getGoods([
                'where' => ['visible' => 1, 'id' => array_keys($_SESSION['delayed'])],
                'join' => [
                    'catalog' => [
                        'field' => ['name'],
                        'on' => ['parent_id' => 'id'],
                        'single' => true
                    ]
                ]
            ]);

        }

        if ($data){

            $catalog = $this->model->get('catalog', [
                'where' => ['visible' => 1, 'id' => array_column($data, 'parent_id')],
                'join_structure' => true
            ]);

            if ($catalog){

                foreach ($data as $key => $item){

                    $data[$key]['parent_name'] = '';

                    if (!empty($catalog[$item['parent_id']])){

                        $data[$key]['parent_name'] = $catalog[$item['parent_id']]['name'];

                    }

                }

            }

        }

        return $data;

    }

    protected function checkLkAlias($alias = ''){

        $active = 'internal-aside-item_active';

        if((empty($this->parameters['alias']) && !$alias) || (!empty($this->parameters['alias']) && $this->parameters['alias'] === $alias)){

            return $active;

        }

        return '';

    }

}