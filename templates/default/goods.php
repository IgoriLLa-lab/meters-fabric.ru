<main class="main">

    <?php if(!empty($data)):?>
        <section class="card-body section">
        <div class="container">
            <div class="card-body__wrapper">
                <div class="card-body__view">
                    <div class="swiper-container card-body-slider">
                        <div class="swiper-wrapper">

                            <?php if(!empty($data['img'])):?>
                                <a href="<?=$this->img($data['img'])?>" data-fancybox="gallery" class="swiper-slide card-body-slide">
                                    <img class="img" src="<?=$this->img($data['img'])?>" alt="">
                                </a>
                            <?php endif;?>

                            <?php if(!empty($data['gallery_img'])):?>
                                <?php foreach (json_decode($data['gallery_img'], true) as $item):?>
                                    <a href="<?=$this->img($item)?>" data-fancybox="gallery" class="swiper-slide card-body-slide">
                                        <img class="img" src="<?=$this->img($item)?>" alt="">
                                    </a>
                                <?php endforeach;?>
                            <?php endif;?>

                        </div>
                    </div>

                    <div class="swiper-container card-body-thumb">
                        <div class="swiper-wrapper">

                            <?php if(!empty($data['img'])):?>
                                <div class="swiper-slide card-body-slide">
                                    <img class="img" src="<?=$this->img($data['img'])?>" alt="">
                                    <a href="<?=$this->img($data['img'])?>" data-fancybox="gallery" class="card-body-slide__icon icon-zoom"></a>
                                </div>
                            <?php endif;?>

                            <?php if(!empty($data['gallery_img'])):?>
                                <?php foreach (json_decode($data['gallery_img'], true) as $item):?>

                                    <div class="swiper-slide card-body-slide">
                                        <img class="img" src="<?=$this->img($item)?>" alt="">
                                        <a href="<?=$this->img($item)?>" data-fancybox="gallery" class="card-body-slide__icon icon-zoom"></a>
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>

                        </div>
                    </div>
                </div>
                <div class="card-body__info">
