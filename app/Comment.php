<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends UidModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments_in_videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'video_id',
        'commenter',
        'comment',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return BelongsTo
     */
    public function commenter()
    {
        return $this->belongsTo(User::class, 'commenter');
    }
}
