<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [

        'text',
        "question_id"
    ];

    public function question(){
        return $this->belongsTo(Question::class,'question_id');
    }
}
