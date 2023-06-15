<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 08.09.2019
 * Time: 15:38
 */

namespace core\user\controller;


class AjaxController extends BaseUser
{

    public function ajax(){

        parent::inputData();

        if($this->ajaxData){

            if(!empty($this->ajaxData['data']) && !is_array($this->ajaxData['data'])){

                $data = json_decode($this->ajaxData['data'], true);

                if($data && is_array($data)){

                    foreach ($data as $key => $item) $this->ajaxData[$key] = $item;

                }

            }

            if(!$this->columns && !empty($this->ajaxData['table'])){
                $this->columns = $this->model->showColumns($this->ajaxData['table']);
            }

        }

        switch($this->ajaxData['ajax']){

            case 'site_search':

                return $this->userSearch();

                break;

            case 'wishList':
            case 'delayed':

                return $this->_addToWishList();

                break;

            case 'add_to_cart':

                return $this->_addToCart();

                break;

        }
    }

    protected function userSearch(){

        $catalog = new CatalogController();

        $alias = '';

        if(!empty($this->ajaxData['action'])){

            $aliasArr = preg_split('/\//', $this->ajaxData['action'], 0, PREG_SPLIT_NO_EMPTY);

            if(count($aliasArr) > 1){

                $alias = array_pop($aliasArr);

            }

        }

        $res = $catalog->getAjaxCatalog($alias);

        if(!empty($res['goods'])){

            foreach ($res['goods'] as $key => $item){

                $res['goods'][$key]['alias'] = $this->alias(['product' => $item['alias']]);

            }

            return $res['goods'];

        }

        return [];

    }

    protected function _addToWishList(){

        return $this->addToWishList($this->ajaxData['id'] ?? null, $this->ajaxData['ajax'] ?? null);

    }

    protected function _addToCart(){

        return $this->addToCart();

    }


}