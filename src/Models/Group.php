<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Banner
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2000-2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Banner\Models;

use Illuminate\Support\Arr;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\Banner\BannerWidgetSkin;

/**
     * @category    Banner
     * @package     Xpressengine\Plugins\Banner
     * @author      XE Team (developers) <developers@xpressengine.com>
     * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
     * @link        http://www.xpressengine.com
     */
class Group extends DynamicModel
{
    protected $table = 'banner_group';

    public $incrementing = false;

    public $timestamps = true;

    protected static $skinResolver;

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getSkinInfo($key = null, $default = null)
    {
        /** @var BannerWidgetSkin $skin */
        $skin = $this->resolveSkin($this->skin)->getClass();
        $info = $skin::getBannerInfo();

        if (!$key) {
            return $info;
        }

        return Arr::get($info, $key, $default);
    }

    public function getImageSize($type = null)
    {
        $size = $this->getSkinInfo('image', ['widget' => 800, 'height' => 600]);

        if (!$type) {
            return $size;
        }

        return $size[$type];
    }

    public function getWidgetCode()
    {
        return sprintf(
            '<xewidget id="widget/banner@widget" title="%s" skin-id="%s"><group_id>%s</group_id></xewidget>',
            $this->title,
            $this->skin,
            $this->id
        );
    }

    public static function setSkinResolver(callable $resolver)
    {
        static::$skinResolver = $resolver;
    }

    protected function resolveSkin($skinId)
    {
        return call_user_func(static::$skinResolver, $skinId);
    }
}
