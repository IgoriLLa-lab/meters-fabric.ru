<?php if(!empty($data['values'])):?>
    <fieldset class="catalog-filter__item" data-toggle="init">
        <legend class="catalog-filter__title catalog-filter__title_style_nb" data-toggle="button" aria-pressed="true" aria-expanded="true">
            <?=$data['name']?> <span class="catalog-filter__toggle icon-arrow-bottom"></span>
        </legend>

        <ul class="catalog-filter-color list catalog-filter__wrapper" data-toggle="area">

            <?php foreach ($data['values'] as $item):?>

                <li class="catalog-filter-color__item">
                    <label class="catalog-filter-color__checkbox">
                        <input type="checkbox" <?=!empty($_GET['filters']) && in_array($item['id'], $_GET['filters']) ? 'checked' : ''?> name="filters[]" value="<?=$item['id']?>" class="visually-hidden checkbox__box" data-parsley-multiple="remember">
                        <span class="catalog-filter-color__color"></span>
                        <span class="catalog-filter-color__text">
                            <span class="catalog-filter-color__view catalog-filter-color__view" style="background: <?=$this->createColors($item['colorpicker'])?>"></span>
                            <span class="catalog-filter-color__text">
                              <?=$item['name']?>
                            </span>
                          </span>
                    </label>
                </li>

            <?php endforeach;?>


        </ul>
    </fieldset>
<?php endif;?>
