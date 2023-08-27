<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CategorieVente;
class ArticleVente extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $fillable = [
        'libelle',
        'prix',
        'qtStock',
        'statut',
        'promo',
        'marge',
        'photo',
        'reference',
        'categorie_vente_id', // Remarque : Assurez-vous d'avoir la bonne colonne de clé étrangère ici
    ];
    public function categorievente()
      {
        return $this->belongsTo(CategorieVente::class);
        }
        public function article(){
        return $this->belongsToMany(Article::class,'articlebetwens','article_id','articlevente_id');
        }    
}
