<?php

namespace core\user\helpers;

trait CatalogHelper
{

    use CartHelper;

    protected function getGoods($where, &$catalogFilters = null, &$catalogPrices= null){

        if(empty($where['where']['visible'])){

            $where['where']['visible'] = 1;

        }

        if(!empty($_GET[$this->model->filtersTable]) && is_array($_GET[$this->model->filtersTable])){

            foreach ($_GET[$this->model->filtersTable] as $key => $item){

                $_GET[$this->model->filtersTable][$key] = $this->clearNum($item);

                if(empty($_GET[$this->model->filtersTable][$key])){

                    unset($_GET[$this->model->filtersTable][$key]);

                    continue;

                }

                $other = array_search($_GET[$this->model->filtersTable][$key], $_GET[$this->model->filtersTable]);

                if($other !== false && $other !== $key){

                    unset($_GET[$this->model->filtersTable][$key]);

                }

            }

            $res = $this->model->get($this->model->filtersParentTable, [
                'where' => [
                    '{IN}id' => $this->model->get($this->model->filtersTable, [
                        'fields' => ['parent_id'],
                        'where' => ['id' => $_GET[$this->model->filtersTable]],
                        'distinct' => true,
                        'return_query' => true
                    ])
                ],
                'join' => [
                    $this->model->filtersTable . ' f_val' => [
                        'where' => ['id' => $_GET[$this->model->filtersTable]],
                        'fields' => ['id'],
                        'on' => ['id' => 'parent_id']
                    ]
                ],
                'join_structure' => true
            ]);

            if($res){

                $arr = [];

                $c = 0;

                foreach ($res as $item){

                    if(!empty($item['join']['f_val'])){

                        $arr[$c] = array_keys($item['join']['f_val']);

                        $c++;

                    }

                }

                $resArr = $this->crossDiffArr($arr);

                if($resArr){

                    $queryStr = '';

                    $filtersCount = 0;

                    foreach ($resArr as $key => $item){

                        !$filtersCount && $filtersCount = count($item);

                        $queryStr .= ' ' . $this->model->filtersColumn . ' IN(' . implode(',', $item) . ')' . (isset($resArr[$key + 1]) ? ' OR' : '');

                    }

                    $where['where']['{IN}id'] = $this->model->get($this->model->filtersGoodsTable, [
                        'fields' => [$this->model->goodsColumn],
                        'where' => $queryStr,
                        'group' => $this->model->goodsColumn . ' HAVING COUNT(' . $this->model->goodsColumn . ') >= ' . $filtersCount,
                        'return_query' => true
                    ]);

                }

            }

        }

        if(isset($_GET['min_price']) && isset($_GET['max_price'])){

            $where['where']['><price'] = [$this->clearNum($_GET['min_price']), $this->clearNum($_GET['max_price'])];

        }

        if(!empty($_GET['order'])){

            $orderArr = preg_split('/_+/', $_GET['order'], 2);

            $where['order'] = $orderArr[0] . ' ' . ($orderArr[1] ?? '');

        }

        if(empty($where['pagination'])){

            $where['pagination'] = $this->clearNum($_GET['page'] ?? 1) ?? 1;

        }

        $data = $this->model->getGoods($where, $catalogFilters, $catalogPrices);

        if(!$data && !empty($where['where']['parent_id']) && empty($where['not_find_child_catalog'])){

            $ids = [];

            $categories = (array)$where['where']['parent_id'];

            foreach ($categories as $id){

                $res = $this->getChildren($id, $this->model->catalogTable, null, true);

                $res && $ids = array_merge($ids, $res);

            }

            $where['where']['parent_id'] = array_unique($ids);

            $data = $this->model->getGoods($where, $catalogFilters, $catalogPrices);

        }

        return $data;

    }

    protected function crossDiffArr($arr, $counter = 0){

        if(empty($arr))
            return [];

        if(count($arr) === 1)
            return array_chunk(array_shift($arr), 1);

        if($counter === count($arr) - 1)
            return $arr[$counter];

        $buffer = $this->crossDiffArr($arr, $counter + 1);

        $res = [];

        foreach ($arr[$counter] as $a){

            foreach ($buffer as $b){

                $res[] = is_array($b) ? array_merge([$a], $b) : [$a, $b];

            }

        }

        return $res;

    }

    protected function showGoods($data, $parameters = [], $template = 'goodsItem'){

        if(!empty($data)){

            echo $this->render(TEMPLATE . 'include/' . $template, compact('data', 'parameters'));

        }

    }

}