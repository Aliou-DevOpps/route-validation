<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorieRequest;
use App\Http\Requests\GenericValidationRequest;
use Illuminate\Http\Request;
use App\Models\CategorieVente;
use App\Http\Resources\ResourceCategorie;

class CategorieVenteController extends Controller
{
    public function index(){
    $categorie=CategorieVente::all();
    return $categorie;
            // $photoname=Str::random(30).".".$request->photo->getClientOriginalExtension();

            // // Storage::disk('public')->put($fileName, $photoData);
 // // Enregistrez l'image dans le stockage public
//             // $fileName = uniqid() . '.jpg'; // Générez un nom de fichier unique
//             // // Storage::disk('public')->put($fileName, $photoData);
//             // Storage::disk('public')->put($photoname,file_get_contents($request->photo));
//          $photoname=$request->validated('photo');
//           $image=  $photoname->store('blog','public');
// //  dd(  $image);
    
    }
    public function paginateCategories( $perPage )
    {
        $categories = CategorieVente::paginate($perPage);
        return ResourceCategorie::collection($categories);
    }
    public function store(CategorieRequest $request)
    {
        $categorie = CategorieVente::create($request->all());
        $updatedCategories = CategorieVente:: where('libelle',$categorie)->orderBy('created_at', 'desc')->get();
    
        return response()->json([
            'message' => 'Catégorie ajoutée avec succès.',
            'categories' => $updatedCategories,
        ]);
    }
    
    

    public function update(CategorieRequest $request, $id)
{
    $categorie = CategorieVente::findOrFail($id);
    
   
    $newLibelle = $request->input('libelle');
    $existingCategorie = CategorieVente::where('libelle', $newLibelle)->where('id', '!=', $id)->first();

    if ($existingCategorie) {
        return response()->json(['exists' => true, 'message' => 'Le libellé existe déjà.'], 400);
    }
    
    $categorie->libelle = $newLibelle;
    $categorie->save();
    
    return response()->json([
        'message' => 'Libellé de la catégorie mis à jour avec succès.',
        'categorie' => $categorie,
    ]);
}


    public function destroy(CategorieRequest $request) {
        $this->validate($request, [
            'ids' => 'required|array',        ]);
    
        $errors = [];
    
        foreach ($request->ids as $id) {
            $categorie = CategorieVente::find($id);
    
            if (!$categorie) {
                $errors[] = "La catégorie ID $id n'existe pas";
                continue;
            }
    
            $categorie->delete();
        }
    
        if (count($errors) > 0) {
            return response()->json(['errors' => $errors], 422);
        }
    
        return response()->json([
            'message' => 'Catégories supprimées'
        ], 200);
    }
    
    public function search(string $recher)
    {
        $categorie = CategorieVente::where("libelle", $recher)->first();
        $exists = $categorie ? true : false;
        
        return response()->json([
            'exists' => $exists
        ]);
    }
    
}

