<?php

namespace App\Models\Artists;

use App\Models\Image;
use App\Models\Link;
use App\Models\MusicFile;

use App\Models\Traits\HasRelatedMedia;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasRelatedMedia;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'artist_profiles';


    const PENDING = 'ProfilePending';
    const SUBMITTED = 'ProfileSubmitted';
    const PUBLISHED = 'ProfilePublished';
    const FEATURED = 'ProfileFeatured';
    const RECALLED = 'ProfileRecalled';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'name', 'email', 'category_id', 'description', 'sortorder'
        ];


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


    /**
     * Attach an image to the profile
     *
     * @param \App\Models\Image $image [the image model instance]
     *
     * @return bool
     */
    public function attachImageRecord(Image $image)
    {
        return $this->images()->save($image);
    }


    /**
     * Attach a music file to the profile
     *
     * @param \App\Models\MusicFile $musicFile [the music file model instance]
     *
     * @return bool
     */
    public function attachMusicFileRecord(MusicFile $musicFile)
    {
        return $this->musicfiles()->save($musicFile);
    }


    /**
     * Attach a link to the profile
     *
     * @param \App\Models\Link $link [the link model instance]
     *
     * @return bool
     */
    public function attachLink(Link $link)
    {
        return $this->links()->save($link);
    }


    /**
     * Check if the pending profile status is set
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status == self::PENDING;
    }


    /**
     * Check if the submitted profile status is set
     *
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->status == self::SUBMITTED;
    }


    /**
     * Check if the published profile status is set
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == self::PUBLISHED;
    }


    /**
     * Check if the featured profile status is set
     *
     * @return bool
     */
    public function isFeatured()
    {
        return $this->status == self::FEATURED;
    }


    /**
     * Check if the recalled profile status is set
     *
     * @return bool
     */
    public function isRecalled()
    {
        return $this->status == self::RECALLED;
    }


    /**
     * Return a list of profile states
     *
     * @return array
     */
    public function getProfileStates()
    {
        return ['pending' => self::PENDING, 'submitted' => self::SUBMITTED, 'published' => self::PUBLISHED, 'featured' => self::FEATURED, 'recalled' => self::RECALLED];
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsTo relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Set up BelongsTo relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    /**
     * Set up HasMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }


    /**
     * Set up HasMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function musicfiles()
    {
        return $this->hasMany(MusicFile::class);
    }


    /**
     * Set up HasMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }


    // MODEL QUERY SCOPES

    /**
     * Scope a query to return sorted profiles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [the query builder instance]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySortOrder($query)
    {
        return $query->orderBy('sortorder');
    }


    /**
     * Scope a query to return published profiles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [the query builder instance]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPublished($query)
    {
        return $query->where('status', self::PUBLISHED);
    }


    /**
     * Scope a query to return featured profiles (should only ever be one!)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [the query builder instance]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFeatured($query)
    {
        return $query->where('status', self::FEATURED);
    }


    /**
     * Scope a query to return profiles that are either published or featured
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [the query builder instance]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPublishedOrFeaturedAndSortOrder($query)
    {
        return $query->whereIn('status', [self::PUBLISHED, self::FEATURED])->BySortOrder();
    }
}