<!--                    <div class="card-body__season">-->
<!--                        Новый сезон-->
<!--                    </div>-->
                    <?php if(!empty($data['join']['catalog']['name'])):?>
                        <div class="card-body__title">
                            <?=$data['join']['catalog']['name']?>
                        </div>
                    <?php endif;?>
                    <div class="card-body__name">
                        <?=$data['name']?>
                    </div>

                    <?php if(!empty($data['old_price']) && !empty($data['discount'])):?>
                        <span class="card-section-price-sale">
                          <span <?=$this->setGoodsOldPrice($data)?>><?=$this->getGoodsOldPrice($data)?> ₽</span>
                          <?=$data['discount']?>% <?=$this->translateEl('скидка')?>
                        </span>
                    <?php endif;?>

                    <div class="card-body__price">
                        <strong style="font-weight: normal" <?=$this->setGoodsPrice($data)?>><?=$this->getGoodsPrice($data)?></strong> ₽
                    </div>

                    <?php if(!empty($this->set['global_sale'])):?>
                        <div class="card-body-action">
                            <div class="card-body-action__text">
                                <?=$this->set['global_sale']?>
                            </div>
                            <?php if(!empty($this->set['global_sale_period'])):?>
                                <div class="card-body-action__time">
                                    <?=$this->set['global_sale_period']?>
                                </div>
                            <?php endif;?>
                        </div>
                    <?php endif;?>

                    <?php if(!empty($data['offers'])):?>
                        <div class="card-body-size">

                            <div class="offers-wrapper">
                                <div class="offers-select">
                                    <span class="icon-arrow-bottom offers-select-arrow"></span>
                                    <span class="offers-select-trigger" data-default-value="<?=$this->translateEl('Выберите размер')?>">
                                        <?=$this->translateEl('Выберите размер')?>
                                    </span>
                                    <div class="offers-options">
                                        <?php foreach ($data['offers'] as $item):?>
                                            <span class="offers-option" <?=$this->setOffers($data, $item)?>>
                                                <span class="custom-option__text"><?=$item['name']?></span>
                                                <?php if($item['quantity'] === '1'):?>
                                                    <span class="custom-option__num">1 <?=$this->translateEl('экземпляр')?></span>
                                                <?php endif;?>
                                            </span>
                                        <?php endforeach;?>
                                    </div>

                                </div>
                            </div>

                        </div>
                    <?php endif;?>

                    <a href="#" class="card-body__basket button button_dark button_big" <?=$this->setAddToCart($data)?>>
                        <span><?=$this->cartBtnText($data)?></span>
                        <span class="icon-bag"></span>
                    </a>
                    <div class="card-body-buttons">
                        <a href="#" class="card-body__buy card-body-buttons__item button button_light button_middle" <?=$this->setAddToCartOneClick($data)?>>
                            <?=$this->translateEl('Купить в один клик')?>
                        </a>
                        <a href="#" class="card-body__favorites card-body-buttons__item button button_light button_middle" <?php $this->delayed($data)?>>
                            <?=empty($_SESSION['delayed'][$data['id']]) ? $this->translateEl('В избранное') : $this->translateEl('В избранном')?> <span class="icon-star"></span></a>
                    </div>

                    <ul class="card-body__list list">

                        <?php if($data['content'] || $data['filters']):?>
                            <li class="card-body-item" data-toggle="init">
                                <div class="card-body-item__title" data-toggle="button" aria-pressed="false" aria-expanded="false">
                                    <span class="icon-arrow-bottom card-body-tem-icon"></span>
                                    <?=$this->translateEl('Описание')?>
                                </div>
                                <div class="card-body-item__wrapper" style="display: none" data-toggle="area" data-simplebar data-simplebar-auto-hide="false">
                                    <?php if($data['content']):?>
                                        <div class="card-body-item__text">
                                            <?=$data['content']?>
                                        </div>
                                    <?php endif;?>

                                    <?php if($data['filters']):?>
                                        <ul class="card-body-item__list list">

                                            <?php foreach ($data['filters'] as $item):?>
                                                <li class="card-body-item__unit">
                                                    <?=$item['name']?>: <?=implode(', ', array_column($item['values'], 'name'))?>
                                                </li>
                                            <?php endforeach;?>

                                        </ul>
                                    <?php endif;?>


                                </div>
                            </li>
                        <?php endif;?>

                        <?php if($data['dop_content'] && $data['dop_content_title']):?>
                            <li class="card-body-item" data-toggle="init">
                                <div class="card-body-item__title" data-toggle="button" aria-pressed="false" aria-expanded="false">
                                    <span class="icon-arrow-bottom card-body-tem-icon"></span>
                                    <?=$data['dop_content_title']?>
                                </div>
                                <div class="card-body-item__wrapper" style="display: none" data-toggle="area" data-simplebar data-simplebar-auto-hide="false">
                                    <div class="card-body-item__text">
                                        <?=$data['dop_content']?>
                                    </div>
                                </div>
                            </li>
                        <?php endif;?>

                        <?php if($this->set['delivery_information']):?>
                            <li class="card-body-item" data-toggle="init">
                                <div class="card-body-item__title" data-toggle="button" aria-pressed="false" aria-expanded="false">
                                    <span class="icon-arrow-bottom card-body-tem-icon"></span>
                                    <?=$this->translateEl('Доставка и возврат')?>
                                </div>
                                <div class="card-body-item__wrapper" style="display: none" data-toggle="area" data-simplebar data-simplebar-auto-hide="false">
                                    <div class="card-body-item__text">
                                        <?=$this->set['delivery_information']?>
                                    </div>
                                </div>
                            </li>
                        <?php endif;?>

                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php endif;?>

    <?php if(!empty($additionalGoods)):?>
        <section class="card-trade section">
            <div class="container">
                <div class="card-trade-body">
                    <div class="title">
                        <?=$this->translateEl('Дополнить образ')?>
                    </div>
<!--                    <div class="card-trade__wrapper">-->
<!--                        --><?php //if(!empty($data['dop_img'])):?>
<!--                            <div class="card-trade__img">-->
<!--                                <img src="--><?//=$this->img($data['dop_img'])?><!--" class="img" alt="">-->
<!--                            </div>-->
<!--                        --><?php //endif;?>

                        <?php foreach ($additionalGoods as $item){

                            echo $this->showGoods($item, [], 'goodsRealItem');

                        }
                        ?>

                    </div>
                </div>

            </div>
        </section>
    <?php endif;?>

    <?=$this->render(TEMPLATE . 'include/recommendationsBlock', [
        'recommendations' => $recommendations ?? [],
        'viewed' => $viewed ?? []
    ])?>

    <?=$this->render(TEMPLATE . 'include/questionBlock')?>


</main>
