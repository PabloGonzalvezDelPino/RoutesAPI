<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    public function origin(){
        return $this->belongsTo(Node::class,'origin');
    }
    public function destination(){
        return $this->belongsTo(Node::class,'destination');
    }
}
