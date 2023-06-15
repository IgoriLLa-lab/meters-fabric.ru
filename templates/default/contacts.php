<main class="main">

    <div class="container">
        <div class="wrapper wrapper_center">
            <h1 class="page-title"><?=$this->translateEl('Наши Контакты')?></h1>
        </div>
    </div>


    <div class="internal-img section" style="margin-bottom: 100px;">
        <div class="container_big">
            <div class="wrapper internal-img-wrapper">
                <div class="internal-img__pic">
                    <img src="<?=$this->img($this->set['video_img'])?>" alt="" class="img img_cover">
                </div>
                <div class="internal-img__description">

                    <div class="internal-img__title">
                        <?=$this->translateEl('Нужена помощь от наших специалистов?')?>
                    </div>

                    <div class="internal-img__text">
                        <?=$this->translateEl('Наша команда всегда в Вашем распоряжении, чтобы ответить на все Ваши вопросы. Не стесняйтесь обращаться к нам и размещать заказ по телефону')?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div>
                <div class="contacts-body">
                    <div class="contacts-items">

                        <div class="contacts-item">
                            <div class="contacts-item__title">
                                <span class="icon-phone contacts-item__icon"></span>
                                <div class="contacts-item__text">
                                    <?=$this->translateEl('Телефон')?>:
                                </div>
                            </div>
                            <a href="tel:<?=preg_replace('/\D/', '', $this->set['phone'])?>" class="contacts-item__content">
                                <?=$this->set['phone']?>
                            </a>
                        </div>

                        <div class="contacts-item">
                            <div class="contacts-item__title">
                                <span class="icon-map contacts-item__icon"></span>
                                <div class="contacts-item__text">
                                    <?=$this->translateEl('Адрес')?>:
                                </div>
                            </div>
                            <div class="contacts-item__content">
                                <?=$this->set['address']?>
                            </div>
                        </div>

                        <div class="contacts-item">
                            <div class="contacts-item__title">
                                <span class="icon-whatsapp contacts-item__icon"></span>
                                <div class="contacts-item__text">
                                    WhatsApp:
                                </div>
                            </div>
                            <div class="contacts-item__content">
                                <?=$this->set['phone']?>
                            </div>
                        </div>

                        <div class="contacts-item">
                            <div class="contacts-item__title">
                                <span class="icon-clock contacts-item__icon"></span>
                                <div class="contacts-item__text">
                                    <?=$this->translateEl('Время работы')?>:
                                </div>
                            </div>
                            <div class="contacts-item__content">
                                <?=$this->set['work_time']?>
                            </div>
                        </div>

                        <div class="contacts-item">
                            <div class="contacts-item__title">
                                <span class="icon-email contacts-item__icon"></span>
                                <div class="contacts-item__text">
                                    E-mail:
                                </div>
                            </div>
                            <a href="#" class="contacts-item__content">
                                <?=$this->set['email']?>
                            </a>
                        </div>

                    </div>

                    <div class="contacts-contact">

                        <?=$this->render(TEMPLATE . 'include/feedBackForm')?>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
