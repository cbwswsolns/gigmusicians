<?php

namespace App\Services\Artists;

use App\Models\Image;
use App\Models\Link;
use App\Models\MusicFile;

use App\Models\Artists\Profile;

use App\Services\Media\Image\ImageHandlerService;

class ProfileService
{
    /**
     * Profile model instance
     *
     * @var \App\Models\Artists\Profile
     */
    protected $profile;


    /**
     * Create a new profile service instance.
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }


    /**
     * Index method
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        /* Return eager loaded sorted profiles with their owner (user)
           Note: Will return empty collection if no profiles exist */
        return $this->profile->BySortOrder()->with('user')->get();
    }


    /**
     * Store profile method
     *
     * @param array $data [the data to use to create a new profile]
     *
     * @return \App\Models\Artists\Profile
     */
    public function store(array $data)
    {
        /* CREATE PROFILE RECORD */

        // Note: Will be set to +1 if no profiles exist
        $sortorder = $this->profile->BySortOrder()->get()->max('sortorder') + 1;

        // Create the profile record
        $profile = $this->profile->create(
            ['name' => $data['name'],
             'email' => $data['email'],
             'category_id' => $data['category_id'],
             'description' => $data['description'],
             'sortorder' => $sortorder,
            ]
        );

        $profile->user_id = auth()->user()->id;
        $profile->laststatus = $this->profile::PENDING;
        $profile->status = $this->profile::PENDING;

        $profile->save();


        /* ATTACH RELATED PROFILE MEDIA */

        if (isset($data['images'])) {
            $this->attachImages($profile, collect($data['images']));
        }

        if (isset($data['musicfiles'])) {
            $this->attachMusicFiles($profile, collect($data['musicfiles']));
        }


        /* ATTACH RELATED PROFILE LINKS */

        $profile->attachLink(new Link(['profile_id' => $profile->id, 'link' => $data['soundplatform'], 'linktype' => 'soundplatform']));

        $profile->attachLink(new Link(['profile_id' => $profile->id, 'link' => youTubeLinkToEmbed($data['youtube']), 'linktype' => 'youtube']));


        return $profile;
    }


    /**
     * Update profile method
     *
     * @param array                       $data    [the data to use to update the given profile]
     * @param \App\Models\Artists\Profile $profile [the profile model instance to update]
     *
     * @return void
     */
    public function update(array $data, Profile $profile)
    {
        /* UPDATE PROFILE RECORD */

        $profile->update($data);


        /* ATTACH RELATED PROFILE FILES (IF PRESENT) */

        if (isset($data['images'])) {
            $this->attachImages($profile, collect($data['images']));
        }

        if (isset($data['musicfiles'])) {
            $this->attachMusicFiles($profile, collect($data['musicfiles']));
        }


        /* UPDATE RELATED PROFILE LINKS */

        $links = $profile->links->where('linktype', 'soundplatform');

        if ($links->count() == 1) {
            $links->first()->update(['link' => $data['soundplatform']]);
        }

        $links = $profile->links->where('linktype', 'youtube');

        if ($links->count() == 1) {
            $links->first()->update(['link' => youTubeLinkToEmbed($data['youtube'])]);
        }
    }


    /**
     * Delete method
     *
     * @param \App\Models\Artists\Profile $profile [the profile model to delete]
     *
     * @return void
     */
    public function delete(Profile $profile)
    {
        /* Associated stored files will be deleted via a model "deleting" event listener.
           Associated related/child records will be deleted (via "on cascade" implementation) */
        $profile->delete();
    }


    /**
     * Attach images to profile
     *
     * @param \App\Models\Artists\Profile    $profile [the profile to attach to]
     * @param \Illuminate\Support\Collection $files   [the image files to attach]
     *
     * @return void
     */
    protected function attachImages($profile, $files)
    {
        foreach ($files as $file) {
            $path = (resolve('App\Services\Media\MediaInterface'))->store($file, 'images');

            // Relative path to store in image model
            $cropPath = 'images/crop_'.basename($path);

            // Full path required by image handler
            $imageHandlerPath = public_path('/storage/').$cropPath;

            (new ImageHandlerService)->makeResizedImage($file->getRealPath(), $imageHandlerPath, 300, 200);

            // Attach the associated database record for referencing the original image
            $profile->attachImageRecord(
                new Image(['profile_id' => $profile->id,'filename' => $path, 'crop_filename' => $cropPath, 'name' => $file->getClientOriginalName()])
            );
        }
    }


    /**
     * Attach music files to profile
     *
     * @param \App\Models\Artists\Profile    $profile [the profile to attach to]
     * @param \Illuminate\Support\Collection $files   [the music files to attach]
     *
     * @return void
     */
    protected function attachMusicFiles($profile, $files)
    {
        foreach ($files as $file) {
            $path = (resolve('App\Services\Media\MediaInterface'))->store($file, 'musicfiles');

            // Attach the associated database record for the music file
            $profile->attachMusicFileRecord(
                new MusicFile(['profile_id' => $profile->id, 'filename' => $path, 'name' => $file->getClientOriginalName()])
            );
        }
    }


    /**
     * Get Profile States
     *
     * @return array
     */
    public function getProfileStates()
    {
        return $this->profile->getProfileStates();
    }
}
