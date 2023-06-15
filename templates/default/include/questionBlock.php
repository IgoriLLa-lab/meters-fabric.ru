<section class="question section">
    <div class="container">

            <div class="question-connection">
                <div class="question-title question-title_connection">
                    <?=$this->translateEl('НУЖНА ПОМОЩЬ С ЗАКАЗОМ')?>?
                </div>

                <?=$this->render(TEMPLATE . 'include/whatsappBtn')?>

                <div class="question-connection-bottom">
                    <a href="tel:<?=preg_replace('/[^\d+]/', '', $this->set['phone'])?>" class="phone question-connection-item question-phone">
                        <span class="icon-phone question-phone-icon"></span><span class="question-connection-text"><?=$this->set['phone']?></span>
                    </a>
                    <a href="mailto:<?=$this->set['email']?>" class="mail question-connection-item question-mail">
                        <span class="icon-email question-mail-icon"></span><span class="question-connection-text"><?=$this->translateEl('Свяжитесь с нами')?></span>
                    </a>
                </div>
            </div>

                </form>

        </div>
    </div>
</section>

