<?php if(!empty($data)):?>
    <main class="main">

        <div class="container">
            <div class="wrapper wrapper_center">
                <h1 class="page-title"><?=$data['name']?></h1>
            </div>
        </div>


        <div class="catalog">
            <div class="container">
                <div class="wrapper">
          <span class="catalog-description">
            <span class="catalog-description__text">
              <?=$data['content']?>

              <a href="#" class="catalog-description-link">
                <?=$this->translateEl('Показать')?>
              </a>
            </span>
          </span>
                    <div class="catalog-top">
                        <div class="catalog-aside__head">
                            <div class="catalog-aside__title"><?=$this->translateEl('Фильтр товаров')?></div>
                            <div class="catalog-aside__arrow icon-arrow-bottom"></div>
                        </div>
                        <div class="catalog-models">
                            <?=!empty($totalCount) ? $totalCount . ' ' . $this->wordsForCounter($totalCount, ['моделей', 'модуль', 'модели']) : ''?>
                        </div>
                        <div class="catalog-sort">
                            <?php if(!empty($order)):?>
                            <div class="catalog-sort__title">

                            </div>
                            <div class="catalog-sort__inner">
                                <?php

                                    $GET = $_GET ?? [];

                                ?>
                                <?php foreach ($order as $key => $item):?>
                                    <a href="<?=$this->alias(['catalog' => $this->parameters['alias']], array_merge($GET, ['order' => $key]))?>" class="catalog-sort__item">
                                        <?=$item?>
                                    </a>
                                <?php endforeach;?>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="catalog-body section">

                        <?=$this->render(TEMPLATE . 'include/filtersBlock', [
                            'data' => $data,
                            'catalogFilters' => $catalogFilters ?? [],
                            'catalogPrices' => $catalogPrices ?? [],
                        ])?>

                        <div class="catalog-main">
                            <div class="catalog-main-items">

                                <?php

                                    if(!empty($goods)){

                                        foreach ($goods as $item){

                                            $this->showGoods($item);

                                        }

                                    }

                                ?>

                            </div>
                            <?php if(!empty($pages)):?>
                            <div class="pagination">

                                <div class="pagination-description">
                                    <?php $this->pagination($pages)?>
                                </div>

                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?=$this->render(TEMPLATE . 'include/recommendationsBlock', [
            'recommendations' => $recommendations ?? [],
            'viewed' => $viewed ?? []
        ])?>

        <?=$this->render(TEMPLATE . 'include/questionBlock')?>


    </main>
<?php endif;?>

