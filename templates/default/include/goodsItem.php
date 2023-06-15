<?php if(!empty($data)):?>
    <a href="<?=$this->alias(['product' => $data['alias']])?>" class="card-section-item " data-productContainer>
          <span class="card-section-item-img">
            <img src="<?=$this->img($data['img'])?>" alt="" class="img">
            <span class="card-section-item-img_second">
              <img src="<?=$this->img($data['dop_img'])?>" alt="" class="img">
            </span>
            <span class="card-section-mark icon-star" <?php $this->delayed($data)?>></span>
          </span>

            <span <?=$this->setAddToCart($data)?>>
                <?=$this->cartBtnText($data)?>
            </span>

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

                </span>

                <?php if(!empty($data['offers'])):?>
                    <span class="card-section-size">
                      <span class="card-section-size-title card-section-title">
                        <?=$this->translateEl('Доступные размеры')?>
                      </span>
                      <span class="card-section-size-text">
                          <?php foreach ($data['offers'] as $item):?>
                              <span class="card-section-size-text-item" <?=$this->setOffers($data, $item)?>>
                                  <?=$item['name']?>
                                </span>
                          <?php endforeach;?>
                      </span>
                </span>
                <?php endif;?>

          </span>
    </a>
<?php endif;?>

