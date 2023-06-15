<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.03.2019
 * Time: 13:13
 */

namespace core\admin\expansion;


use core\base\controller\Singleton;

class SettingsExpansion
{

    use Singleton;

    public function expansion(){
        $no_add = true;
        $no_delete = true;


        return compact('no_add', 'no_delete');
    }

}