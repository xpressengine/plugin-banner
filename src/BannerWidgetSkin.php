<?php
/**
 * BannerWidgetSkin.php
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

namespace Xpressengine\Plugins\Banner;

use Xpressengine\Skin\GenericSkin;

/**
 * BannerWidgetSkin
 *
 * @category    Widget
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class BannerWidgetSkin extends GenericSkin
{
    /**
     * get banner info
     *
     * @param null|string $key key
     *
     * @return array
     */
    public static function getBannerInfo($key = null)
    {
        if ($key) {
            $key = '.' . $key;
        }
        return static::info('banner' . $key);
    }

    /**
     * render banner setting
     *
     * @return string
     */
    public function renderBannerSetting()
    {
        return '';
    }
}
