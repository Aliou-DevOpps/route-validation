<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;
    protected $hidden=[ "deleted_at","created_at","updated_at"];
public function article(){
    return $this->belongsToMany(Article::class,'fournisarticles');

}
}
