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

use Carbon\Carbon;
use Xpressengine\Database\Eloquent\DynamicModel;


/**
     * @category    Banner
     * @package     Xpressengine\Plugins\Banner
     * @author      XE Team (khongchi) <khongchi@xpressengine.com>
     * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
     * @link        http://www.xpressengine.com
     */
class Item extends DynamicModel
{
    protected $table = 'banner_item';

    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public $timestamps = true;

    protected $casts = [
        'order' => 'integer',
        'image' => 'array',
        'use_timer' => 'boolean',
        'startedAt' => 'date',
        'endedAt' => 'date'
    ];

    //protected $dates = [
    //    'startedAt',
    //    'endedAt'
    //];

    protected $fillable = [
        'order'
    ];

    protected $appends = [
        'edit_url', 'delete_url', 'update_url', 'is_visible', 'image_url'
    ];

    //protected $fillable = [
    //];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getEditUrlAttribute()
    {
        return route('banner::item.edit', ['group_id' => $this->group_id, 'item_id' => $this->id]);
    }

    public function getDeleteUrlAttribute()
    {
        return route('banner::item.delete', ['group_id' => $this->group_id, 'item_id' => $this->id]);
    }

    public function getUpdateUrlAttribute()
    {
        return route('banner::item.update', ['group_id' => $this->group_id, 'item_id' => $this->id]);
    }

    public function getOrderAttribute($value)
    {
        if($value === 0) {
            return $this->id;
        }
    }

    public function getImageSizeAttribute()
    {
        return $this->group->image_size;
    }

    public function getImageUrlAttribute()
    {
        $path = array_get($this->image, 'path');
        if($path) {
            return asset($path);
        } else {
            return asset('assets/core/common/img/default_image_196x140.jpg');
        }
    }

    public function getIsVisibleAttribute(){
        return $this->isVisible();
    }

    public function isVisible(Carbon $time = null)
    {
        if ($this->status !== 'show') {
            return false;
        }
        if (!$this->use_timer) {
            return true;
        }

        if ($time === null) {
            $time = Carbon::now();

            if ($time->gte($this->startedAt) && $time->lte($this->endedAt)) {
                return true;
            } else {
                return false;
            }
        }
    }

}
