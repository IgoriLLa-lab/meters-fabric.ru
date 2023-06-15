<form class="fitting-main-form form-file" action="<?=$this->alias('send-mail')?>" method="post">
    <div class="fitting-main-title title">
        <?=$this->translateEl('Напишите нам')?>
    </div>


    <div class="fitting-main-fields">
        <label class="label">
            <input type="text" name="name" class="input" placeholder="<?=$this->translateEl('Имя')?>" value="<?=$this->setFormValues('name', 'userData')?>">
        </label>
        <label class="label">
            <input type="email" name="email" class="input" placeholder="E-mail*" value="<?=$this->setFormValues('email', 'userData')?>">
        </label>
        <label class="label">
            <input type="tel" name="phone" class="input" placeholder="<?=$this->translateEl('Телефон')?>*" value="<?=$this->setFormValues('phone', 'userData')?>">
        </label>
        <label class="label fitting-main-textarea__label">
            <textarea name="comment" class="input fitting-main-textarea" placeholder="<?=$this->translateEl('Комментарий')?>"><?=$this->setFormValues('comment', 'userData')?></textarea>
        </label>
    </div>
    <div class="fitting-main-form__description question-form-policy">
        <?php if($this->specialInformation):?>
            <?=$this->translateEl('Подтверждение согласия') . ' '?>
            <?php foreach ($this->specialInformation as $item):?>
                <a href="<?=$this->alias(['special-information' => $item['alias']])?>"><?=$item['name']?></a>
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <button class="button button_dark button_big">
        <?=$this->translateEl('Отправить')?>
    </button>
</form>
