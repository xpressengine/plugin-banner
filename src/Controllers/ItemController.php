<?php
/**
 * ItemController.php
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

use App\Http\Controllers\Controller as Origin;
use Illuminate\Http\Request;
use Xpressengine\Plugins\Banner\Handler;
use Xpressengine\Plugins\Banner\Models\Item;
use Xpressengine\Plugins\Banner\Plugin;

/**
 * ItemController
 *
 * @category    Widget
 * @package     Xpressengine\Plugins\Widget
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class ItemController extends Origin
{
    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * ItemController constructor.
     *
     * @param Plugin $plugin plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * store
     *
     * @param Request $request  request
     * @param Handler $handler  banner handler
     * @param string  $group_id group id
     *
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request, Handler $handler, $group_id)
    {
        $group = $handler->getGroup($group_id);

        \DB::beginTransaction();
        try {
            $item = $handler->createItem($group);
            $data = [
                'item' => view('banner::views.settings.group.item', compact('item'))->render()
            ];
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
        \DB::commit();
        return app('xe.presenter')
            ->makeApi(array_merge($data, ['alert' => ['type' => 'success', 'message' => '추가되었습니다.']]));
    }

    /**
     * edit
     *
     * @param Request $request  request
     * @param Handler $handler  banner handler
     * @param string  $group_id group id
     * @param string  $item_id  item id
     *
     * @return mixed
     */
    public function edit(Request $request, Handler $handler, $group_id, $item_id)
    {
        $group = $handler->getGroup($group_id);
        $skin = \XeSkin::get($group->skin);
        $item = $handler->getItem($item_id);
        return api_render($this->plugin->view('views.settings.item.edit'), compact('item', 'skin'));
    }

    /**
     * update
     *
     * @param Request $request  request
     * @param Handler $handler  banner handler
     * @param string  $group_id group id
     * @param string  $item_id  item id
     *
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Handler $handler, $group_id, $item_id)
    {
        $inputs = $request->only(['title', 'image', 'content', 'status', 'use_timer', 'link', 'link_target']);
        if (isset($inputs['use_timer']) == false) {
            $inputs['use_timer'] = 0;
        }

        $sd = $request->get('started_at_date');
        $st = $request->get('started_at_time');
        $ed = $request->get('ended_at_date');
        $et = $request->get('ended_at_time');

        if ($sd) {
            $inputs['started_at'] = $sd . ' ' . ($st ?: '00:00') . ':00';
        }
        if ($ed) {
            $inputs['ended_at'] = $ed . ' ' . ($et ?: '00:00') . ':00';
        }

        if (array_get($inputs, 'link_target') === null) {
            $inputs['link_target'] = '_self';
        }

        if ($request->get('original_image_size', null) == 1) {
            $handler::setResizable(false);
        }

        $inputs['etc'] = $request->except([
            '_token', '_method', 'title', 'image', 'content', 'status', 'use_timer', 'link', 'link_target',
            'started_at_date', 'started_at_time', 'ended_at_date', 'ended_at_time',
        ]);

        $item = $handler->getItem($item_id);

        \DB::beginTransaction();
        try {
            $item = $handler->updateItem($item, $inputs);

            $data = [
                'item' => view('banner::views.settings.group.item', compact('item'))->render()
            ];
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
        \DB::commit();

        return app('xe.presenter')
            ->makeApi(array_merge($data, ['alert' => ['type' => 'success', 'message' => '저장되었습니다.']]));
    }

    /**
     * destroy
     *
     * @param Request $request  request
     * @param Handler $handler  banner handler
     * @param string  $group_id group id
     * @param string  $item_id  item id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Request $request, Handler $handler, $group_id, $item_id)
    {
        $item = Item::where('group_id', $group_id)->find($item_id);

        \DB::beginTransaction();
        try {
            $handler->removeItem($item);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
        \DB::commit();

        return app('xe.presenter')->makeApi(['alert' => ['type' => 'success', 'message' => '삭제되었습니다.']]);
    }
}
