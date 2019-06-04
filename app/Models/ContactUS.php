<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUS extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'contact_us';
 
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name','email','message'];
}
