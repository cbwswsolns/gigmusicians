<?php

namespace App\Models;

use App\Models\Artists\Profile;

use App\Models\Traits\HasMedia;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasMedia;

    /* Note: as table name is "images", there is no need to specify a table property for this model as "images" is the default name */


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'profile_id',
    ];


    // MODEL METHODS

    /**
     * Get the files associated with this model
     *
     * @return array
     */
    public function getFiles()
    {
        return [$this->filename, $this->crop_filename];
    }


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
