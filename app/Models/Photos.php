<?php

namespace App\Models;

use App\Http\Controllers\API\ThesisController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'thesis_id'
    ];

    public function photo(){
        return $this->belongsTo(ThesisController::class,'thesis_id');
    }

}
