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
class GithubRecord extends Model
{
    protected $table = 'github_records';

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
        'year_level',
    ];

    public function getYearLevelAttribute(): float
    {
        $currentYear = $this->date->year;

        $maxContributionsInYear = self::whereYear('date', $currentYear)->max('contributions_count');

        if ($maxContributionsInYear === null || $maxContributionsInYear === 0) {
            return 0.0;
        }

        return $this->contributions_count / $maxContributionsInYear;
    }
}
