<?php
/**
 * Created by PhpStorm.
 * User: Ayse
 * Date: 15-5-2016
 * Time: 10:10
 */

namespace Plugin\StaticShare;


class Event
{

    public static function ipBeforeController() {

        ipAddCss('assets/css/static-share-button.css');

    }


}