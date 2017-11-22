<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'playable',
        'owner',
        'category_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @param string $tag
     * @return TagInVideo
     */
    public function pushTag($tag)
    {
        return $this->tags()
            ->firstOrNew([
                'name' => $tag,
            ]);
    }

    /**
     * @return HasMany
     */
    public function tags()
    {
        return $this->hasMany(TagInVideo::class, 'video_id');
    }
}
