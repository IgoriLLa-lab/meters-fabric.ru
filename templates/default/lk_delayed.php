<main class="main">
    <div class="container">
        <div class="wrapper wrapper_center">
            <h1 class="page-title"><?=$this->translateEl('Избранное')?></h1>
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
                    <div class="catalog-main">
                        <div class="catalog-main-items">

                            <?php
                                if(!empty($delayed)){

                                    foreach ($delayed as $item){

                                        $this->showGoods($item);

                                    }

                                }else{

                                    echo $this->translateEl('Список избранного пуст');

                                }
                            ?>

                            <script>

                                document.addEventListener('DOMContentLoaded', () => {

                                    document.querySelectorAll('[data-delayed]').forEach(item => {

                                        item.addEventListener('click', () => {

                                            setTimeout(() => {location.reload()}, 100)

                                        })

                                    })

                                })


                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?=$this->render(TEMPLATE . 'include/questionBlock')?>

</main>
