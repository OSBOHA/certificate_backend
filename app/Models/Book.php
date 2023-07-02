<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_page',
        'name',
        'section_id',
        "level_id",
        "end_page",
        "writer",
        'publisher',
        'link',
        'brief',
        'level',
        'language_id',
        'type_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_book');
    }


    protected $with = array('section', 'level', 'type', 'language');

    public function level()
    {
        return $this->belongsTo(BookLevel::class, 'level_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function section()
    {
        return $this->belongsTo(BookSection::class, 'section_id');
    }

    public function userBook()
    {
        return $this->hasMany(UserBook::class);
    }


    public function Language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
