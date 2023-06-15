
function changeFilters(filters = null){

    if(!filters){

        filters = document.querySelectorAll('[data-filters]')

    }

    if(filters.length){

        let baseUrl = location.href

        filters.forEach(filtersBlock => {

            let filterItems = filtersBlock.querySelectorAll('input[name]')

            if(filterItems.length){

                let str = location.search

                filterItems.forEach(item => {

                    ['change'].forEach(event => {

                        item.addEventListener(event, () => {

                            str = str.replace(/&?page=\d+/, '').replaceGetParameters(item.name, item.value)

                            history.pushState(null, null, str ? '?' + str : baseUrl)

                        })

                    })

                })

            }

        })

    }

}

addEventListener('popstate', () => {
    location.reload()
})

document.querySelectorAll('form[data-filters]').forEach(form => form.onsubmit = e => e.preventDefault())

document.querySelectorAll('[data-filters] input[type="submit"], [data-filters] input[type="reset"]').forEach(item => {

    item.addEventListener('click', () => {

        if(item.type === 'submit'){

            location.reload()

        }else{

            let href = location.href.split(/\?/)

            location.href = href ? href[0] : location.href

        }

    })


})

changeFilters()

const addToWishList = (() => {

    async function _addToWishList(e){

        e.preventDefault()

        let action = this.hasAttribute('data-wishList') ? 'wishList' : 'delayed'

        let id = +this.getAttribute(`data-${action}`)

        if(!isNaN(id) && id){

            let res = await Ajax({
                data:{
                    id: id,
                    ajax: action
                }
            })

            try{

                res = JSON.parse(res)

                if(typeof res.error !== 'undefined' || typeof res.success === 'undefined'){

                    throw new Error(res.error || null)

                }

                let success = !!res.success

                if(success){

                    this.setAttribute(`data-${action}-added`, true)

                }else{

                    this.removeAttribute(`data-${action}-added`)

                }

                if(typeof res.empty !== 'undefined'){

                    document.querySelectorAll(`[data-header-${action}]`).forEach(item => {

                        item.removeAttribute(`data-${action}-added`)

                    })

                }else{

                    document.querySelectorAll(`[data-header-${action}]`).forEach(item => {

                        item.setAttribute(`data-${action}-added`, true)

                    })

                }

            }catch (e){

                console.error(e)

                if(typeof e.message !== 'undefined'){

                    alert(e.message)

                }

            }

            console.log(res)

        }

    }

    return function (){

        document.querySelectorAll('[data-wishList], [data-delayed]').forEach(item => {

            item.removeEventListener('click', _addToWishList)

            item.addEventListener('click', _addToWishList)

        })

    }

})()

addToWishList()

