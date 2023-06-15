<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 31.01.2019
 * Time: 16:01
 */

namespace core\base\settings;


use core\base\controller\Singleton;

class Settings
{

    use BaseSettings;

    private $routes = [
        'user' => [
            'routes' => [
                'product' => 'goods',
                'special-information' => 'information/specialInformation',
                'client-services' => 'information/clientServices',
                'services' => 'information/services',
                'pages' => 'information/pages',
            ]
        ]
    ];

    private $projectTables = [
        'catalog' => ['name' => 'Каталог'],
        'goods' => ['name' => 'Товары'],
        'filters' => ['name' => 'Фильтры'],
        'offers' => ['name' => 'Дополнительные предложения к товару'],
        'sales' => ['name' => 'Акции'],
        'socials' => ['name' => 'Социальные сети'],
        'pages' => ['name' => 'Страницы'],
        'services' => ['name' => 'Услуги'],
        'client_services' => ['name' => 'Обслуживание клиентов'],
        'settings' => ['name' => 'Настройки системы'],
        'translate_elements' => ['name' => 'Название элементов страниц'],
        'special_information' => ['name' => 'Специальная информация'],
        'test' => ['name' => 'TEST']
    ];

    private $templateArr = [
        'text' => [
            'external_alias_faq', 'first_title', 'first_short_content', 'first_external_alias',
            'second_title', 'second_short_content', 'second_external_alias', 'video_title',
            'global_sale_period', 'dop_content_title', 'quantity'
        ],
        'text_disabled' => [],
        'date' => [],
        'textarea' => ['video_text', 'video_content', 'global_sale', 'delivery_information', 'dop_content'],
        'file' => [],
        'gallery_img' => ['payments_gallery_img'],
        'img' => ['first_img', 'second_img', 'dop_img', 'video_img'],
        'radio' => ['show_full_screen', 'first_inner_text', 'second_inner_text', 'template_type'],
        'select' => ['catalog_id'],
        'checkboxlist' => ['goods', 'sales', 'filters'],
        'colorpicker' => [],
        'gps' => [],
        'addedlist' => ['tizers_links'],
        'addedlist_img' => ['tizers_recomendations'],
    ];

    private $translate = [
        'catalog_id' => ['Раздел каталога', 'Обязательно указывать только для корневого раздела'],
        'goods' => ['Товары'],
        'show_full_screen' => ['Показывать на всю  ширину экрана'],
        'tizers_links' => ['Дополнительные пункты в верхнем меню', 'Выводятся только из категорий перволго уровня'],
        'tizers_recomendations' => ['Рекомендации в верхнем меню', 'Выводятся только из категорий перволго уровня'],
        'external_alias_faq' => ['Ссылка на страницу помощи'],
        'first_title' => ['Заголовок первой акции'],
        'second_title' => ['Заголовок второй акции'],
        'first_short_content' => ['Краткое описание первой акции'],
        'second_short_content' => ['Краткое описание второй акции'],
        'first_external_alias' => ['Ссылка на страницу первой акции'],
        'second_external_alias' => ['Ссылка на страницу второй акции'],
        'first_img' => ['Изображение первой акции'],
        'second_img' => ['Изображение второй акции'],
        'first_inner_text' => ['Текст внутри изображения первой акции'],
        'second_inner_text' => ['Текст внутри изображения второй акции'],
        'dop_img' => ['Дополнительное изображение'],
        'video_img' => ['Изображение для блока видео'],
        'video_title' => ['Заголовок блока видео'],
        'video_text' => ['Описание блока видео'],
        'video_content' => ['Ссылка на источник видео'],
        'payments_gallery_img' => ['Галерея систем оплаты'],
        'template_type' => ['Тип отображения'],
        'global_sale_period' => ['Период горячей акции'],
        'global_sale' => ['Горячая акция'],
        'delivery_information' => ['Информация о возврате и доставке товара'],
        'dop_content_title' => ['Заголовок дополнительной информации о товаре'],
        'dop_content' => ['Дополнительная информация о товаре'],
        'quantity' => ['Количество доп товара']
    ];

    private $radio = [
        'first_inner_text' => ['Нет', 'Да', 'default' => 'Нет'],
        'second_inner_text' => ['Нет', 'Да', 'default' => 'Нет'],
        'template_type' => ['Чекбоксы', 'Плитка', 'С блоком цвета', 'default' => 'Чекбоксы'],
    ];

    private $blockNeedle = [
//        'grid' => [],
//        'full' => []
    ];

    private $deepLevel = [
        'catalog' => [
            'parent_id' => 3
        ],
        'sales' => [
            'catalog_id' => 0
        ],
        'filters' => [
            'parent_id' => 0
        ]
    ];

    private $rootItems = [
        'tables' => ['sales']
    ];

    private $manyToMany = [
        'sales_goods' => ['sales', 'goods',],
        'catalog_goods' => ['catalog', 'goods',],
        'goods_goods' => ['goods', 'goods',],
    ];

}