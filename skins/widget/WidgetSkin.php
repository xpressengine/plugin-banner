<?php
namespace Xpressengine\Plugins\Banner\Skins;

use Xpressengine\Plugins\Banner\BannerWidgetSkin;

class WidgetSkin extends BannerWidgetSkin
{
    protected static $path = 'banner/skins/widget';

    /**
     * 스킨을 출력한다.
     * 만약 view 이름과 동일한 메소드명이 존재하면 그 메소드를 호출한다.
     *
     * @return Renderable|string
     */
    public function render()
    {
        app('xe.frontend')->css('assets/vendor/bootstrap/css/bootstrap.min.css')->load();
        return parent::render();
    }


}
