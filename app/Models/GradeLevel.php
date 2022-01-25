<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeLevel extends Model
{
    use SoftDeletes;

    protected $table = 'grade_levels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['grade_level'];

    public function patrons()
    {
        return $this->hasManyThrough('App\Models\Patron', 'App\Models\Section');
    }

    public function sections()
    {
        return $table->hasMany('App\Models\Section');
    }
}
