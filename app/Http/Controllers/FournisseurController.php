<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    //
    public function index(){
    $fournisseur=Fournisseur::all();
    
    return response()->json($fournisseur);
    }
    public function rechercherFournisseurs(Request $request) {
        $recherche = $request->input('recherche');
        $fournisseurs = Fournisseur::where('nom', 'LIKE', "%$recherche%")->get();
        return response()->json($fournisseurs);
    }
}
