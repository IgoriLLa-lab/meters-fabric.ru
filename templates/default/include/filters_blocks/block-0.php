<?php if(!empty($data['values'])):?>
    <fieldset class="catalog-filter__item" data-toggle="init">
        <legend class="catalog-filter__title catalog-filter__title_style_nb" data-toggle="button" aria-pressed="true" aria-expanded="true">
            <?=$data['name']?> <span class="catalog-filter__toggle icon-arrow-bottom"></span>
        </legend>
        <div class="catalog-filter__wrapper" data-toggle="area">

            <?php
                $count = count($data['values'])
            ?>

            <?php if($count > 10):?>
                <label class="catalog-filter__search">
                    <span class="catalog-filter__search-icon icon-search"></span>
                    <input type="text" placeholder="<?=$this->translateEl('Выбрать из')?> <?=$count?>" class="catalog-filter__search--input" />
                </label>
            <?php endif;?>

            <ul class="catalog-filter-unit catalog-filter-design__sublist catalog-filter-design__bottom catalog-filter-overflow list">

                <?php foreach ($data['values'] as $item):?>
                    <li class="catalog-filter-unit__item catalog-filter-design__subitem">
                        <label class="catalog-filter__checkbox checkbox">
                            <input type="checkbox" <?=!empty($_GET['filters']) && in_array($item['id'], $_GET['filters']) ? 'checked' : ''?> name="filters[]" value="<?=$item['id']?>" class="visually-hidden checkbox__box" data-parsley-multiple="remember">
                            <span class="checkbox__span checkbox__span_filter"></span>
                            <span class="checkbox__text checkbox__text_filter"><?=$item['name']?></span>
                        </label>
                    </li>
                <?php endforeach;?>

            </ul>
        </div>
    </fieldset>
<?php endif;?>
