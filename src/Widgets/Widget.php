<?php
/**
 * Widget.php
 *
 * This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Banner
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
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
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
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
        $setting = $this->setting();
        $group_id = array_get($setting, 'group_id');

        $group = app('xe.banner')->getGroup($group_id);

        if ($group == null) {
            return '';
        }

        $items = app('xe.banner')->getItems($group, $group->getSkinInfo('count'), true);

        array_set($this->config, '@attributes.skin-id', $group->skin);

        $footer = '';
        if (auth()->user()->isAdmin()) {
            // 경우에 따라 버튼의 스타일이 다르게 표현되어 디자인이 깨지는 현상으로 인해 주석처리
//            $footer = '<div style="position:relative;top:-30px;text-align:right"><a class="xe-btn xe-btn-xs xe-btn-primary-outline" href="'.route('banner::group.edit',['group_id' => $group->id]).'" onclick="window.open(this.href, \'bannerEditor\', \'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no\');return false">배너편집</a></div>';
        }
        return $this->renderSkin(compact('group', 'items')) . $footer;
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
        $groups = app('xe.banner')->getGroups();
        return view(Plugin::view('views.widget.setting'), compact('groups', 'args'));
    }
}
