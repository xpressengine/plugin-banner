<?php
/**
 * GroupController.php
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

namespace Xpressengine\Plugins\Banner\Controllers;

use XePresenter;
use App\Http\Controllers\Controller as Origin;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\Banner\Handler;
use Xpressengine\Plugins\Banner\Models\Group;
use Xpressengine\Plugins\Banner\Plugin;

/**
 * GroupController
 *
 * @category    Widget
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class GroupController extends Origin
{
    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * GroupController constructor.
     *
     * @param Plugin $plugin plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * index
     *
     * @param Handler $handler banner handler
     *
     * @return mixed
     */
    public function index(Handler $handler)
    {
        $groups = $handler->getGroups();

        app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-page.js')->load();
        app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-form.js')->load();

        return app('xe.presenter')->make($this->plugin->view('views.settings.group.index'), compact('groups'));
    }

    /**
     * create
     *
     * @return mixed
     */
    public function create()
    {
        return api_Render($this->plugin->view('views.settings.group.create'));
    }

    /**
     * @param Request $request request
     * @param Handler $handler banner handler
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
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

        return redirect()->route('banner::group.index')
            ->with(['alert' => ['type' => 'success', 'message' => '추가되었습니다.']]);
    }

    /**
     * edit
     *
     * @param Request $request  request
     * @param Handler $handler  banner handler
     * @param string  $group_id group id
     *
     * @return mixed
     */
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

    /**
     * update form
     *
     * @param string $group_id group id
     *
     * @return mixed
     */
    public function updateForm($group_id)
    {
        $group = Group::find($group_id);

        return api_Render($this->plugin->view('views.settings.group.update'), compact('group'));
    }

    /**
     * update
     *
     * @param Request $request  request
     * @param Handler $handler  banner handler
     * @param string  $group_id group id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

        return redirect()->route('banner::group.index')
            ->with(['alert' => ['type' => 'success', 'message' => '수정되었습니다.']]);
    }

    /**
     * destroy
     *
     * @param Handler $handler Banner handler
     * @param string  $groupId Group id
     *
     * @return mixed|\Xpressengine\Presenter\Presentable
     * @throws \Throwable
     */
    public function destroy(Handler $handler, $groupId)
    {
        \DB::beginTransaction();
        try {
            $group = $handler->getGroup($groupId);
            $handler->removeGroup($group);
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        return XePresenter::makeApi(['success' => true]);
    }
}
