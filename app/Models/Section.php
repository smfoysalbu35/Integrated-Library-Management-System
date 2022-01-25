<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $table = 'sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'grade_level_id'
    ];

    public function grade_level()
    {
        return $this->belongsTo('App\Models\GradeLevel');
    }

    public function patrons()
    {
        return $this->hasMany('App\Models\Patron');
    }
}
