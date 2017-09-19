<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Banner
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (khongchi) <khongchi@xpressengine.com>
 * @copyright   2000-2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Banner\Models;

use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\Banner\BannerWidgetSkin;

/**
     * @category    Banner
     * @package     Xpressengine\Plugins\Banner
     * @author      XE Team (khongchi) <khongchi@xpressengine.com>
     * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
     * @link        http://www.xpressengine.com
     */
class Group extends DynamicModel
{
    protected $table = 'banner_group';

    public $incrementing = false;

    public $timestamps = true;

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function itemCountRelation()
    {
        return $this->items()->selectRaw(
            'group_id, count(*) as count'
        )->groupBy('group_id');
    }

    public function getItemCountAttribute()
    {
        $count = $this->itemCountRelation->first();
        return $count ? $count->count : 0;
    }

    public function getSkinClassAttribute()
    {
        return app('xe.register')->get($this->skin);
    }

    public function getSkinInfoAttribute()
    {
        /** @var BannerWidgetSkin $skin */
        $skin = $this->skin_class;
        return $skin::getBannerInfo();
    }

    public function getImageSizeAttribute()
    {
        /** @var BannerWidgetSkin $skin */
        $skin = $this->skin_class;
        $info = $skin::getBannerInfo();

        return array_get($info, 'image', ['widget' => 800, 'height' => 600]);
    }
    public function getEditUrlAttribute()
    {
        return route('banner::group.edit', ['group_id' => $this->id]);
    }

    public function getWidgetCodeAttribute()
    {
        return sprintf(
            '<xewidget id="widget/banner@widget" title="%s" skin-id="%s"><group_id>%s</group_id></xewidget>',
            $this->title,
            $this->skin,
            $this->id
        );
    }

}
