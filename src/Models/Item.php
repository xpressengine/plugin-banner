<?php
/**
 * Item.php
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

use Carbon\Carbon;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Media\Models\Image;

/**
 * Item
 *
 * @category    Widget
 * @package     Xpressengine\Plugins\Banner
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
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
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'etc' => 'array'
    ];

    protected $fillable = ['order'];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * get image size
     *
     * @param null|string $type type
     *
     * @return mixed
     */
    public function getImageSize($type = null)
    {
        return $this->group->getImageSize($type);
    }

    /**
     * image url
     *
     * @return string
     */
    public function imageUrl()
    {
        $id = array_get($this->image, 'id');
        $image = Image::find($id);

        if ($image) {
            return $image->url();
        } else {
            return asset('assets/core/common/img/default_image_1200x800.jpg');
        }
    }

    /**
     * check is visible
     *
     * @param Carbon|null $time time
     *
     * @return bool
     */
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
