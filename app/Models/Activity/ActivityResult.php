<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityResult
 * @package App\Models\Activity
 */
class ActivityResult extends Model
{
    protected $fillable = [
        'activity_id',
        'result'
    ];

    protected $casts = [
        'result' => 'json'
    ];

}
