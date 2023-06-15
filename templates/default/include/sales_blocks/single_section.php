<?php if(!empty($data)):?>
    <section class="banner section banner_big">
        <div class="container">
            <div class="wrapper">
                <a href="<?=$this->alias($data['first_external_alias'])?>" class="banner-item">
                    <img src="<?=$this->img($data['first_img'])?>" class="img img_cover" alt="">
                </a>
            </div>
        </div>
    </section>
<?php endif;?>
