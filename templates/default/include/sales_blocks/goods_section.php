<?php if(!empty($data)):?>
    <section class="card-section section">
        <div class="container">
            <div class="wrapper wrapper_center">
                <div class="title">
                    <?=$data['name']?>
                </div>
                <?php if(!empty($data['join']['goods'])):?>
                    <div class="card-section-items">
                        <?php foreach ($data['join']['goods'] as $item):?>

                            <?=$this->showGoods($item)?>

                        <?php endforeach;?>

                    </div>
                <?php endif;?>

            </div>
        </div>
    </section>
<?php endif;?>
