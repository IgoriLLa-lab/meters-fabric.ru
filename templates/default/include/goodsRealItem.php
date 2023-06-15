<?php if(!empty($data)):?>
    <div class="card-section-item " data-productContainer>
          <a href="<?=$this->alias(['product' => $data['alias']])?>" class="card-section-item-img">
            <img src="<?=$this->img($data['img'])?>" alt="" class="img">
              <?php if(!empty($data['dop_img'])):?>
                <span class="card-section-item-img_second">
                  <img src="<?=$this->img($data['dop_img'])?>" alt="" class="img">
                </span>
              <?php endif;?>
            <span class="card-section-mark icon-star" <?php $this->delayed($data)?>></span>
          </a>
            <span class="card-section-bottom">
                <span class="card-section-info">

                  <?php if(!empty($data['join']['catalog']['name'])):?>
                      <span class="card-section-title">
                            <?=$data['join']['catalog']['name']?>
                          </span>
                  <?php endif;?>


                  <span class="card-section-description">
                    <?=$data['name']?>
                  </span>

                  <span class="card-section-price">

                    <?php if(!empty($data['old_price']) && !empty($data['discount'])):?>
                        <span class="card-section-price-sale">
                          <span <?=$this->setGoodsOldPrice($data)?>><?=$this->getGoodsOldPrice($data)?> ₽</span>
                          <?=$data['discount']?>% <?=$this->translateEl('скидка')?>
                        </span>

                        <span class="card-section-price-num card-section-price-num_sale">
                          <strong style="font-weight: normal" <?=$this->setGoodsPrice($data)?>><?=$this->getGoodsPrice($data)?></strong> ₽
                        </span>
                    <?php else:?>
                        <span class="card-section-price-num ">
                          <strong style="font-weight: normal" <?=$this->setGoodsPrice($data)?>><?=$this->getGoodsPrice($data)?></strong> ₽
                        </span>
                    <?php endif;?>
                  </span>

                    <?php if(!empty($data['offers'])):?>
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
                    <?php endif;?>
                  <a href="#" class="card-body__basket button button_dark button_big" <?=$this->setAddToCart($data)?> <?=empty($data['offers']) ? 'style="margin-top:15px"' : ''?>>
                    <span><?=$this->cartBtnText($data)?></span> <span class="icon-bag"></span>
                  </a>
                </span>
          </span>
    </div>
<?php endif;?>
