<?php if(!empty($data)):?>
    <section class="section index-sale">
        <div class="container">
            <div class="wrapper">
                <div class="index-sale-main">
                    <a href="<?=$this->alias($data['first_external_alias'])?>" class="index-sale-item index-sale-item_big">
                        <img src="<?=$this->img($data['first_img'])?>" alt="" class="img img_cover">
                    </a>
                    <a href="<?=$this->alias($data['second_external_alias'])?>" class="index-sale-item index-sale-item_small">
                        <img src="<?=$this->img($data['second_img'])?>" alt="" class="img img_cover">
                    </a>
                </div>
                <div class="index-sale-description">
                    <div class="index-sale-description-title">
                        <?=$data['name']?>
                    </div>
                    <div class="index-sale-description-text">
                        <?=$data['short_content']?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;?>
