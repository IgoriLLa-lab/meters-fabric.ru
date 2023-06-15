<?php

namespace core\user\helpers;

use core\base\model\Crypt;

trait CartHelper
{

    private $emptyCartValue = 'Купить';

    private $fullCartValue = 'В корзине';

    private $cartStrictMode = true;

    protected $cart;

    private function checkCartStrictMode(){

        return $this->cartStrictMode ?: ($this->cartStrictMode = $this->set['cart_strict_mode'] ?? false);

    }

    protected function getElementId($data, $table = ''){

        $resultId = null;

        if($data){

            if(!is_array($data) && !is_object($data)){

                return is_numeric($data) ? $this->clearNum($data) : $this->clearStr($data);

            }

            !$table && $table = $this->model->goodsTable;

            if(in_array($table, $this->model->showTables())){

                $resultId = $data[$this->model->showColumns($table)['id_row']] ?? null;

            }

        }

        return $resultId;


    }


    protected function addToWishList($id = null, $action = null){

        $id = $this->clearNum($id ?? ($this->ajaxData['id'] ?? 0));

        $action = $this->clearStr($action ?? ($this->ajaxData['action'] ?? 'delayed'));

        if(!$id){

            return ['error' => 1, 'message' => $this->translateEl('Не балуйтесь карт хелпер 60')];

        }

        $where = [$this->model->showColumns($this->model->goodsTable)['id_row'] => $id];

        if(!empty($this->model->showColumns($this->model->goodsTable)['visible'])){

            $where['visible'] = 1;

        }

        $goods = $this->model->get($this->model->goodsTable, [
            'fields' => [$this->model->showColumns($this->model->goodsTable)['id_row']],
            'where' => $where,
            'single' => true
        ]);

        if(!$goods){

            return ['error' => 1, 'message' => $this->translateEl('Данного товара не найдено')];

        }

        $res['success'] = 1;

        if(!empty($_SESSION[$action][$id])){

            unset($_SESSION[$action][$id]);

            $res['success'] = 0;

        }else{

            $_SESSION[$action][$id] = true;

        }

        if(empty($_SESSION[$action])){

            $res['empty'] = 1;

        }

        return $res;

    }

    //избранное
    protected function wishList($data, $action = 'wishList'){

        if(!empty($data)){

            $id = $this->getElementId($data);

            if($id){

                $str = 'data-' . $action . '="' . $id . '"';

                if(!empty($_SESSION[$action][$id])){

                    $str .= ' data-' . $action . '-added';

                }

                echo $str;

            }

        }

    }

    protected function headerWishList($action = 'wishList'){

        $str = 'data-header-' . $action;

        if(!empty($_SESSION[$action])){

            $str .= ' data-' . $action . '-added';

        }

        echo $str;

    }

    protected function delayed($data){

        $this->wishList($data, 'delayed');

    }

    protected function headerDelayed(){

        $this->headerWishList('delayed');

    }

    protected function initCartValues($emptyCartValue = '', $fullCartValue = ''){

        $this->emptyCartValue = $emptyCartValue;

        $this->fullCartValue = $fullCartValue;

    }

    protected function setAddToCart($data, $emptyCartValue = false, $fullCartValue = false){

        $id = $this->getElementId($data);

        if($id){

            $emptyCartValue !== false && $this->emptyCartValue = $emptyCartValue;

            $fullCartValue !== false && $this->fullCartValue = $fullCartValue;

            return 'data-addToCart="' . $id . '"'.
                    ' data-notInCartValue="' . $this->emptyCartValue . '"' .
                    ' data-inCartValue="' . $this->fullCartValue . '"' .
                    (!empty($this->cart[$this->model->goodsTable][$id]) ? ' data-toCartAdded' : '') .
                    ($this->checkCartStrictMode() && !empty($data[$this->model->offersTable]) ? ' data-strict-mode' : '');

        }

    }

    protected function setAddToCartOneClick($data, $emptyCartValue = false, $fullCartValue = false){

        return $this->setAddToCart($data, $emptyCartValue, $fullCartValue) . ' data-one-click';

    }

    protected function setCartQuantity($data){

        $id = $this->getElementId($data);

        if($id){

            $value = !empty($this->cart[$this->model->goodsTable][$id]['qty']) &&
                        empty($this->cart[$this->model->goodsTable][$id]['qty'][$this->model->offersTable]) ?
                            $this->cart[$this->model->goodsTable][$id]['qty'] : 1.0;

            return 'data-quantity value="' . $value . '"' .
                    (!empty($this->cart[$this->model->goodsTable][$id]) ? ' data-toCartAdded="' . $value . '"'  : '');

        }

    }

