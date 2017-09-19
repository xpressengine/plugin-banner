<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category
 * @package     Xpressengine\
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */

namespace Xpressengine\Plugins\Banner;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xpressengine\Media\Thumbnailer;
use Xpressengine\Plugins\Banner\Models\Group;
use Xpressengine\Plugins\Banner\Models\Item;

/**
 * @category
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class Handler
{
    /**
     * @var Plugin
     */
    protected $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function createGroup($attrs)
    {
        $group = new Group();
        $group->skin = $attrs['skin'];
        $group->title = $attrs['title'];
        $group->save();
        return $group;
    }

    public function getGroup($group_id)
    {
        return Group::find($group_id);
    }

    public function removeGroup($group)
    {
        if(is_string($group)) {
            $group = Group::find($group);
        }

        if($group !== null) {
            $group->delete();
            $items = Items::where('group_id', $group->id)->get();
            foreach ($items as $item) {
                $this->removeItem($item);
            }
        }
    }

    public function getGroups()
    {
        return Group::with('itemCountRelation')->get();
    }

    public function createItem($group, $attrs = [])
    {
        $item = new Item();
        $item->group_id = $group->id;
        $item->order = 9999; // todo: fix order
        $item->status = 'hidden';
        $item->title = 'untitled';

        foreach ($attrs as $key => $value) {
            $item->{$key} = $value;
        }
        $item->save();
        return $item;
    }

    public function getItem($item_id)
    {
        return Item::with('group')->find($item_id);
    }

    public function updateItem($item, $attrs = [])
    {
        // process image
        $image = array_get($attrs, 'image');
        unset($attrs['image']);
        if ($image) {
            if ($item->image) {
                $this->removeFile($item->image);
                $item->image = null;
            }
            if ($image instanceof UploadedFile) {
                $item->image = $this->saveImage($item, $image);
            }
        }

        foreach ($attrs as $key => $value) {
            $item->{$key} = $value;
        }

        $item->save();
        return $item;
    }

    protected function removeFile($image)
    {
        $id = $image['id'];

        // remove old file
        if ($id !== null) {
            $oldFile = app('xe.storage')->find($id);
            if ($oldFile) {
                app('xe.storage')->delete($oldFile);
            }
        }
    }

    protected function saveImage($item, UploadedFile $file)
    {
        $imageManager = Thumbnailer::getManager();
        $size = $item->image_size;

        $img = $imageManager->make($file->getRealPath());
        $img = $img->fit(array_get($size, 'width'), array_get($size, 'height'));

        // save new file
        $file = app('xe.storage')->create(
            $img->encode()->getEncoded(),
            "public/plugin/banner/{$item->group_id}/{$item->id}",
            'image'
        );
        app('xe.storage')->bind("banner/{$item->group_id}/{$item->id}", $file);

        $saved = [
            'id' => $file->id,
            'filename' => $file->clientname
        ];

        $mediaFile = null;
        if (app('xe.media')->is($file)) {
            $mediaFile = app('xe.media')->make($file);
            $saved['path'] = $mediaFile->url();
        }

        return $saved;
    }

    public function removeItem($item)
    {
        if ($image = $item->image) {
            $this->removeFile($image);
        }
        $item->delete();
    }

    public function sortItems($group, $orders)
    {
        $items = Item::where('group_id', $group->id)->findMany($orders);

        foreach (array_reverse($orders) as $order => $id) {
            $items->find($id)->update(['order' => $order]);
        }
    }

    public function getItems($group, $count = null, $onlyVisible = false)
    {
        $query = Item::where('group_id', $group->id);

        if ($onlyVisible) {
            $query->where('status', 'show')
            ->where(function ($query) {
                $query->where('use_timer', 0)
                ->orWhere(function($query) {
                    $now = Carbon::now();
                    $query->where('ended_at', '>', $now);
                    $query->where('started_at', '<', $now);
                });
            });
        }

        if ($count !== null) {
            $query->take($count);
        }

        return $query->orderBy('order', 'desc')->orderBy('created_at','desc')->get();
    }
}
