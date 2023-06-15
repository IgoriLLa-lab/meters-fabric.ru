<?php if(!empty($data)):?>
<aside class="catalog-aside">
    <div class="catalog-filter" data-filters>
        <div class="catalog-filter__close icon-cancel"></div>
        <div class="catalog-filter__items">

            <?php

                $menuFilters = $this->menu['sub'][$data['id']]['sub'] ?? ($this->menu['sub'][$data['parent_id']]['sub'] ?? null);

            ?>

            <?php if($menuFilters):?>
                <fieldset class="catalog-filter__item" data-toggle="init">
                    <legend class="catalog-filter__title catalog-filter__title_style_nb" data-toggle="button" aria-pressed="true" aria-expanded="true">
                        <?=$this->translateEl('Категория')?> <span class="catalog-filter__toggle icon-arrow-bottom"></span>
                    </legend>
                    <ul class="catalog-filter-categories catalog-filter__wrapper list" data-toggle="area">

                        <?php foreach ($menuFilters as $item):?>
                            <li class="catalog-filter-categories__item">
                                <label class="catalog-filter-categories__checkbox">
                                    <input type="checkbox" name="catalog[]" <?=!empty($_GET['catalog']) && in_array($item['id'], $_GET['catalog']) ? 'checked' : ''?> value="<?=$item['id']?>" class="visually-hidden checkbox__box" data-parsley-multiple="remember">
                                    <span class="catalog-filter-categories__color"></span>
                                    <span class="catalog-filter-categories__text"><?=$item['name']?></span>
                                </label>
                            </li>
                        <?php endforeach;?>

                    </ul>
                </fieldset>
            <?php endif;?>

            <?php

                if(!empty($catalogFilters)){

                    foreach ($catalogFilters as $item){

                        echo $this->render(TEMPLATE . 'include/filters_blocks/block-' . $item['template_type'], [
                            'data' => $item
                        ]);

                    }

                }

            ?>

        </div>

        <input type="submit" value="<?=$this->translateEl('Применить')?>">
        <input type="reset" value="<?=$this->translateEl('Сбросить')?>">

    </div>

</aside>
<?php endif;?>