    protected function setCartPriceCorrector($data){

        $id = $this->getElementId($data);

        if($id){

            $value = !empty($this->cart[$this->model->goodsTable][$id]['corrector']) ? $this->cart[$this->model->goodsTable][$id]['corrector'] : '';

            return 'data-priceCorrector="' . $value . '" value="' . $value . '"';

        }

    }

    protected function cartBtnText($data, $emptyCartValue = false, $fullCartValue = false){

        $id = $this->getElementId($data);

        if($id){

            $emptyCartValue !== false && $this->emptyCartValue = $emptyCartValue;

            $fullCartValue !== false && $this->fullCartValue = $fullCartValue;

            return empty($this->cart[$this->model->goodsTable][$id]) ? $this->emptyCartValue : $this->fullCartValue;

        }

    }

    protected function setGoodsPrice($data, $column = 'price'){

        if(isset($data[$column])){

            $attribute = preg_replace_callback('/_+(\w)/', function ($matches){

                return strtoupper($matches[1]);

                }, $column) ?? $column;

            $id = $this->getElementId($data);

            $str = 'data-' . $attribute . '="' . $data[$column] . '"';

            if($id && !empty($this->cart[$this->model->goodsTable][$id]['corrector'])){

                $str .= ' data-corrector="' . $this->cart[$this->model->goodsTable][$id]['corrector'] . '"';

            }

            return $str;

        }

    }

    protected function setGoodsOldPrice($data){

        return $this->setGoodsPrice($data, 'old_price');

    }

    protected function getGoodsPrice($data, $column = 'price'){

        if(isset($data[$column])){

            $id = $this->getElementId($data);

            $price = $this->clearNum($data[$column]);

            return !empty($this->cart[$this->model->goodsTable][$id]['corrector']) ?
                        $price * $this->cart[$this->model->goodsTable][$id]['corrector'] : $price;

        }

    }

    protected function getGoodsOldPrice($data){

        return $this->getGoodsPrice($data, 'old_price');

    }

    protected function setOffers($goodsItem, $data){

        if($goodsItem && $data){

            $goodsId = $this->getElementId($goodsItem);

            $id = $this->getElementId($data, $this->model->offersTable);

            if($id && $goodsId && is_array($data)){

                $offersDataStr = 'data-offers="' . $id . '"';

                !empty($data['old_price']) && $offersDataStr .= ' data-offers-oldPrice="' .$data['old_price'] . '"';

                !empty($data['price']) && $offersDataStr .= ' data-offers-price="' .$data['price'] . '"';

                !empty($this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['qty']) &&
                    $offersDataStr .= ' data-offers-quantity="' . $this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['qty'] . '"';

                !empty($this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['corrector']) &&
                    $offersDataStr .= ' data-corrector="' . $this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['corrector'] . '"';

                return $offersDataStr;

            }

        }

    }

