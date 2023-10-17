<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistDiscogModel extends Model
{
    use HasFactory;
    
    public $table = "data_reference_album_sales";
    protected $guarded = [];
}
