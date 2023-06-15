<main class="main">
    <div class="container">
        <div class="wrapper wrapper_center">
            <h1 class="page-title"><?=$this->translateEl('Мой аккаунт')?></h1>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <div class="wrapper_internal wrapper_lk">

                <?=$this->render(TEMPLATE . 'include/lkMenu', [
                    'orders' => $orders ?? null,
                    'delayed' => $delayed ?? null
                ])?>

                <div class="lk-body">

                    <form action="<?=$this->alias(['login' => 'registration'])?>" class="lk-body" method="post">

                        <div class="lk-body-top">
                            <div class="lk-account__mail lk-account__item">
                                <div class="lk-account__name">
                                    E-mail
                                </div>
                                <label class="lk-account__field label">
                                    <input type="text" name="email" value="<?=$this->setFormValues('email', 'userData')?>" placeholder="Email" class="input">
                                </label>
                            </div>
                            <div class="lk-account__pass lk-account__item lk-account__item_horizontal">
                                <div class="lk-account__pass-item">
                                    <div class="lk-account__name">
                                        <?=$this->translateEl('Пароль')?>:
                                    </div>
                                    <label class="lk-account__field label">
                                        <input type="password" name="password" class="input">
                                    </label>
                                </div>
                                <div class="lk-account__pass-item">
                                    <div class="lk-account__name">
                                        <?=$this->translateEl('Повторить пароль')?>:
                                    </div>
                                    <label class="lk-account__field label">
                                        <input type="password" name="confirm_password" class="input">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="lk-body-bottom">

                            <div class="lk-account__title lk-account__item">
                                <div class="lk-account__name">
                                    <?=$this->translateEl('Имя')?>:
                                </div>
                                <label class="lk-account__field label">
                                    <input type="text" name="name" value="<?=$this->setFormValues('name', 'userData')?>" class="input">
                                </label>
                            </div>

                            <div class="lk-account__phone lk-account__item">
                                <div class="lk-account__name">
                                    <?=$this->translateEl('Телефон')?>:
                                </div>
                                <label class="lk-account__field label">
                                    <input type="tel" name="phone" value="<?=$this->setFormValues('phone', 'userData')?>" class="input">
                                </label>
                            </div>
                        </div>
                        <div class="lk-account__buttons">
                            <input type="reset" value="Отменить" class="button button_light button_big">
                            <input type="submit"  value="Сохранить" class="button button_dark button_big">

                        </div>
                    </form>
                </div>
            </div>
        </div></section>

        <?=$this->render(TEMPLATE . 'include/questionBlock')?>

</main>
