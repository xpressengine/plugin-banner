<?php
/**
 * Widget.php
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

namespace Xpressengine\Plugins\Banner\Widgets;

use Xpressengine\Plugins\Banner\Plugin;
use Xpressengine\Widget\AbstractWidget;

/**
 * Widget
 *
 * @category    Widget
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class Widget extends AbstractWidget
{
    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $widgetConfig = $this->setting();

        $setting = $this->setting();
        $title = $setting['@attributes']['title'];
        $group_id = array_get($setting, 'group_id');

        $group = app('xe.banner')->getGroup($group_id);
        if ($group == null) {
            return '';
        }

        array_set($this->config, '@attributes.skin-id', $group->skin);

        // 랜덤 (Random)
        $isRandomActivated = (array_get($widgetConfig, 'random') === 'activated');
        $items = app('xe.banner')->getItems($group, $group->getSkinInfo('count'), true)->when($isRandomActivated, function($items) {
            return $items->shuffle();
        });

        $footer = '';
        if (auth()->user()->isAdmin()) {
            // 경우에 따라 버튼의 스타일이 다르게 표현되어 디자인이 깨지는 현상으로 인해 주석처리
//            $footer = '<div style="position:relative;top:-30px;text-align:right"><a class="xe-btn xe-btn-xs xe-btn-primary-outline" href="'.route('banner::group.edit',['group_id' => $group->id]).'" onclick="window.open(this.href, \'bannerEditor\', \'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no\');return false">배너편집</a></div>';
        }

        return $this->renderSkin(compact('title', 'group', 'items', 'widgetConfig')) . $footer;
    }

    /**
     * 위젯 설정 페이지에 출력할 폼을 출력한다.
     *
     * @param array $args 설정값
     *
     * @return string
     */
    public function renderSetting(array $args = [])
    {
//        $groups = app('xe.banner')->getGroups();
        $selectedSkinId = app('request')->get('skin');
        if ($selectedSkinId == '') {
            $selectedSkinId = array_get($args, '@attributes.skin-id');
        }
        $groups = app('xe.banner')->getGroupsBySkin($selectedSkinId);

        return view(Plugin::view('views.widget.setting'), compact('groups', 'args'));
    }
}
