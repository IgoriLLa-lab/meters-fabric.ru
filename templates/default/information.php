<?php if(!empty($data)):?>
    <main class="main">

        <div class="container">
            <div class="wrapper wrapper_center">
                <h1 class="page-title"><?=$data['name']?></h1>
            </div>
        </div>

        <?php if(!empty($data['img'])):?>
            <div class="internal-img section">
                <div class="container_big">
                    <div class="wrapper internal-img-wrapper">
                        <div class="internal-img__pic">
                            <img src="<?=$this->img($data['img'])?>" alt="" class="img img_cover">
                        </div>
                        <div class="internal-img__description">

                            <div class="internal-img__title">

                            </div>

                            <div class="internal-img__text">
                                <?=$data['short_content'] ?? ''?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;?>


        <section class="fitting-main section">

            <div class="fitting-main-wrapper wrapper">
                <div class="fitting-main__description">
                    <?=$data['content']?>
                </div>
                <div class="fitting-main-application">

                    <?=$this->render(TEMPLATE . 'include/feedBackForm')?>

                </div>
            </div>

        </section>

        <?=$this->render(TEMPLATE . 'include/questionBlock')?>

    </main>
<?php endif;?>