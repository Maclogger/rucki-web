<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $date Dátum záznamu (primárny kľúč).
 * @property int $contributions_count Počet príspevkov za daný deň.
 * @property Carbon $created_at Čas vytvorenia záznamu.
 * @property Carbon $updated_at Čas poslednej aktualizácie záznamu.
 */
class GitHubRecord extends Model
{
    protected $table = 'git_hub_records';

    protected $fillable = [
        'date',
        'contributions_count',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'day_of_the_week',
    ];

    public function getDayOfTheWeekAttribute(): int {
        return $this->date->dayOfWeek;
    }
}
