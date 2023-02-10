<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    public function start(){
        return $this->belongsTo(Node::class,'start_id');
    }
    public function destination(){
        return $this->belongsTo(Node::class,'destination_id');
    }
}
