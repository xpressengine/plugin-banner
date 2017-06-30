<?php
namespace Xpressengine\Plugins\Banner;

use Xpressengine\Skin\GenericSkin;

class BannerWidgetSkin extends GenericSkin
{
    public static function getBannerInfo($key = null)
    {
        if($key) {
            $key = '.'.$key;
        }
        return static::info('banner'.$key);
    }
}
