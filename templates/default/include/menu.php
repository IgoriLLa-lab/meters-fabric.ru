<?php if(!empty($this->menu['root'])):?>

    <?php
        $class = $class ?? 'header-divisions-item';
    ?>

    <?php foreach ($this->menu['root'] as $item):?>
        <a href="<?=$this->alias(['catalog' => $item['alias']])?>" style="color: white" class="header-divisions-item <?=!empty($item['current']) ? 'header-divisions-item_active' : ''?>">
            <?=$item['name']?>
        </a>
    <?php endforeach;?>
<?php endif;?>
