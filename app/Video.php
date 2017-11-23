<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends UidModel
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
     */
    public function pushTag($tag)
    {
        if (! $this->tags()
            ->where('name', $tag)
            ->count()) {
            $this->tags()
                ->create([
                    'name' => $tag,
                ]);
        }
    }

    /**
     * @return HasMany
     */
    public function tags()
    {
        return $this->hasMany(TagInVideo::class, 'video_id');
    }
}
