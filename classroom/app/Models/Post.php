<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'user_id',
        'title',
        'content',
        'type',
        'points',
        'due_date',
        'attachment_path',
        'attachment_name',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->oldest();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'post_id');
    }
}
