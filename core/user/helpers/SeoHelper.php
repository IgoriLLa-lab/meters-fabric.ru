<?php

namespace core\user\helpers;

use core\base\settings\Settings;
use core\user\model\Model;

trait SeoHelper
{

    protected $chainParents = [];

    protected $rootChainElement = [];

    protected $setFullChainData = false;

    protected $skipChainBuilding;

    protected $columnName = 'name';

    protected $columnAlias = 'alias';

    protected function createChainElements($data){

        if(empty($data) || $this->skipChainBuilding){

            return [];

        }

        $chaining = [];

        !$this->model && $this->model = Model::instance();

        $currentTable = $this->getCurrentTable();

        if(!$currentTable){

            return false;

        }

        $currentColumns = $this->model->showColumns($currentTable);

        $currentData = !empty($data['data'][$currentColumns['id_row']]) ?
            $data['data'] : (!empty($data[$currentColumns['id_row']]) ? $data : null);

        if($currentData){

            $this->setChaining($currentData, $currentTable, $chaining);

            $this->setMeta($currentData);

            $fields = $this->getTableColumns($currentTable);

            $key = key($fields);

            $item = isset($key) ? $fields[$key] : null;

            $table = $currentTable;

            $previousTable = null;

            $currentElement = $currentData;

            $nextElement = null;

            while ($item){

                $foreignData = $this->model->showForeignKeys($table, $item);

                if(!$foreignData && $item === 'parent_id'){

                    $foreignData = [];

                    $foreignData[0]['REFERENCED_TABLE_NAME'] = $table;

                    $foreignData[0]['REFERENCED_COLUMN_NAME'] = $this->model->showColumns($table)['id_row'];

                }

                if($foreignData){

                    $table = $foreignData[0]['REFERENCED_TABLE_NAME'];

                    if($table !== $previousTable || $nextElement){

                        $previousTable = $table;

                        $idRow = $foreignData[0]['REFERENCED_COLUMN_NAME'];

                        $tempFields = $this->getTableColumns($table);

                        array_splice($fields, $key + 1, 0, $tempFields);

                        for($i = 0; $i < $key; $i++){

                            next($fields);

                        }

                        $nextElement = $this->getForeignElement($currentElement[$item] ?? null, $table, $idRow);

                        if($nextElement){

                            $this->setChaining($nextElement, $table, $chaining);

                            if(is_array($this->chainParents) && array_key_exists($table, $this->chainParents) && empty($this->chainParents[$table])){

                                $table = $currentTable;

                                $currentElement = $currentData;

                            }else{

                                $currentElement = $nextElement;

                            }

                        }else{

                            $currentElement = $currentData;

                            $table = $currentTable;

                            if($key === count($fields) - 1){

                                prev($fields);

                            }

                        }

                    }else{

                        $table = $currentTable;

                    }

                }else{

                    $table = $currentTable;

                }

                next($fields);

                $key = key($fields);

                $item = isset($key) ? $fields[$key] : null;

            }

        }

       return $this->setCorrectPrepareChaining($chaining, $currentTable);

    }

    private function setCorrectPrepareChaining($chaining, $table){

        if($chaining){

            $this->checkMetaData($chaining);

            if(isset($this->rootChainElement) && (!isset($this->chainParents[$table]) || !empty($this->chainParents[$table]))){

                $name = null;

                $alias = $table;

                if(!empty($this->rootChainElement)){

                    if(is_array($this->rootChainElement)){

                        $alias = key($this->rootChainElement);

                        $name = $this->rootChainElement[$alias];

                    }else{

                        $alias = $this->rootChainElement;

                    }

                }

                !$name && $name = !empty($this->metaData[$table]['name']) ? $this->metaData[$table]['name'] :
                    (Settings::get('projectTables')['name'] ?? null);

                if($name){

                    $chaining[] = [
                        'name' => $name,
                        'alias' => $alias
                    ];

                }

            }

            $chaining[] = [
                'name' => $this->translateEl('Главная'),
                'alias' => $this->alias()
            ];

        }

        $chaining && is_array($chaining) && $chaining = array_reverse($chaining);

        if(property_exists($this, 'chaining')){

            $this->chaining = $chaining;

        }

        return $chaining;

    }

