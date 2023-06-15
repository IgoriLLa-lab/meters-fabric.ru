<?php


namespace core\user\controller;

class IndexController extends BaseUser
{

    protected function inputData(){

        parent::inputData();

        $data = $this->model->get('sales', [
            'where' => ['visible' => 1, 'catalog_id' => null],
            'order' => 'menu_position',
            'limit' => 1,
            'single' => true
        ]);

        if(!empty($data)){

            $data['sale_description'] = $data['short_content'];

        }

        $sales = $this->getSales($data);

        return compact('sales', 'data');

    }

}