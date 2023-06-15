<main class="main">

    <?php if(empty($data)):?>

        <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
            <div class="wrapper wrapper_center">
                <h1 class="page-title"><?=$this->translateEl('Ваша корзина пуста')?></h1>
            </div>
        </div>


    <?php else:?>

        <div class="container">
            <div class="wrapper wrapper_center">
                <h1 class="page-title"><?=$this->translateEl('Корзина')?></h1>
            </div>
        </div>

        <section class="basket-body section">
            <div class="container">
                <div class="basket-body-wrapper">
                    <div class="basket-main">
                        <div class="basket-main-wrapper">

                            <?php foreach ($data as $item):?>
                                <div class="basket-item" data-productContainer>
                                    <a href="<?=$this->alias(['product' => $item['alias']])?>" class="basket-item__img">
                                        <?php if (!empty($item['img'])):?>
                                        <img src="<?=$this->img($item['img'])?>" alt="" class="img img_cover">
                                        <?php endif;?>
                                    </a>
                                    <div class="basket-item-info">
                                        <div class="basket-item-title">
                                            <?=$item['parent_name']?>
                                        </div>
                                        <div class="basket-item-info__description">
                                            <?=$item['name']?>
                                        </div>
                                        <?php if (!empty($item['article'])):?>
                                        <div class="basket-item-info__article">
                                            <?=$this->translateEl('Артикул')?>: <?=$item['article']?>
                                        </div>
                                        <?php endif;?>
                                        <a href="#" class="basket-item-info__chose" <?=$this->delayed($item)?>>
                                            <span class="basket-item-info-mark icon-star"></span><span class="basket-item-info-text">
                                                <?=!empty($_SESSION['delayed']) ? $this->translateEl('В избранном') : $this->translateEl('В избранное')?>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="basket-item-check">
                                        <?php if (!empty($item['offers'])):?>
                                        <div class="basket-item-check__size">
                                            <div class="basket-item-check__title">
                                                <?=$this->translateEl('Размер:')?>
                                            </div>
                                            <div class="card-body-size">
                                                <span <?=$this->setOffers($item, $item['offers'])?>><?=$item['offers']['name']?></span>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <div class="basket-item-check__num">
                                            <div class="basket-item-check__title">
                                                <?=$this->translateEl('Количество: (1м.10см) для ткани')?>
                                            </div>
                                            <div class="basket-item__counter">
                                                <div class="basket-counter">

                                                    <div class="basket-counter__action" data-quantityMinus>-</div>
                                                    <input type="text" class="basket-counter__label" <?=$this->setCartQuantity($item)?>>
                                                    <div class="basket-counter__action" data-quantityPlus>+</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="basket-item-price">
                                        <?php if(!empty($item['old_price']) && !empty($item['discount'])):?>
                                            <span class="card-section-price-sale">
                                              <span <?=$this->setGoodsOldPrice($item)?>><?=$this->getGoodsOldPrice($item)?> ₽</span>
                                              <?=$item['discount']?>% <?=$this->translateEl('скидка')?>
                                            </span>

                                            <span class="card-section-price-num card-section-price-num_sale">
                                              <strong style="font-weight: normal" <?=$this->setGoodsPrice($item)?>><?=$this->getGoodsPrice($item)?></strong> ₽ за единицу товара
                                            </span>

                                        <?php else:?>
                                            <span class="card-section-price-num ">
                                              <strong style="font-weight: normal" <?=$this->setGoodsPrice($item)?>><?=$this->getGoodsPrice($item)?></strong> ₽ за единицу товара
                                            </span>

                                        <?php endif;?>
                                    </div>
<!--                                    <div class="basket-item-price">-->
<!--                                        --><?php //if(!empty($item['old_price']) && !empty($item['discount'])):?>
<!--                                            <span>--><?//=$this->translateEl('Итого')?><!-- </span><span><strong data-totalSum>--><?//=$this->cart['total_sum']?><!--</strong> ₽</span>-->
<!---->
<!--                                        --><?php //endif;?>
<!--                                    </div>-->
                                    <a href="<?=$this->alias(['cart' => 'delete', 'id' => $item['id'], 'offers' => $item['offers']['id'] ?? null])?>" class="basket-item-close">
                                        <div class="icon-cancel"></div>
                                    </a>
                                    <div <?=$this->setAddToCart($item)?> style="display: none"></div>
                                </div>
                            <?php endforeach;?>
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        document.querySelectorAll('.basket-main-wrapper [data-offers-quantity]').forEach(item => item.dispatchEvent(new Event('click')))
                                    })
                                </script>
                        </div>
                    </div>
                    <aside class="basket-aside">
                        <div class="basket-aside-top">
                            <div class="basket-aside__title">
                                <?=$this->translateEl('Итого')?>
                            </div>
                            <div class="basket-aside-price basket-aside-string">
                                <span><?=$this->translateEl('Итого')?> </span><span><strong data-totalSum><?=$this->cart['total_sum']?></strong> ₽</span>
                            </div>
                        </div>
                        <div class="basket-aside-bottom">
                            <div class="basket-aside-final basket-aside-string">
                                <span><?=$this->translateEl('Всего')?> <span><strong data-totalSum><?=$this->cart['total_sum']?></strong> ₽</span>
                            </div>
                            <button class="basket-aside-confirm button button_dark button_big" data-modal-mt="order">
                                <span><?=$this->translateEl('Перейти к оплате')?>
                            </button>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

    <?php endif;?>

    <?php if (!empty($moreData['delayed'])):?>
        <section class="chose-section section">
            <div class="container">
                <div class="chose-section-wrapper wrapper">
                    <div class="title">
                        <span><?=$this->translateEl('Избранное')?>
                    </div>
                    <div class="chose-section-description">
                        <div class="chose-section-description__text">
                            <span><?=$this->translateEl('Здесь собрано все, что вам понравилось')?>
                        </div>
                        <a href="<?=$this->alias(['lk' => 'delayed'])?>" class="chose-section-description__link">
                            <?=$this->translateEl('Перейти в Избранное')?>
                        </a>
                    </div>
                    <div class="chose-section__items">

                        <?php

                        foreach ($moreData['delayed'] as $item){

                            echo $this->showGoods($item);

                        }

                        ?>

                    </div>
                </div>
            </div>
        </section>
    <?php endif;?>

    <?=$this->render(TEMPLATE . 'include/recommendationsBlock', [
        'recommendations' => $recommendations ?? [],
        'viewed' => $moreData['viewed'] ?? []
    ])?>

<!--    --><?//=$this->render(TEMPLATE . 'include/questionBlock')?>


</main>
