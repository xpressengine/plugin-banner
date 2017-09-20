<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Point
 * @package     Xpressengine\Plugins\Point
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Banner\Controllers;

use App\Http\Controllers\Controller as Origin;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\Banner\Handler;
use Xpressengine\Plugins\Banner\Models\Group;
use Xpressengine\Plugins\Banner\Plugin;

/**
 * @category    Point
 * @package     Xpressengine\Plugins\Point\Controllers
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class GroupController extends Origin
{
    /**
     * @var Plugin
     */
    protected $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function index(Handler $handler)
    {
        $groups = $handler->getGroups();

        app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-page.js')->load();
        app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-form.js')->load();

        return app('xe.presenter')->make($this->plugin->view('views.settings.group.index'), compact('groups'));
    }

    public function create()
    {
        return api_Render($this->plugin->view('views.settings.group.create'));
    }

    public function store(Request $request, Handler $handler)
    {
        $this->validate($request, [
            'title' => 'required|unique:banner_group',
            'skin' => 'required'
        ]);

        $inputs = $request->only('title', 'skin');
        \DB::beginTransaction();
        try {
            $handler->createGroup($inputs);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
        \DB::commit();

        return redirect()->route('banner::group.index')->with(['alert'=>['type'=>'success', 'message'=>'추가되었습니다.']]);
    }

    public function edit(Request $request, Handler $handler, $group_id)
    {
        app('xe.theme')->selectBlankTheme();

        app('xe.frontend')->css('assets/vendor/bootstrap/css/bootstrap.min.css')->load();
        app('xe.frontend')->css('assets/core/widgetbox/css/widgetbox.css')->load();
        app('xe.frontend')->css($this->plugin->asset('assets/css/edit.css'))->load();

        app('xe.frontend')->js('assets/vendor/jqueryui/jquery-ui.min.js')->load();
        app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-page.js')->load();
        app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-form.js')->load();
        app('xe.frontend')->js($this->plugin->asset('assets/js/edit.js'))->load();

        $group = Group::find($group_id);

        $items = $handler->getItems($group);

        return app('xe.presenter')->make($this->plugin->view('views.settings.group.edit'), compact('group', 'items'));
    }

    public function update(Request $request, Handler $handler, $group_id)
    {
        $group = $handler->getGroup($group_id);
        $orders = $request->get('orders');

        if ($orders !== null) {
            $handler->sortItems($group, $orders);
        }

        $skin = $request->get('skin');
        if ($skin !== null) {
            $group->skin = $skin;
        }

        $title = $request->get('title');
        if ($title !== null) {
            $group->title = $title;
        }

        $group->save();

        return app('xe.presenter')->makeApi(['alert'=>['type'=>'success', 'message'=>'수정되었습니다']]);
    }
}
