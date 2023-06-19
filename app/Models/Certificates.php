<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_book_id',
        "final_grade",
        'general_summary_grade',
        "thesis_grade",
        "check_reading_grade",
    ];

    public function user_book(){
        return $this->belongsTo(UserBook::class,'user_book_id');
    }
}
