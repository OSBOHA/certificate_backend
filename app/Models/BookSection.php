<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSection extends Model
{
    use HasFactory;
    protected $table = 'section';
    protected $fillable = [
        'name'
    ];

    public function books(){
        return $this->hasMany(Book::class);
    }



}
