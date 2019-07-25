<?php
/**
 * Group.php
 *
 * This file is part of the Xpressengine package.
 *
 * PHP version 7
 *
 * @category    Banner
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Banner\Models;

use Illuminate\Support\Arr;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\Banner\BannerWidgetSkin;

/**
 * Group
 *
 * @category    Widget
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class Group extends DynamicModel
{
    protected $table = 'banner_group';

    public $incrementing = false;

    public $timestamps = true;

    protected static $skinResolver;

    /**
     * items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * @param null|string $key     key
     * @param null|mixed  $default default
     *
     * @return array|mixed
     */
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

    /**
     * get image size
     *
     * @param null|string $type type
     *
     * @return array|mixed
     */
    public function getImageSize($type = null)
    {
        $size = $this->getSkinInfo('image', ['widget' => 800, 'height' => 600]);

        if (!$type) {
            return $size;
        }

        return $size[$type];
    }

    /**
     * get widget code
     *
     * @return string
     */
    public function getWidgetCode()
    {
        return sprintf(
            '<xewidget id="widget/banner@widget" title="%s" skin-id="%s"><group_id>%s</group_id></xewidget>',
            $this->title,
            $this->skin,
            $this->id
        );
    }

    /**
     * set skin resolver
     *
     * @param callable $resolver resolver
     *
     * @return void
     */
    public static function setSkinResolver(callable $resolver)
    {
        static::$skinResolver = $resolver;
    }

    /**
     * resolve skin
     *
     * @param string $skinId skin id
     *
     * @return mixed
     */
    protected function resolveSkin($skinId)
    {
        return call_user_func(static::$skinResolver, $skinId);
    }
}