const addToCart = (() => {

    async function _addToCart(e){

        e.preventDefault();

        if(this.hasAttribute('data-strict-mode') && !this.getAttribute('data-offers-id')){

            alert('Выберите торговое предложение')

            return false

        }

        let cart = {}

        cart.cartData = {}

        cart.id = +this.getAttribute('data-addToCart')

        if(isNaN(cart.id) || !cart.id){

            console.error('Некорректный идентификатор товара');

            return false;

        }

        if(this.hasAttribute('data-offers-id')){

            cart.offersId = +this.getAttribute('data-offers-id')

            if(isNaN(cart.offersId) || !cart.offersId){

                console.error('Некорректный идентификатор торгового предложения');

                return false;

            }

        }

        let quantity = this.productContainer.querySelector('[data-quantity]')

        cart.qty = 1

        if(quantity){

            cart.qty = +quantity.value || 1

        }

        /*Дополнительные данные для корзины*/

        let priceCorrector = this.productContainer.querySelector('[data-priceCorrector]')

        if(priceCorrector && priceCorrector.value){

            cart.cartData.corrector = priceCorrector.value

        }

        if(cart.cartData){

            cart.cartData = JSON.stringify(cart.cartData)

        }

        /*Дополнительные данные для корзины*/

        cart.ajax = 'add_to_cart'

        let res = await Ajax({
            data: cart
        })

        try{

            res = JSON.parse(res)

            console.log(res)

            if(typeof res.success === 'undefined' || !res.success){

                throw new Error(res.message || 'Ошибка добавления товара в корзину')

            }

            if (e.isTrusted && /\/cart(\/|$)/.test(location.pathname)){

                location.href = '/cart/'

            }

            if(this.hasAttribute('data-one-click')){

                location.href = '/cart/';

            }

            if(this.getAttribute('data-inCartValue')){

                this.innerHTML = this.getAttribute('data-inCartValue')

            }

            this.setAttribute('data-toCartAdded', true)

            this.productContainer.querySelectorAll('[data-quantity]').forEach(item => item.setAttribute('data-toCartAdded', true))

            document.querySelectorAll('[data-header-cart]').forEach(item => item.setAttribute('data-headerToCartAdded', true))

            if(typeof res.data !== 'undefined'){

                if(typeof res.data['total_sum'] !== 'undefined'){

                    document.querySelectorAll('[data-totalSum]').forEach(item => item.innerHTML = res.data['total_sum'])

                }

                if(typeof res.data['total_old_sum'] !== 'undefined'){

                    document.querySelectorAll('[data-totalOldSum]').forEach(item => item.innerHTML = res.data['total_old_sum'])

                }

                if(typeof res.data['total_qty'] !== 'undefined'){

                    document.querySelectorAll('[data-totalQty]').forEach(item => item.innerHTML = res.data['total_qty'])

                }

                if(typeof cart.offersId !== 'undefined'){

                    this.productContainer.querySelectorAll(`[data-offers="${cart.offersId}"]`).forEach(item => {

                        item.setAttribute('data-offers-quantity', cart.qty)

                        if(priceCorrector && priceCorrector.value){

                            item.setAttribute('data-corrector', priceCorrector.value)

                        }

                    })

                }

            }

        }catch (e){

            console.error(e)

        }

    }

    function _changeQty(e){

        e.preventDefault()

        if(e.type !== 'click'){

            this.value = _setCorrectFloatValue(this.value)

            let quantities = this.productContainer.querySelectorAll('[data-quantity]')

            if(quantities.length > 1){

                quantities.forEach(item => {

                    if(item !== this){

                        item.value = this.value

                    }

                })

            }

            if(e.type === 'change'){

                _triggerAddToCart(this)

            }

        }

    }

    function _changePrice(e){

        e.preventDefault()

        let value = this.value = _setCorrectFloatValue(this.value)

        if(!value){

            value = 1

        }

        let addToCart = this.productContainer.querySelector('[data-addToCart]')

        let price = null

        let oldPrice = null

        if(addToCart.hasAttribute('data-offers-id')){

            let offer = this.productContainer.querySelector(`[data-offers="${addToCart.getAttribute('data-offers-id')}"]`)

            if(offer){

                price = offer.getAttribute('data-offers-price')

                oldPrice = offer.getAttribute('data-offers-oldPrice')

            }

        }else{

            let priceElement = this.productContainer.querySelector('[data-price]')

            let oldPriceElement = this.productContainer.querySelector('[data-oldPrice]')

            if(priceElement){

                price = priceElement.getAttribute('data-price')

            }

            if(oldPriceElement){

                oldPrice = oldPriceElement.getAttribute('data-oldPrice')

            }

        }

        if(price){

            this.productContainer.querySelectorAll('[data-price]').forEach(item => item.innerHTML = parseFloat((price * value).toFixed(2)))

        }

        if(oldPrice){

            this.productContainer.querySelectorAll('[data-oldPrice]').forEach(item => item.innerHTML = parseFloat((oldPrice * value).toFixed(2)))

        }

        if(e.type === 'change'){

            _triggerAddToCart(this)

        }

    }

    function _chooseOffers(e){

        e.preventDefault()

        if (e.isTrusted && /\/cart(\/|$)/.test(location.pathname) && this.getAttribute('data-offers-quantity')){

            return false

        }

        let id = +this.getAttribute('data-offers')

        if(!isNaN(id) && id){

            let goodsPrice = this.productContainer.querySelectorAll('[data-price]')

            let goodsOldPrice = this.productContainer.querySelectorAll('[data-oldPrice]')

            let addToCart = this.productContainer.querySelectorAll('[data-addToCart]')

            let quantities = this.productContainer.querySelectorAll('[data-quantity]')

            let priceCorrector = this.productContainer.querySelectorAll('[data-priceCorrector]')

            if(this.hasAttribute('data-offers-price')){

                goodsPrice.forEach(item => item.innerHTML = !this.hasAttribute('data-offers-added') ?
                    this.getAttribute('data-offers-price') : item.getAttribute('data-price'))

            }

            if(this.hasAttribute('data-offers-oldPrice')){

                goodsOldPrice.forEach(item => item.innerHTML = !this.hasAttribute('data-offers-added') ?
                    this.getAttribute('data-offers-oldPrice') : item.getAttribute('data-oldPrice'))

            }

            if(this.hasAttribute('data-offers-added')){

                addToCart.forEach(item => item.removeAttribute('data-offers-id'))

                quantities.forEach(item => item.value = item.getAttribute('data-toCartAdded') || 1)

                this.removeAttribute('data-offers-added')

            }else{

                addToCart.forEach(item => item.setAttribute('data-offers-id', id))

                this.productContainer.querySelectorAll('[data-offers]').forEach(item => item.removeAttribute('data-offers-added'))

                this.setAttribute('data-offers-added', true)

                quantities.forEach(item => item.value = this.getAttribute('data-offers-quantity') || 1)

            }

            if(priceCorrector.length){

                if(this.getAttribute('data-corrector')){

                    priceCorrector.forEach(item => item.value = _setCorrectFloatValue(this.getAttribute('data-corrector')))

                }

                priceCorrector[0].dispatchEvent(new Event('click'))

            }


        }

    }

    function _incDecQuantity(e){

        e.preventDefault();

        let quantities = this.productContainer.querySelectorAll('[data-quantity]')

        if(quantities.length){

            let value = null

            quantities.forEach(item => {

                if(value === null){

                    value = +_setCorrectFloatValue(item.value)

                    if(isNaN(value)){

                        value = 0

                    }

                }

                if(this.hasAttribute('data-quantityPlus')){

                    item.value = ++value

                }else{

                    item.value = value <= 1.0 ? 1.0 : --value

                }

            })

            _triggerAddToCart(this)

        }

    }

    function _triggerAddToCart(_this){

        let addToCart = _this.productContainer.querySelector('[data-addToCart][data-toCartAdded]')

        if(addToCart){

            if(addToCart.hasAttribute('data-strict-mode')){

                let currentOffer = addToCart.getAttribute('data-offers-id')

                if(!currentOffer){

                    return false

                }

                currentOffer = _this.productContainer.querySelector(`[data-offers="${currentOffer}"][data-offers-added][data-offers-quantity]`)

                console.log(_this)

                if(!currentOffer){

                    return false

                }

            }

            addToCart.dispatchEvent(new Event('click'))

        }

    }

    function _setCorrectFloatValue(value){

        return (value + '').replace(/[^\d\.,]/g, '').replace(/,/, '.').
                 replace(/^\./, '0.').replace(/\.{2,}/, '.').
                    replace(/(^\d+\.\d{2}).+/, '$1')

    }

    return function (){

        document.querySelectorAll('[data-addToCart], [data-quantity], [data-offers], [data-quantityPlus], [data-quantityMinus], [data-priceCorrector]').forEach(item => {

            switch (true){

                case item.hasAttribute('data-addToCart'):

                    item.removeEventListener('click', _addToCart)

                    item.addEventListener('click', _addToCart)

                    break;

                case item.hasAttribute('data-quantity'):

                    ['click', 'input', 'change'].forEach(event => {

                        item.removeEventListener(event, _changeQty)

                        item.addEventListener(event, _changeQty)

                    })

                    break;

                case item.hasAttribute('data-priceCorrector'):

                    ['click', 'input', 'change'].forEach(event => {

                        item.removeEventListener(event, _changePrice)

                        item.addEventListener(event, _changePrice)

                    })

                    break;

                case item.hasAttribute('data-offers'):

                    item.removeEventListener('click', _chooseOffers)

                    item.addEventListener('click', _chooseOffers)

                    break;

                default:

                    item.removeEventListener('click', _incDecQuantity)

                    item.addEventListener('click', _incDecQuantity)

                    break;

            }

            item.productContainer = item.closest('[data-productContainer]') || document

        })

    }

})()

addToCart()