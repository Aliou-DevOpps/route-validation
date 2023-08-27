<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;
use App\Models\Fournisseur;

class Fournisarticles extends Model
{
    use HasFactory;
    protected $fillable=['article_id','fournisseur_id'];
    protected $hidden=[ "deleted_at","created_at","updated_at"];
    public function article(){
    return $this->belongsTo(Article::class);
    }
    public function fournisseur(){
        return $this->belongsTo(Fournisseur::class);
        }
}
