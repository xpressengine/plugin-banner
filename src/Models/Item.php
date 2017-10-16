<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Banner
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
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
     * @author      XE Team (developers) <developers@xpressengine.com>
     * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
     * @link        http://www.xpressengine.com
     */
class Item extends DynamicModel
{
    protected $table = 'banner_item';

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'order' => 'integer',
        'image' => 'array',
        'use_timer' => 'boolean',
        'started_at' => 'date',
        'ended_at' => 'date',
        'etc' => 'array'
    ];

    protected $fillable = [
        'order'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getImageSize($type = null)
    {
        return $this->group->getImageSize($type);
    }

    public function imageUrl()
    {
        $path = array_get($this->image, 'path');
        if($path) {
            return asset($path);
        } else {
            return asset('assets/core/common/img/default_image_196x140.jpg');
        }
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

            if ($time->gte($this->started_at) && $time->lte($this->ended_at)) {
                return true;
            } else {
                return false;
            }
        }
    }

}
