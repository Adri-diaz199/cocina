<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'ingredients',
        'instructions',
        'image',
    ];

    public function category(){
        return $this->belongsTo(Category::Class);
    }
    
    public function user(){
        return $this->belongsTo(User::Class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::Class);
    }
}