    private function setChaining($data, $table, &$chaining){

        $c = count($chaining);

        if(empty($this->setFullChainData)){

            $chaining[$c][$this->columnName] = $data[$this->columnName] ?? '';

            $chaining[$c][$this->columnAlias] = $data[$this->columnAlias] ?? '';

        }else{

            $chaining[$c] = $data;

        }

        $chaining[$c]['table'] = $table;

    }

    private function getForeignElement($id, $table, $field){

        $columns = $this->model->showColumns($table);

        $where = [$field => $id];

        if(!empty($columns['visible'])){

            $where['visible'] = 1;

        }

        $result = $this->model->get($table, [
            'where' => $where,
            'limit' => 1,
            'single' => true
        ]);

        if($result){

            $this->setMeta($result);

        }

        return $result;

    }

    protected function getTableColumns($currentTable){

        $chainParents = $this->chainParents;

        if(!is_array($chainParents)){

            $chainParents = [];

        }

        if(!isset($chainParents[$currentTable])){

            $chainParents[$currentTable] = [];

            $parentRow = null;

            if(!empty($this->model->showColumns($currentTable)['parent_id'])){

                $parentRow = 'parent_id';

            }elseif(($currentTableForeignKeys = $this->model->showForeignKeys($currentTable))){

                $parentRow = $currentTableForeignKeys[0]['COLUMN_NAME'];

            }

            if(!$parentRow){

                return [];

            }

            $chainParents[$currentTable][] = $parentRow;

        }

        return $chainParents[$currentTable];

    }

    private function getCurrentTable(){

        $currentTable = null;

        if(!empty($this->inputMethod) && $this->inputMethod !== Settings::get('routes')['default']['inputMethod']){

            $inputMethod = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $this->inputMethod));

            if(in_array($inputMethod, $this->model->showTables())){

                $currentTable = $inputMethod;

            }

        }

        if(!$currentTable){

            $table = $this->table ?: $this->getController();

            if(in_array($table, $this->model->showTables())){

                $currentTable = $table;

            }

        }

        return $currentTable;

    }

    private function checkMetaData($chaining){

        $this->getMetaData();

        if($chaining && count($chaining) > 1){

            if($this->metaData){

                foreach (array_unique(array_column($chaining, 'table')) as $table){

                    $this->setMeta($this->metaData[$table] ?? []);

                }

            }

        }

        $this->setMeta($this->set ?? []);

    }

    protected function getCrumbsAlias($data){

        return !empty($data['alias']) ?
            $this->alias((!empty($data['table']) ? $data['table'] . '/' : '') . $data['alias']) : '';

    }

    protected function getMetaData(){

        if(!$this->metaData){

            if(in_array('metadata', $this->model->showTables())){

                $metadata = $this->model->get('metadata');

                if($metadata){

                    foreach ($metadata as $item){

                        $this->metaData[$item['table_name']] = $item;

                    }

                }

            }

        }

        return $this->metaData;

    }

    private function setMeta($data){

        if($data){

            if(!$this->title)
                $this->title = $data['title'] ?? ($data['name'] ?? '');

            if(!$this->h1)
                $this->h1 = $data['h1'] ?? ($data['name'] ?? '');

            if(!$this->description)
                $this->description = $data['description'] ?? '';

            if(!$this->keywords)
                $this->keywords = $data['keywords'] ?? '';

        }

    }

    protected function getMeta(){

        if($this->title){

            if(preg_match('/##/', $this->title)){

                if($this->h1)
                    $this->title = str_replace('##', $this->h1, $this->title);
                else
                    $this->title = preg_replace('/\s*##\s*/', '', $this->title);
            }

            echo '<title>' . $this->title . '</title>' . "\r\n";

        }

        if($this->description){

            if(preg_match('/##/', $this->description)){

                if($this->h1)
                    $this->description = str_replace('##', $this->h1, $this->description);
                else
                    $this->description = preg_replace('/\s*##\s*/', '', $this->description);


            }

            echo '<meta name="description" content="' . $this->description . '">'  . "\r\n";
        }

        if($this->keywords){

            if(preg_match('/##/', $this->keywords)){

                if($this->h1)
                    $this->keywords = str_replace('##', $this->h1, $this->keywords);
                else
                    $this->keywords = preg_replace('/\s*##\s*/', '', $this->keywords);


                $this->keywords = preg_replace('/\s*,\s*,\s*/', ', ', $this->keywords);

            }

            echo '<meta name="keywords" content="' . $this->keywords . '">'  . "\r\n";

        }

    }

}