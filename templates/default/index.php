<main class="index-main">

    <?php

        if(!empty($sales)){

            echo implode("\n", $sales);

        }

    ?>

    <?php if(!empty($this->set['video_content'])):?>
    <section class="section video">
        <div class="video-img">
            <?php if(!empty($this->set['video_img'])):?>
                <img class="img img_cover" src="<?=$this->img($this->set['video_img'])?>" alt="">
            <?php endif;?>
        </div>
        <div class="container">
            <div class="video-wrapper">
                <div class="video-title">
                    <?=$this->set['video_title']?>
                </div>
                <div class="video-description">
                    <?=$this->clearStr($this->set['video_text'], false)?>
                </div>
                <a href="<?=$this->set['video_content']?>" class="video-toggle" data-fancybox="gallery">
                    <div class="video-play icon-play"></div>
                </a>
            </div>
        </div>
    </section>
    <?php endif;?>

    <?=$this->render(TEMPLATE . 'include/questionBlock')?>


</main>
