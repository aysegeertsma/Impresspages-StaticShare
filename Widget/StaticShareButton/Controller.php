<?php
/**
 * Created by PhpStorm.
 * User: Ayse
 * Date: 15-5-2016
 * Time: 08:58
 */

namespace Plugin\StaticShare\Widget\StaticShareButton;


class Controller extends \Ip\WidgetController
{

    //
    //
    //
    // http://pinterest.com/pin/create/button/?url=&description=&media=
    //
    // [url]

    private static $types = null;

    public static function getTypes() {

        if(is_null(self::$types)) {
            self::$types = array(

                'facebook'=> array(
                    'name'=>'Facebook',
                    'url'=>'https://www.facebook.com/sharer/sharer.php?u=%1$s',
                    'fields'=>array('url'),
                ),
                'linkedin'=> array(
                    'name'=>'LinkedIn',
                    'url'=>'http://www.linkedin.com/shareArticle?url=%1$s&title=%2$s&summary=%3$s',
                    'fields'=>array('url','title','description'),
                ),
                'twitter'=> array(
                    'name'=>'Twitter',
                    'url'=>'https://twitter.com/intent/tweet?url=%1$s&text=%3$s',
                    'fields'=>array('url','description'),
                ),
                'googleplus'=> array(
                    'name'=>'Google +',
                    'url'=>'https://plus.google.com/share?url=%1$s',
                    'fields'=>array('url'),
                ),
                'hatena'=> array(
                    'name'=>'Hatena',
                    'url'=>'http://b.hatena.ne.jp/entry/%1$s',
                    'fields'=>array('url'),
                ),

            );
        }

        return self::$types;

    }

    public function getTitle()
    {
        return __('Share button', 'Static Share');
    }

    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {

        $types = self::getTypes();

        // set default
        if(!isset($data['type']) || !isset(self::$types[$data['type']])) {
            reset($types);
            $data['type']=key($types);
        };

        $urlPath = ipRequest()->getUrl();
        $data['target'] = 'share_goal_'.dechex(mt_rand(0, 256*6));
        $data['share_url'] = sprintf(
            self::$types[$data['type']]['url'],
            urlencode($urlPath),
            isset($data['title'])?urlencode($data['title']):'',
            isset($data['description'])?urlencode($data['description']):''
        );
        $data['class']=$data['type'];

        return parent::generateHtml($revisionId, $widgetId, $data, $skin); // TODO: Change the autogenerated stub
    }


}