<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieVente extends Model
{
    use HasFactory;
    protected $fillable = ['libelle'];
    protected $hidden=[ "deleted_at","created_at","updated_at"];
   
}
