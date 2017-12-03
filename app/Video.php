<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'thumbnail',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

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
     * @param string $url
     * @return Video
     */
    public function setPlayable(string $url)
    {
        $this->playable = $url;

        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setThumbnail(string $url)
    {
        $this->thumbnail = $url;

        return $this;
    }

    /**
     * @param string $val
     * @return string
     */
    public function getThumbnailAttribute($val)
    {
        $baseUrl = config('app.url');
        return is_null($val) ? null : "{$baseUrl}/$val";
    }

    /**
     * @param string $val
     * @return string
     */
    public function getPlayableAttribute($val)
    {
        $baseUrl = config('app.url');
        return is_null($val) ? null : "{$baseUrl}/$val";
    }

    /**
     * @return bool
     */
    public function readyToPlay()
    {
        return !is_null($this->playable);
    }

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * @return HasMany
     */
    public function tags()
    {
        return $this->hasMany(TagInVideo::class, 'video_id');
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'video_id');
    }
}