    protected function addToCart($id = null, $qty = null, $offersId = null, $cartData = []){

        !$id && $id = $this->clearNum($this->ajaxData['id'] ?? 0);

        !$qty && $qty = $this->clearNum($this->ajaxData['qty'] ?? 1) ?: 1;

        !$offersId && $offersId = $this->clearNum($this->ajaxData['offersId'] ?? null);

        !$cartData && !empty($this->ajaxData['cartData']) && $cartData = $this->ajaxData['cartData'];

        !is_array($cartData) && $cartData = json_decode($cartData, true);

        if($cartData && is_array($cartData)){

            foreach ($cartData as $name => $item){

                $item = is_numeric($item) ? $this->clearNum($item) : $this->clearStr($item);

                if(empty($item)){

                    unset($cartData[$name]);

                    continue;

                }

                $cartData[$name] = $item;

            }

        }else{

            $cartData = [];

        }

        if(!$id){

            return ['succes' => 0, 'message' => $this->translateEl('Не балуйтесь карт хелпер ID 360')];

        }

        $where = [$this->model->showColumns($this->model->goodsTable)['id_row'] => $id];

        if(!empty($this->model->showColumns($this->model->goodsTable)['visible'])){

            $where['visible'] = 1;

        }

        $data = $this->model->getGoods([
            'where' => $where,
            'single' => true
        ], ...[false, false]);

        if(method_exists($this, 'beforeAddToCart')){

            $message = null;

            if($this->beforeAddToCart($data, $id, $qty, $offersId, $cartData, $message) === false){

                return ['success' => 0, 'message' => $message];

            }

        }

        if(empty($data)){

            return ['success' => 0, 'message' => $this->translateEl('Отсутствуют данные для добавления в корзину')];

        }

        if($this->checkCartStrictMode()){

            if(!empty($data[$this->model->offersTable]) && (empty($offersId) || empty($data[$this->model->offersTable][$offersId]))){

                return ['success' => 0, 'message' => $this->translateEl('Нельзя добавить данные в корзину без торгового предложения')];

            }

        }

        $res = $this->setCartData($data, $qty, $offersId, $cartData);

        if(empty($res) || empty($res[$this->model->goodsTable][$id])){

            return ['success' => 0, 'message' => $this->translateEl('Ошибка добавления товара в корзину')];

        }

        $res['current'] = $res[$this->model->goodsTable][$id];

        unset($res['current'][$this->model->offersTable]);

        if(!empty($offersId) && !empty($res[$this->model->goodsTable][$id][$this->model->offersTable][$offersId])){

            $res['current']['offers'] = $res[$this->model->goodsTable][$id][$this->model->offersTable][$offersId];

        }

        return ['success' => 1, 'message' => $this->translateEl('Товар добавлен в корзину'), 'data' => $res];

    }

    protected function setCartData($data, float $qty = 1.0, $offersId = null, $cartData = []){

        $id = $this->getElementId($data);

        $cart = &$this->getCart();

        if(!isset($cart[$this->model->goodsTable][$id])){

            $cart[$this->model->goodsTable][$id] = [];

        }

        if(!empty($offersId) && !empty($data[$this->model->offersTable][$offersId])){

            $cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId] = [];

            $cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId]['qty'] = $qty;

