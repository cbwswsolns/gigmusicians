<?php

namespace App\Models;

use App\Models\Artists\Profile;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /* Note: as table name is "links", there is no need to specify a table property for this model as "links" is the default name */


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'profile_id',
    ];


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsTo relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
