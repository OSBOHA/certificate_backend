<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id'
    ];


    protected $table = 'user_book';

    protected $with = array('thesises','user','book','questions','generalInformation');


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function book(){
        return $this->belongsTo(Book::class,'book_id');
    }

    public function certificates(){
        return $this->hasMany(Certificates::class);
    }

    public function thesises(){
        return $this->hasMany(Thesis::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function generalInformation(){
        return $this->hasOne(GeneralInformations::class);
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($userBook) { 
            $userBook->generalInformation()->each(function ($generalInformation) {
                $generalInformation->delete(); 
            });
            $userBook->questions()->each(function ($questions) {
                $questions->delete(); 
            });
            $userBook->thesises()->each(function ($thesises) {
                $thesises->delete(); 
            });
            $userBook->certificates()->each(function ($certificates) {
                $certificates->delete(); 
            });
        });
    }

}


