<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'code',
        'status',
        'current_question_id',
        'host_id',
        'question_active_since',
    ];

    protected $casts = [
        'question_active_since' => 'datetime',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function currentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'current_question_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class, 'game_session_id')->orderByDesc('score');
    }

    public function playerAnswers(): HasManyThrough
    {
        return $this->hasManyThrough(PlayerAnswer::class, GamePlayer::class, 'game_session_id', 'game_player_id');
    }
}
