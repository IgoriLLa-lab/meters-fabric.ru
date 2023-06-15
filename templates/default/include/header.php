<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, shrink-to-fit=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>

    <?php $this->getStyles()?>

</head>

<body>
<div class="loader">
    <div class="loader-spin"></div>
</div>
<header class="header ">
    <div class="container_big">
        <div class="wrapper">
            <div class="header-wrapper">
                <div class="header-top">
                    <div class="header-logo-mobile">
                        <a href="<?=$this->alias()?>"><?=$this->img($this->set['img'], 1)?></a>
                    </div>

                    <div class="header-interaction">

                        <a href="<?=$this->alias('lk')?>" class="header-interaction-item " <?=!$this->userData ? 'data-modal-mt="lk"' : ''?>>
                            <span class="header-divisions-item-icon icon-user"></span>
                            <span class="header-divisions-item-text"><?=$this->userData['name'] ?? $this->translateEl('Вход/регистрация')?></span>
                        </a>

                        <a href="<?=$this->alias(['lk' => 'delayed'])?>" class="header-interaction-item ">
                            <span class="header-divisions-item-icon icon-star" <?php $this->headerDelayed()?>></span>
                        </a>

                        <a href="<?=$this->alias('cart')?>" class="header-interaction-item " data-header-cart>
                            <span class="header-divisions-item-icon icon-bag"></span>
                        </a>

                    </div>

                    <button class="burger button button_burger"></button>
                </div>

                <div class="mobile-menu">

                    <div class="mobile-menu-divisions">

                       <?=$this->render(TEMPLATE . 'include/menu', ['class' => 'mobile-menu-divisions-item'])?>

                    </div>

                    <?php if(!empty($this->menu['sub'])):?>
                        <nav class="mobile-menu-navigation">

                            <ul class="list mobile-menu-list">

                                <?php if(!empty($this->menu['designers'])):?>
                                    <li class="mobile-menu-navigation-item" data-toggle="init">

                                        <a href="<?=$this->alias(['designers' => $this->rootAlias])?>" class="mobile-menu-navigation-link link"><?=$this->translateEl('Дизайнеры')?></a>

                                        <div class="mobile-menu-navigation-item-toggle icon-arrow-bottom" data-toggle="button"></div>

                                        <div class="mobile-menu-navigation-wrapper" data-toggle="area">
                                            <div class="mobile-menu-subnavigation">
                                                <div class="mobile-menu-subitems">

                                                    <div class="mobile-menu-subitem mobile-menu-subitem_toggle">

                                                        <?php foreach ($this->menu['designers'] as $item):?>

                                                            <a href="<?=$this->alias(['catalog' => $this->rootAlias], ['filters[]' => $item['id']])?>" class="mobile-menu-subnavigation-link link">
                                                                <?=$item['name']?>
                                                            </a>

                                                        <?php endforeach;?>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                <?php endif;?>

                                <?php if(!empty($this->menu['sub'])):?>

                                    <?php foreach ($this->menu['sub'] as $item):?>

                                        <li class="mobile-menu-navigation-item" <?=!empty($item['sub']) ? 'data-toggle="init"' : ''?>>

                                            <div class="mobile-menu-navigation-link link"><?=$item['name']?></div>

                                            <?php if(!empty($item['sub']) || !empty($item['designers']) ||
                                                $this->checkTizers(($item['tizers_links'] ?? [])) ||
                                                $this->checkTizers(($item['tizers_recomendations'] ?? []))):?>

                                                <div class="mobile-menu-navigation-item-toggle icon-arrow-bottom" data-toggle="button"></div>

                                                <div class="mobile-menu-navigation-wrapper" data-toggle="area">
                                                    <div class="mobile-menu-subnavigation">
                                                        <div class="mobile-menu-subitems">

                                                            <?php if(!empty($item['sub'])):?>
                                                            <div class="mobile-menu-subitem mobile-menu-subitem_toggle" >

                                                                <?php foreach ($item['sub'] as $value):?>

                                                                    <a href="<?=$this->alias(['catalog' => $value['alias']])?>" class="mobile-menu-subnavigation-link link">
                                                                        <?=$value['name']?>
                                                                    </a>

                                                                <?php endforeach;?>

                                                            </div>
                                                            <?php endif;?>

                                                            <?php if($this->checkTizers(($item['tizers_links'] ?? []))):?>
                                                                <div class="mobile-menu-subitem ">
                                                                    <a class="mobile-menu-subitem-title ">

                                                                    </a>

                                                                    <ul class="mobile-menu-sublist list ">
                                                                        <?php foreach ($this->checkTizers() as $value):?>
                                                                            <li class="mobile-menu-subnavigation-item">
                                                                                <a href="<?=$this->alias($value['value'])?>" class="mobile-menu-subnavigation-link link">
                                                                                    <?=$value['name']?>
                                                                                </a>
                                                                            </li>
                                                                        <?php endforeach;?>

                                                                    </ul>
                                                                </div>
                                                            <?php endif;?>

                                                            <?php if(!empty($item['designers'])):?>

                                                                <div class="mobile-menu-subitem mobile-menu-subitem_toggle" data-toggle="init">
                                                                    <a class="mobile-menu-subitem-title mobile-menu-subitem-title_toggle">
                                                                        <?=$this->translateEl('Дизайнеры')?>
                                                                    </a>
                                                                    <span class="mobile-menu-subitem-title-toggle icon-arrow-bottom" data-toggle="button"></span>
                                                                    <ul class="mobile-menu-sublist list mobile-menu-sublist-toggle" data-toggle="area">

                                                                        <?php foreach ($item['designers'] as $value):?>
                                                                            <li class="mobile-menu-subnavigation-item">
                                                                                <a href="<?=$this->alias(['catalog' => $this->rootAlias], ['filters[]' => $value['id']])?>" class="mobile-menu-subnavigation-link link">
                                                                                    <?=$value['name']?>
                                                                                </a>
                                                                            </li>
                                                                        <?php endforeach;?>
                                                                    </ul>
                                                                </div>

                                                            <?php endif;?>

                                                        </div>
                                                        <?php if($this->checkTizers($item['tizers_recomendations'])):?>
                                                            <div class="header-subnavigation-card">
                                                                <?php foreach ($this->checkTizers() as $value):?>
                                                                    <?php if(!empty($value['file'])):?>
                                                                        <div class="header-subnavigation-card-img">
                                                                            <img src="<?=$this->img($value['file'])?>" alt="" class="img">
                                                                        </div>
                                                                    <?php endif;?>

                                                                    <div class="header-subnavigation-card-description">
                                                                        <?=$this->translateEl('Наш выбор')?>
                                                                    </div>

                                                                    <div class="header-subnavigation-card-title">
                                                                        <?=$value['name'] ?? ''?>
                                                                    </div>

                                                                    <?php

                                                                    $valueArr = preg_split('/\s*[:\-]\s*/', $value['value'], 2, PREG_SPLIT_NO_EMPTY);

                                                                    ?>

                                                                    <a href="<?=$valueArr[1] ?? ''?>" class="header-subnavigation-card-link">
                                                                        <?=$valueArr[0]?>
                                                                    </a>
                                                                <?php endforeach;?>

                                                            </div>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            <?php endif;?>

                                        </li>

                                    <?php endforeach;?>

                                <?php endif;?>

                                <?php if($this->pages):?>
                                    <?php foreach ($this->pages as $item):?>
                                        <li class="mobile-menu-navigation-item">
                                            <a href="<?=$this->alias(['pages' => $item['alias']])?>" class="mobile-menu-navigation-link link"><?=$item['name']?></a>

                                        </li>
                                    <?php endforeach;?>
                                <?php endif;?>

                            </ul>
                        </nav>
                    <?php endif;?>

                </div>

                <a href="<?=$this->alias()?>" class="header-logo">
                    <img src="<?=$this->img($this->set['img'])?>" alt="" class="img">
                </a>


                <nav class="header-navigation">
                    <ul class="list header-list">

                        <?php if(!empty($this->menu['designers'])):?>
                            <li class="header-navigation-item">
                                <a href="<?=$this->alias(['designers' => $this->rootAlias])?>" class="header-navigation-link link"><?=$this->translateEl('Дизайнеры')?></a>

                                <div class="header-navigation-wrapper">
                                    <div class="header-subnavigation">
                                        <div class="header-subitems">
                                            <?php foreach (array_chunk($this->menu['designers'], ceil(count($this->menu['designers']) / 3)) as $value):?>
                                            <div class="header-subitem">

                                                <ul class="header-sublist list">

                                                    <?php foreach ($value as $item):?>
                                                        <li class="header-subnavigation-item">
                                                            <a href="<?=$this->alias(['catalog' => $this->rootAlias], ['filters[]' => $item['id']])?>" class="header-subnavigation-link link">
                                                                <?=$item['name']?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach;?>

                                                </ul>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>

                            </li>
                        <?php endif;?>

                        <?php if(!empty($this->menu['sub'])):?>
                            <?php foreach ($this->menu['sub'] as $item):?>
                            <li class="header-navigation-item">

                                <div class="header-navigation-link link"><?=$item['name']?></div>

                                <?php if(!empty($item['sub']) || !empty($item['designers']) ||
                                        $this->checkTizers(($item['tizers_links'] ?? [])) ||
                                        $this->checkTizers(($item['tizers_recomendations'] ?? []))):?>

                                    <div class="header-navigation-wrapper">
                                        <div class="header-subnavigation">
                                            <div class="header-subitems">

                                                <?php if(!empty($item['sub'])):?>
                                                    <div class="header-subitem">
                                                        <a href="<?=$this->alias(['catalog' => $item['alias']])?>" class="header-subitem-title ">
                                                            <?=$item['name']?>
                                                        </a>
                                                        <ul class="header-sublist list">

                                                            <?php foreach ($item['sub'] as $value):?>
                                                                <li class="header-subnavigation-item">
                                                                    <a href="<?=$this->alias(['catalog' => $value['alias']])?>" class="header-subnavigation-link link">
                                                                        <?=$value['name']?>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach;?>

                                                        </ul>
                                                    </div>
                                                <?php endif;?>

                                                <?php if($this->checkTizers($item['tizers_links'])):?>
                                                    <div class="header-subitem">
                                                        <a class="header-subitem-title header-subitem-title_empty">

                                                        </a>
                                                        <ul class="header-sublist list">

                                                            <?php foreach ($this->checkTizers() as $value):?>
                                                                <li class="header-subnavigation-item">
                                                                    <a href="<?=$this->alias($value['value'])?>" class="header-subnavigation-link link">
                                                                        <?=$value['name']?>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach;?>

                                                        </ul>
                                                    </div>
                                                <?php endif;?>

                                                <?php if(!empty($item['designers'])):?>
                                                    <div class="header-subitem">
                                                        <a href="<?=$this->alias(['designers' => $this->rootAlias])?>" class="header-subitem-title ">
                                                            <?=$this->translateEl('Дизайнеры')?>
                                                        </a>
                                                        <ul class="header-sublist list">
                                                            <?php foreach ($item['designers'] as $value):?>
                                                            <li class="header-subnavigation-item">
                                                                <a href="<?=$this->alias(['catalog' => $this->rootAlias], ['filters[]' => $value['id']])?>" class="header-subnavigation-link link">
                                                                    <?=$value['name']?>
                                                                </a>
                                                            </li>
                                                            <?php endforeach;?>

                                                        </ul>
                                                    </div>
                                                <?php endif;?>

                                            </div>
                                            <?php if($this->checkTizers($item['tizers_recomendations'])):?>
                                                <div class="header-subnavigation-card">
                                                    <?php foreach ($this->checkTizers() as $value):?>
                                                        <?php if(!empty($value['file'])):?>
                                                            <div class="header-subnavigation-card-img">
                                                                <img src="<?=$this->img($value['file'])?>" alt="" class="img">
                                                            </div>
                                                        <?php endif;?>

                                                        <div class="header-subnavigation-card-description">
                                                            <?=$this->translateEl('Наш выбор')?>
                                                        </div>

                                                        <div class="header-subnavigation-card-title">
                                                            <?=$value['name'] ?? ''?>
                                                        </div>

                                                        <?php

                                                        $valueArr = preg_split('/\s*[:\-]\s*/', $value['value'], 2, PREG_SPLIT_NO_EMPTY);

                                                        ?>

                                                        <a href="<?=$valueArr[1] ?? ''?>" class="header-subnavigation-card-link">
                                                            <?=$valueArr[0]?>
                                                        </a>
                                                    <?php endforeach;?>

                                                </div>
                                            <?php endif;?>

                                        </div>
                                    </div>
                                <?php endif;?>
                            </li>
                            <?php endforeach;?>
                        <?php endif;?>

                        <?php if($this->pages):?>
                            <?php foreach ($this->pages as $item):?>
                                <li class="header-navigation-item">
                                    <a href="<?=$this->alias(['pages' => $item['alias']])?>" class="header-navigation-link link"><?=$item['name']?></a>

                                </li>
                            <?php endforeach;?>
                        <?php endif;?>

                    </ul>
                </nav>

            </div>

        </div>
    </div>

</header>

<?=$this->breadcrumbs?>

<div class="overlay"></div>
