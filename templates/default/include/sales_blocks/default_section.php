<?php if(!empty($data)):?>
    <section class="brands-description section">
        <div class="container">
            <div class="brands-description-wrapper">

                <?php $arr = ['first_', 'second_']?>

                <?php foreach ($arr as $value):?>

                    <?php if(empty($data[$value . 'inner_text'])):?>

                        <a href="<?=$this->alias($data[$value . 'external_alias'])?>" class="brands-description-item">
                            <div class="brands-description-img">
                                <img src="<?=$this->img($data[$value . 'img'])?>" alt="" class="img img_cover">
                            </div>
                            <div class="brands-description-info">
                                <div class="brands-description-title">
                                    <?=$data[$value . 'title']?>
                                </div>
                                <div class="brands-description-text">
                                    <?=$data[$value . 'short_content']?>
                                </div>
                            </div>
                        </a>

                    <?php else:?>

                        <a href="<?=$this->alias($data[$value . 'external_alias'])?>" class="brands-description-item brands-description-item_cover">
                            <div class="brands-description-img">
                                <img src="<?=$this->img($data[$value . 'img'])?>" alt="" class="img img_cover">
                            </div>
                            <div class="brands-description-info">
                                <div class="brands-description-title">
                                    <?=$data[$value . 'title']?>
                                </div>
                                <div class="brands-description-link">
                                    <?=$data[$value . 'short_content']?>
                                </div>
                            </div>
                        </a>
                    <?php endif;?>

                <?php endforeach;?>
            </div>
        </div>
    </section>
<?php endif;?>
