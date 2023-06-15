<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.03.2019
 * Time: 13:13
 */

namespace core\admin\expansion;


use core\base\controller\BaseMethods;
use core\base\controller\Singleton;
use core\base\settings\Settings;

class MetadataExpansion extends Expansion
{

    use Singleton;
    use BaseMethods;

    public function expansion($args = [], $obj = false){

        $no_add = true;
        $no_delete = true;

        parent::expansion($args, $obj);

        if($this->className === 'Show'){

            $project_tables = Settings::get('projectTables');
            $exceptionTables = Settings::get('menuException');

            $meta_data = [];
            $delete_meta = [];

            $res = $this->model->get('metadata', [
                'fields' => ['table_name']
            ]);

            if($res){
                foreach ($res as $item){
                    if(!$project_tables[$item['table_name']]){
                        $delete_meta[] = $item['table_name'];
                        continue;
                    }
                    $meta_data[] = $item['table_name'];
                }
            }

            if($project_tables){

                $new_meta = [];

                foreach($project_tables as $table_name => $value){

                    if(in_array($table_name, $exceptionTables)) continue;

                    $table_data = $this->model->showColumns($table_name);

                    if($table_data['description']){

                        if(!in_array($table_name, $meta_data)){
                            $name = $project_tables[$table_name]['name'] ?: $table_name;
                            $new_meta[] = ['name' => $name, 'table_name' => $table_name];
                        }

                    }

                }

                if($delete_meta){
                    $this->model->delete('metadata', [
                        'where' => ['table_name' => $delete_meta],
                    ]);
                }

                if($new_meta){

                    $this->model->add('metadata', [
                        'fields' => $new_meta
                    ]);

                    $this->redirect();

                }
            }
        }

        return compact('no_add', 'no_delete');

    }

}