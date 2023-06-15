<?php if(!empty($recommendations) || !empty($viewed)):?>
    <div class="catalog-cards tabs-container section">
        <div class="container">
            <div class="wrapper catalog-cards-wrapper">
                <div class="tabs-top catalog-cards-top">
                    <?php if(!empty($recommendations)):?>
                        <div class="tabs__toggle tabs__toggle_card tabs__toggle_active">
                            <?=$this->translateEl('Рекомендации')?>
                        </div>
                    <?php endif;?>
                    <?php if(!empty($viewed)):?>
                        <div class="tabs__toggle tabs__toggle_card <?=empty($recommendations) ? 'tabs__toggle_active' : ''?>">
                            <?=$this->translateEl('Недавно просмотренные')?>
                        </div>
                    <?php endif;?>

                </div>
                <div class="catalog-cards-body">
                    <div class="catalog-cards-tabs tabs">

                        <?php if(!empty($recommendations)):?>
                            <div class="catalog-cards-tab tabs__tab">
                                <div class="card-slider__prev icon-arrow-left card-slider-arrow"></div>
                                <div class="swiper-container card-slider">
                                    <div class="swiper-wrapper">

                                        <?php
                                            foreach ($recommendations as $item){

                                                $this->showGoods($item);

                                            }
                                        ?>

                                    </div>
                                </div>
                                <div class="card-slider__next icon-arrow-right card-slider-arrow"></div>
                            </div>
                        <?php endif;?>

                        <?php if(!empty($viewed)):?>
                            <div class="catalog-cards-tab tabs__tab">
                                <div class="card-slider__prev icon-arrow-left card-slider-arrow"></div>
                                <div class="swiper-container card-slider">
                                    <div class="swiper-wrapper">

                                        <?php
                                            foreach ($viewed as $item){

                                                $this->showGoods($item);

                                            }
                                        ?>

                                    </div>
                                </div>
                                <div class="card-slider__next icon-arrow-right card-slider-arrow"></div>
                            </div>
                        <?php endif;?>

                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif;?>
