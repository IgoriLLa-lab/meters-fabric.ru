<div class="modal" data-modal-target="order">
    <div class="modal-wrapper">
        <div class="close">Закрыть</div>
        <h2><?=$this->translateEl('Оформление заказа')?></h2>
        <form action="<?=$this->alias(['cart' => 'order'])?>" method="post">

            <input type="text" name="name" required placeholder="<?=$this->translateEl('Имя')?>*" value="<?=$this->setFormValues('name', 'userData')?>">
            <input type="email" name="email" required placeholder="E-mail*" value="<?=$this->setFormValues('email', 'userData')?>">
            <input type="tel" name="phone" required placeholder="<?=$this->translateEl('Телефон')?>*" value="<?=$this->setFormValues('phone', 'userData')?>">
            <textarea name="address" placeholder="<?=$this->translateEl('Адрес')?>"><?=$this->setFormValues('address', 'userData')?></textarea>
            <textarea name="comment" placeholder="<?=$this->translateEl('Комментарий к заказу')?>"><?=$this->setFormValues('comment', 'userData')?></textarea>

            <?php if($this->delivery):?>
                <label>
                    <div><?=$this->translateEl('Способы доставки')?></div>
                    <select name="delivery_id">
                        <?php foreach ($this->delivery as $item):?>
                            <option value="<?=$item['id']?>" <?=$item['id'] === $this->setFormValues('delivery_id') ? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach;?>
                    </select>
                </label>
            <?php endif;?>

            <?php if($this->payments):?>
                <label>
                    <div><?=$this->translateEl('Способы оплаты')?></div>
                    <select name="payments_id">
                        <?php foreach ($this->payments as $item):?>
                            <option value="<?=$item['id']?>" <?=$item['id'] === $this->setFormValues('payments_id') ? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach;?>
                    </select>
                </label>
            <?php endif;?>

            <div><?=$this->translateEl('Итого')?>: <strong data-totalSum><?=$this->cart['total_sum']?></strong>₽</div>

            <div class="order-btn">
                <button type="submit" class="button button_dark button_big"><?=$this->translateEl('Оформить заказ')?></button>
            </div>


        </form>
    </div>
</div>

<?php if(!$this->userData):?>
    <div class="modal" data-modal-target="lk">
        <div class="modal-wrapper">
            <div class="close">Закрыть</div>
            <div class="registration-titles" style="display: flex; justify-content:center; flex-wrap: wrap">
                <h2 style="cursor: pointer; margin-right: 20px"><?=$this->translateEl('Регистрация')?></h2>
                <h2 style="cursor: pointer"><?=$this->translateEl('Вход')?></h2>
            </div>

            <form action="<?=$this->alias(['login' => 'registration'])?>" method="post">

                <input type="text" name="name" required placeholder="<?=$this->translateEl('Имя')?>*" value="<?=$this->setFormValues('name', 'userData')?>">
                <input type="email" name="email" required  placeholder="E-mail*" value="<?=$this->setFormValues('email', 'userData')?>">
                <input type="tel" name="phone" required  placeholder="<?=$this->translateEl('Телефон')?>*" value="<?=$this->setFormValues('phone', 'userData')?>">
                <input type="password" name="password" required  placeholder="<?=$this->translateEl('Пароль')?>*" value="">
                <input type="password" name="confirm_password" required placeholder="<?=$this->translateEl('Подтверждение пароля')?>*" value="">


                <div class="order-btn">
                    <button type="submit" class="button button_dark button_big"><?=$this->translateEl('Регистрация')?></button>
                </div>


            </form>
            <form action="<?=$this->alias(['login' => 'login'])?>" method="post" style="display: none">

                <input type="text" name="login" required placeholder="<?=$this->translateEl('E-mail или телефон')?>*" value="">
                <input type="password" name="password" required placeholder="<?=$this->translateEl('Пароль')?>*" value="">


                <div class="order-btn">
                    <button type="submit" class="button button_dark button_big"><?=$this->translateEl('Вход')?></button>
                </div>

            </form>
        </div>
    </div>
<?php endif;?>
