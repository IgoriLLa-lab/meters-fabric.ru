<main class="main">

    <div class="container">
        <div class="wrapper wrapper_center">
            <h1 class="page-title"><?=$this->translateEl('Мои заказы')?></h1>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="wrapper_internal wrapper_lk">

                <?=$this->render(TEMPLATE . 'include/lkMenu', [
                    'orders' => $orders ?? null,
                    'delayed' => $delayed ?? null
                ])?>

                <div class="cabinet cabinet_switches lk-body">

                    <div class="switchTitles second">

                        <?php if(!empty($currentOrder)):?>
                            <div class="active" data-innerswitch="order_switch1"><?=$this->translateEl('Текущий заказ')?></div>
                        <?php endif;?>
                        <?php if(!empty($orders)):?>
                            <div data-innerswitch="order_switch2" class="<?=empty($currentOrder) ? 'active' : ''?>"><?=$this->translateEl('Все заказы')?> (<?=count($orders)?>)</div>
                        <?php endif;?>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {

                            let innerSwitch = document.querySelectorAll('[data-innerswitch]')

                            let innerSwitched = document.querySelectorAll('[data-innerswitched]')

                            if(innerSwitched.length){

                                innerSwitch.forEach((item, index) => {

                                    item.addEventListener('click', () => {

                                        if(typeof innerSwitched[index] !== 'undefined' && !item.classList.contains('active')){

                                            innerSwitch.forEach(el => el.classList.remove('active'))
                                            innerSwitched.forEach(el => el.classList.remove('active'))

                                            item.classList.add('active')
                                            innerSwitched[index].classList.add('active')

                                        }

                                    })

                                })

                            }

                        })
                    </script>

                    <?php if(!empty($currentOrder)):?>
                        <div class="active" data-innerswitched="order_switch1">
                            <div class="order_title">
                                <h3>
                                    <?=$this->translateEl('Заказ')?> №<?=$currentOrder['id']?>
                                </h3>
                                <a style="color: black" href="<?=$this->alias(['lk' => 'restate', 'id' => $currentOrder['id']])?>" class="btn"><?=$this->translateEl('Повторить заказ')?></a>
                            </div>
                            <ul class="info_panel">
                                <li>
                                    <span class="name">
                                        <?=$this->translateEl('Всего')?>:
                                    </span>
                                    <?php $countGoods = count($currentOrder['join']['orders_goods'] ?? [])?>
                                    <span class="info">
                                        <?=$this->wordsForCounter($countGoods, ['Товаров', 'Товара', 'Товара'])?>
                                    </span>
                                </li>
                                <li>
                                    <span class="name">
                                        <?=$this->translateEl('Стоимость')?>:
                                    </span>
                                    <span class="info">
                                        <?=$currentOrder['total_sum']?> ₽
                                    </span>
                                </li>
                                <li>
                                    <span class="name">
                                        <?=$this->translateEl('Дата формирования')?>:
                                    </span>
                                    <span class="info">
                                        <?=$currentOrder['date']?>
                                    </span>
                                </li>
                                <li>
                                    <span class="name">
                                        <?=$this->translateEl('Статус')?>:
                                    </span>
                                    <span class="info">
                                         <?=$currentOrder['join']['orders_statuses']['name']?>
                                    </span>
                                </li>
                            </ul>
                            <div class="table_wrap">
                                <table>
                                    <thead>
                                    <tr>
                                        <th><?=$this->translateEl('Артикул')?></th>
                                        <th><?=$this->translateEl('Наименование')?></th>
                                        <th><?=$this->translateEl('Количество')?></th>
                                        <th><?=$this->translateEl('Стоимость')?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($currentOrder['join']['orders_goods'] as $item):?>
                                        <tr>
                                            <td><?=$item['article'] ?? $item['id']?></td>
                                            <td>
                                                <div class="flex_wrap">
                                                    <b>
                                                        <?=$item['name']?>
                                                    </b>
                                                </div>
                                            </td>
                                            <td>
                                                <?=$item['qty']?> <?=$item['unit'] ?? 'м.(шт.)'?>
                                            </td>
                                            <td>
                                                <?=$item['total_sum']?> ₽
                                            </td>
                                        </tr>
                                    <?php endforeach;?>

                                    </tbody>
                                </table>
                            </div>

                            <div class="table_total">
                                <div class="delivery">
                                    <div>
                                        <b>
                                            <?=$this->translateEl('Способ оплаты')?>:
                                        </b>
                                        <span>
                                            <?=$currentOrder['join']['payments']['name']?>
                                        </span>
                                    </div>
                                    <div>
                                        <b>
                                            <?=$this->translateEl('Способ получения')?>:
                                        </b>
                                        <span>
                                             <?=$currentOrder['join']['delivery']['name']?>
                                        </span>
                                    </div>
                                </div>
                                <div class="totally">
                                    <b><?=$this->translateEl('Итоговая стоимость')?>:</b>
                                    <?=$currentOrder['total_sum']?> ₽
                                </div>
                            </div>

                        </div>
                    <?php endif;?>

                    <?php if(!empty($orders)):?>
                        <div data-innerswitched="order_switch2" class="<?=empty($currentOrder) ? 'active' : ''?>">
                            <div class="table_wrap">
                                <table class="second_table">
                                    <thead>
                                    <tr>
                                        <th><?=$this->translateEl('Номер')?></th>
                                        <th><?=$this->translateEl('Дата формирования')?></th>
                                        <th><?=$this->translateEl('Стоимость')?></th>
                                        <th><?=$this->translateEl('Статус')?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($orders as $item):?>
                                        <tr>
                                            <td>№<?=$item['id']?></td>
                                            <td>
                                                <?=$item['date']?>
                                            </td>
                                            <td>
                                                <?=$item['total_sum']?> ₽
                                            </td>
                                            <td>
                                                <span class="status "><?=$item['join']['orders_statuses']['name']?></span>
                                            </td>
                                            <td>
                                                <a href="<?=$this->alias(['lk' => 'orders', 'current-order' => $item['id']])?>">
                                                    <?=$this->translateEl('Подробнее')?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif;?>


                </div>

            </div>
        </div>
    </section>

    <?=$this->render(TEMPLATE . 'include/questionBlock')?>

</main>