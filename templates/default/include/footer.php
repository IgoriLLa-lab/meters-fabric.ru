<footer class="footer">
    <div class="footer-top">
        <div class="container_middle">
            <div class="footer-top-wrapper">
                <div class="footer-info">
                    <div class="footer-logo">
                        <img src="<?=$this->img($this->set['img'])?>" alt="" class="img" ">
                    </div>
                    <div class="footer-description">
                        <?=$this->set['short_content']?>
                    </div>
                </div>

                <div class="footer-interaction">
                    <?php if($this->socials):?>
                        <div class="footer-title">
                            <?=$this->translateEl('ПОДПИШИТЕСЬ на Нас в социальных сетях')?>
                        </div>
                        <div class="footer-socials">
                            <?php foreach ($this->socials as $item):?>
                                <a href="<?=$this->alias($item['external_alias'])?>" target="_blank" class="footer-socials-item">
                                    <?=$this->img($item['img'], 1, ['style' => 'max-width:17px'])?>
                                </a>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
                    <?php if($this->set['payments_gallery_img']):?>
                    <div class="footer-title">
                        <?=$this->translateEl('Способы оплаты')?>
                    </div>
                    <div class="footer-pay">
                        <?php foreach (json_decode($this->set['payments_gallery_img'], true) as $item):?>
                            <a href="" class="footer-pay-item"><img src="<?=$this->img($item)?>" alt="" class="img"></a>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </div>

                <div class="footer-client">
                    <div class="footer-title">
                        <?=$this->translateEl('обслуживание клиентов')?>
                    </div>
                    <ul class="footer-list list">

                        <li class="footer-list-item">
                            <a href="<?=$this->alias('contacts')?>" class="footer-link">
                                <?=$this->translateEl('Контакты')?>
                            </a>
                        </li>

                        <?php if($this->clientServices):?>
                            <?php foreach ($this->clientServices as $item):?>
                            <li class="footer-list-item">
                                <a href="<?=$this->alias(['client-services' => $item['alias']])?>" class="footer-link">
                                    <?=$item['name']?>
                                </a>
                            </li>
                            <?php endforeach;?>
                        <?php endif;?>

                    </ul>

                </div>

                <div class="footer-services">
                    <?php if($this->services):?>
                        <div class="footer-title">
                            <?=$this->translateEl('Услуги')?>
                        </div>
                    <?php endif;?>

                    <ul class="footer-list list">

                        <?php foreach ($this->services as $item):?>
                            <li class="footer-list-item">
                                <a href="<?=$this->alias(['services' => $item['alias']])?>" class="footer-link">
                                    <?=$item['name']?>
                                </a>
                            </li>
                        <?php endforeach;?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container_middle">
            <div class="footer-bottom-wrapper">
                <div class="footer-copy">
                    © <?=$this->set['name']?>, <?=(new \DateTime())->format('Y')?>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="top-button">
    <div class="top-button-text">
        <?=$this->translateEl('Наверх')?>
    </div>
    <div class="top-button-toggle icon-arr-right">

    </div>
</div>
<div class="popup" tabindex="-1" role="dialog">
    <div class="popup__overlay js-popupWatch">
        <div class="popup__body js-popupBody" style="min-height: 225px; min-width: 400px">
            <div class="popup__close js-popupClose">
                <span class="icon-cancel"></span>
            </div>

            <div class="popup__wrap js-popupWrap">
                <div class="popup-loader">
                    <div class="popup-loader__spin"></div>
                </div>
                <div class="popup-target">

                </div>

            </div>
        </div>
    </div>
</div>

<?=$this->modals?>

<?php if(!empty($_SESSION['res']['answer'])):?>

    <div class="wq-message__wrap">
        <?=$_SESSION['res']['answer']?>
    </div>

    <?php unset($_SESSION['res'])?>
<?php endif;?>

<?php $this->getScripts()?>

</body>

</html>