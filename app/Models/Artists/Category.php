<?php

namespace App\Models\Artists;

use App\Models\Traits\HasRelatedMedia;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasRelatedMedia;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'artist_categories';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // MODEL METHODS

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up HasMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }


    /**
     * Set up HasManyThrough relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function images()
    {
        return $this->hasManyThrough('App\Models\Image', 'App\Models\Artists\Profile');
    }


    /**
     * Set up HasManyThrough relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function musicfiles()
    {
        return $this->hasManyThrough('App\Models\MusicFile', 'App\Models\Artists\Profile');
    }


    // MODEL QUERY SCOPES

    /**
     * Scope a query to return sorted categories
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [the query builder instance]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySortOrder($query)
    {
        return $query->orderBy('sortorder');
    }
}
