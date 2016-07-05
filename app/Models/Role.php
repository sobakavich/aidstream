<?php namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\Models
 */
class Role extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'role';

    /**
     * A Role belongs to a User.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
