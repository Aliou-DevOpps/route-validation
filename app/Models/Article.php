<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Categorie;
use App\Models\Fournisseur;

class Article extends Model
{
    use HasFactory;
    protected $hidden=[ "deleted_at","created_at","updated_at"];
    use SoftDeletes;
    protected $fillable = [
        
        'libelle', // Ajoutez 'libelle' ici
        'reference',
        'categorie_id',
        
        'prix',
        'quantite',
        'photo',
    ];
  public function fournisseur(){
return $this->belongsToMany(Fournisseur::class,'fournisarticles','article_id', 'fournisseur_id');
}
public function categorie(){
    return $this->belongsTo(Categorie::class);
    }
}
