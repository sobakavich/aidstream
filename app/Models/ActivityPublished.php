<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityPublished
 * @package App\Models
 */
class ActivityPublished extends Model
{
    protected $table = "activity_published";
    protected $fillable = ['published_activities', 'filename', 'published_to_register', 'organization_id', 'created_at', 'updated_at'];

    protected $casts = [
        'published_activities' => 'json'
    ];
}