            if($cartData){

                $cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId] = array_merge($cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId], $cartData);

            }

        }else{

           $cart[$this->model->goodsTable][$id]['qty'] = $qty;

           if($cartData){

               $cart[$this->model->goodsTable][$id] = array_merge($cart[$this->model->goodsTable][$id], $cartData);

           }else{

               foreach ($cart[$this->model->goodsTable][$id] as $key => $item){

                   if($key !== 'qty' && $key !== $this->model->offersTable){

                       unset($cart[$this->model->goodsTable][$id][$key]);

                   }

               }

           }

        }

        $this->updateCart();

        return $this->getCartData(true);

    }

    public function deleteCartData($id, $offersId){

        $id = $this->clearNum($id);

        if ($offersId){

            $offersId = $this->clearNum($offersId);

        }

        if ($id){

            $cart = &$this->getCart();

            if (empty($cart[$this->model->goodsTable])){

                $this->clearCart();

                return false;

            }

            if (!empty($offersId)){

                if (!empty($cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId])){

                    unset($cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId]);

                    if (empty($cart[$this->model->goodsTable][$id][$this->model->offersTable] &&
                    empty($cart[$this->model->goodsTable][$id]['qty']))){

                        unset($cart[$this->model->goodsTable][$id]);

                    }

                }

            }else{

                unset($cart[$this->model->goodsTable][$id]);

            }

            $this->updateCart();

            $this->getCartData(true);

        }

    }

    public function getCartData($cartChanged = false){

        if(!empty($this->cart) && !$cartChanged){

            return $this->cart;

        }

        $cart = &$this->getCart();

        if(empty($cart[$this->model->goodsTable])){

            $this->clearCart();

            return false;

        }

        $goods = $this->model->getGoods([
            'where' => [$this->model->showColumns($this->model->goodsTable)['id_row'] => array_keys($cart[$this->model->goodsTable])]
        ], ...[false, false]);

        $this->cart = [
            $this->model->goodsTable => []
        ];

        if($goods){

            $changeCart = false;

            foreach ($cart[$this->model->goodsTable] as $key => $item){

                if(empty($goods[$key])){

                    unset($cart[$this->model->goodsTable][$key]);

                    $changeCart = true;

                    continue;

                }

                $this->cart[$this->model->goodsTable][$key] = $this->setElement($goods[$key], $item);

                if(!empty($item[$this->model->offersTable])){

                    if(empty($goods[$key][$this->model->offersTable])){

                        unset($cart[$this->model->goodsTable][$key]);

                        $changeCart = true;

                        continue;

                    }

                    foreach ($item[$this->model->offersTable] as $k => $offer){

                        if(empty($goods[$key][$this->model->offersTable][$k])){

                            unset($cart[$this->model->goodsTable][$key][$this->model->offersTable][$k]);

                            $changeCart = true;

                            continue;

                        }

                        $this->cart[$this->model->goodsTable][$key][$this->model->offersTable][$k] = $this->setElement($goods[$key][$this->model->offersTable][$k], $offer);

                    }

                }

            }

            if($changeCart){

                $this->updateCart();

            }

        }else{

            $this->clearCart();

        }

        return $this->totalSum();

    }

    protected function setElement($data, $item){

        if(isset($data) && isset($item)){

            unset($data[$this->model->offersTable], $item[$this->model->offersTable]);

            return array_merge($data, $item);

        }

        return [];

    }

    private function totalSum(){

        if(empty($this->cart) || empty($this->cart[$this->model->goodsTable])){

            $this->cart = [];

            $this->clearCart();

            return false;

        }

        $this->cart['total_qty'] = $this->cart['total_sum'] = $this->cart['total_old_sum'] = 0;

        foreach ($this->cart[$this->model->goodsTable] as $key => $item){

            $this->cart[$this->model->goodsTable][$key]['total_sum'] = $this->cart[$this->model->goodsTable][$key]['total_old_sum'] = $this->cart[$this->model->goodsTable][$key]['total_qty'] = 0;

            if(!empty($item['qty'])){

                $this->cart[$this->model->goodsTable][$key]['total_qty'] = $item['qty'];

                $this->cart[$this->model->goodsTable][$key]['total_sum'] = round($item['qty'] * $item['price'], 2);

                $this->cart[$this->model->goodsTable][$key]['total_old_sum'] = round($item['qty'] * ($item['old_price'] ?? 0), 2);

            }

            if(!empty($item[$this->model->offersTable])){

                foreach ($item[$this->model->offersTable] as $offerKey => $offer){

                    $totalOfferSum = round($offer['qty'] * $offer['price'], 2);

                    $totalOfferOldSum = round($offer['qty'] * ($offer['old_price'] ?? 0), 2);

                    $this->cart[$this->model->goodsTable][$key]['total_qty'] += $offer['qty'];

                    $this->cart[$this->model->goodsTable][$key]['total_sum'] += $totalOfferSum;

                    $this->cart[$this->model->goodsTable][$key]['total_old_sum'] += $totalOfferOldSum;

                    $this->cart[$this->model->goodsTable][$key][$this->model->offersTable][$offerKey]['total_sum'] = $totalOfferSum;

                    $this->cart[$this->model->goodsTable][$key][$this->model->offersTable][$offerKey]['total_old_sum'] = $totalOfferOldSum;

                }

            }

            $this->cart['total_qty'] += $this->cart[$this->model->goodsTable][$key]['total_qty'];

            $this->cart['total_sum'] += $this->cart[$this->model->goodsTable][$key]['total_sum'];

            $this->cart['total_old_sum'] += $this->cart[$this->model->goodsTable][$key]['total_old_sum'];


        }

        return $this->cart;

    }

    public function updateCart(){

        $cart = &$this->getCart();

        if(empty($cart[$this->model->goodsTable])){

            $this->clearCart();

            return false;

        }

        if(defined('CART') && strtolower(CART) === 'cookie'){

            setcookie('cart', Crypt::instance()->encrypt(json_encode($cart)), time() + 60 * 60 * 24 * CART_COOKIE_TIME, PATH);

        }

        return true;

    }

    public function clearCart(){

        unset($_SESSION['cart'], $_COOKIE['cart']);

        if(defined('CART') && strtolower(CART) === 'cookie'){

            setcookie('cart', '', 1, PATH);

        }

        $this->cart = [];

    }

    public function &getCart(){

        if(!defined('CART') || strtolower(CART) !== 'cookie'){

            if(!isset($_SESSION['cart'])){

                $_SESSION['cart'] = [];

            }

            return $_SESSION['cart'];

        }else{

            if(empty($_COOKIE['cart'])){

                $_COOKIE['cart'] = [];

            }else{

                $_COOKIE['cart'] = is_string($_COOKIE['cart']) ? json_decode(Crypt::instance()->decrypt($_COOKIE['cart']), true) : $_COOKIE['cart'];

            }

            return $_COOKIE['cart'];

        }

    }

}