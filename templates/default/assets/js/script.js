$(function () {
    var item = document.querySelectorAll('.place-item');

    item.forEach((item) => {

        let button = item.querySelector('.place-item-button');
        button.addEventListener('click', function () {

            window.coordX = button.dataset.coordsx;
            window.coordY = button.dataset.coordsy;

            console.log(window.coordX);
            console.log(window.coordY);

        });
    });

    let sortBlockTitle = document.querySelector('.catalog-sort__title')

    if (sortBlockTitle) {

        sortBlockTitle.addEventListener('click', () => {
            sortBlockTitle.nextElementSibling.classList.toggle('catalog-sort__inner-active')
        })

    }

    document.querySelectorAll('.offers-select').forEach(item => {

        item.addEventListener('click', () => {
            item.classList.toggle('opened')
        })

    })

    document.querySelectorAll('.offers-option').forEach(item => {

        item.addEventListener('click', () => {

            let offersSelect = item.closest('.offers-select')

            if (offersSelect) {

                let offersTitle = offersSelect.querySelector('[data-default-value]')

                if (offersTitle) {

                    let title = offersTitle.getAttribute('data-default-value')

                    setTimeout(() => {

                        if (item.hasAttribute('data-offers-added')) {

                            title = item.children[0].innerHTML

                        }

                        offersTitle.innerHTML = title

                    }, 0)

                }

            }

        })

    })

    document.querySelectorAll('[data-modal-mt]').forEach(item => {

        if (item.getAttribute('data-modal-mt')) {

            item.addEventListener('click', e => {

                e.preventDefault()

                let target = document.querySelector(`[data-modal-target="${item.getAttribute('data-modal-mt')}"]`)

                if (target) {

                    target.classList.add('visible')

                }

            })

        }

    })

    document.querySelectorAll('[data-modal-target]').forEach(item => {

        item.addEventListener('click', e => {

            if (e.target === item || e.target.closest('.close')) {

                item.classList.remove('visible')

            }

        })

    })

    let loginForms = document.querySelectorAll('[data-modal-target="lk"] form')

    if(loginForms.length){

        document.querySelectorAll('.registration-titles h2').forEach((item, index) => {

            item.addEventListener('click', () => {

                loginForms[index].style.display = 'block'
                loginForms[+!index].style.display = 'none'

            })

        })

    }

    document.querySelectorAll('input[type="tel"]').forEach(item => phoneValidate(item))

    function phoneValidate(item) {

        let char = ''

        item.addEventListener('input', function (e) {

            if (e.inputType === 'deleteContentBackward' || e.inputType === 'deleteContentForward') {

                return false;

            }

            if (e.data && e.data === '7' && char && char !== '+') {

                this.value = ''

                char = ''

            }

            char = (e.data && e.data === '+' && this.value.length === 1) ? e.data : ''

            this.value = this.value.replace(/\D/g, '')

            let start = 2

            if (/^[87]/.test(this.value)) {

                this.value = this.value.replace(/^[87]/, '+7')

            } else if (!/^\+/.test(this.value)) {

                this.value = '+' + this.value

            }

            let objectChars = {
                0: '(',
                4: ')',
                8: '-',
                11: '-'
            }

            if (/^\+7/.test(this.value)){

                let limit = 14

                for(let i in objectChars){

                    let j = +i

                    if (this.value[start + j] && this.value[start + j] !== objectChars[i])
                        this.value = this.value.substring(0, start + j) + objectChars[i] + this.value.substring(start + j)

                }

                if (this.value[start + limit])
                    this.value = this.value.substring(0, start + limit)

            }

        })

        item.dispatchEvent(new Event('input'))

    }

});
