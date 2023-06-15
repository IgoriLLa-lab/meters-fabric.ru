<?php

defined('VG_ACCESS') or die('Access denied');

const TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/view/';
const UPLOAD_DIR = 'userfiles/';

const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = 'SDF*999HT5DrsDFsdf098098*&&^$%&^sdf54F873fDSFSF-SDF*9879790SDDFsdf098098*&&^$%&^sdfsddsfsfDSFdd511';
const COOKIE_TIME = 0;
const BLOCK_TIME = 3;

const END_SLASH = '/';

const _QTY = 15;
const QTY_LINKS = 3;

const CART = 'cookie';
const CART_COOKIE_TIME = 4; //значение в днях

const BASE_CSS_JS = [
    'styles' => [],
    'scripts' => ['core/base/assets/js/frameworkfunctions.js', 'core/base/assets/js/showMessage.js']
];

const ADMIN_CSS_JS = [
    'styles' => ['css/choices.min.css', 'css/decoration.css', 'css/loader.css', 'css/crop_image.css', 'css/style.css'],
    'scripts' => [
        'js/app.js',
        'js/choices.min.js',
        'js/jsColorPicker.min.js',
        'js/frameworkfunctions.js',
        'js/main.js',
        'js/showMessage.js',
        'js/tinymce/tinymce.min.js',
        'js/tinymce/tinymce_init.js',
        'js/crop-image.js'
    ]
];

const USER_CSS_JS = [
    'styles' => [
        'assets/css/normalize.min.css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css',
        'assets/icons/style.css', 'assets/libs/swiper/swiper-bundle.min.css',
        'assets/css/simplebar.css', 'assets/css/style.css', 'assets/css/index.css', 'assets/css/corrector.css',
        'assets/css/search.css'
    ],
    'scripts' => [
        'assets/libs/jquery-3.4.1.min.js', 'https://code.jquery.com/jquery-3.4.1.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js',
        'assets/libs/simplebar.min.js', 'assets/libs/swiper/swiper-bundle.min.js',
        'https://unpkg.com/swiper/swiper-bundle.min.js', 'assets/libs/jquery.inputmask.bundle.min.js',
        'assets/libs/parsley.min.js', 'assets/libs/parsley-loc.js', 'assets/js/vendor.js',
        'assets/js/app.js', 'assets/js/script.js', 'assets/js/calendar.js', 'assets/js/shop.js',
        'assets/js/send_form.js', 'assets/js/search.js'
    ]
];

use core\base\exceptions\RouteException;

function autoloadMainClasses($class_name){

    $file_name = str_replace('\\', '/', $class_name);

    @include_once $_SERVER['DOCUMENT_ROOT'] . PATH . $file_name . '.php';

}

spl_autoload_register('autoloadMainClasses');