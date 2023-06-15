<a href="<?=preg_replace('/\D/', '', $this->set['phone'])?>" class="question-button button button_dark button_big"
   onclick="event.preventDefault(); location.href = (/android|webos|iphone|ipad|ipod|blackberry|eimobile|opera mini/i.test(navigator.userAgent) ? 'whatsapp://send?phone=' : 'https://web.whatsapp.com/send/?phone=') + this.getAttribute('href')">
    <?=$this->translateEl('Написать в Whatsapp')?>
</a>
