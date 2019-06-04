<?php

namespace App\Rules;

use App\Models\Artists\Profile;

use Illuminate\Contracts\Validation\Rule;

class MaxImageFileCount implements Rule
{
    /**
     * The profile model instance
     *
     * @var \App\Models\Artists\Profile
     */
    protected $profile;


    /**
     * Create a new rule instance.
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
     * Determine if the validation rule passes.
     *
     * @param string $attribute [field under validation]
     * @param mixed  $value     [value of field]
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $imageDbCount = $this->profile->images()->count();

        return (($imageDbCount + sizeof($value)) < 3);
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A maximum of two files are permitted! (including existing attached images)';
    }
}
