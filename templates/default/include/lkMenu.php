<aside class="internal-aside internal-aside_lk">
    <div class="internal-aside-items">
        <?php if($this->userData):?>
            <a href="<?=$this->alias('lk')?>" class="internal-aside-item <?=$this->checkLkAlias()?>">
                <?=$this->translateEl('Мой аккаунт')?>
            </a>
            <a href="<?=$this->alias(['lk' => 'orders'])?>" class="internal-aside-item <?=$this->checkLkAlias('orders')?>">
                <?=$this->translateEl('Мои заказы')?>
            </a>
        <?php endif;?>

        <a href="<?=$this->alias(['lk' => 'delayed'])?>" class="internal-aside-item <?=$this->checkLkAlias('delayed')?>">
            <?=$this->translateEl('Избранное')?>
        </a>

        <?php if($this->userData):?>
            <a href="<?=$this->alias(['login' => 'logout'])?>" class="internal-aside-item ">
                <?=$this->translateEl('Выход')?>
            </a>
        <?php endif;?>
    </div>
</aside>
