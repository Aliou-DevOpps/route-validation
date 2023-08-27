<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleBetwen extends Model
{
    use HasFactory;
    public function article(){
    return $this->belongsTo(Article::class);
    }
    public function articlevente(){
        return $this->belongsTo(ArticleVente::class);
        }
}
