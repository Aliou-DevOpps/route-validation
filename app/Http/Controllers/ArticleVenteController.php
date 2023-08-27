<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleVenteRequest;
use App\Http\Resources\ArticleVenteResource;
use App\Models\Article;
use App\Models\ArticleVente;
use App\Models\CategorieVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class ArticleVenteController extends Controller
{
    //
    public function store(ArticleVenteRequest $request)
    {
    dd('sava'); 
        try {
            $photoname = Str::random(30) . "." . $request->photo->getClientOriginalExtension();
    
            $libelle = $request->input('libelle');
            $categorie = CategorieVente::findOrFail($request->input('categorie_id'));
            $selectedArticles = $request->input('articles');
            $numero_ordre = ArticleVente::where('categorievente_id', $categorie->id)->count() + 1;
            $stock = $request->input('qtstock');
            $marge=$request->input('marge');
            $reference = 'REF-' . strtoupper(substr($libelle, 0, 3))
                . '-' . strtoupper(($categorie->libelle)) . '-' . $numero_ordre;
    
            if (ArticleVente::where(['libelle' => $libelle, 'categorie_id' => $categorie->id])->exists()) {
                return response()->json([
                    'message' => 'Une ligne avec la même référence existe déjà.',
                    'error' => true,
                ], 400);
            }
            $prixTotal = 0;
            foreach ($selectedArticles as $articleId) {
                $article = Article::find($articleId);
                if ($article) {
                    $prixTotal += $article->prix;
                }
            }
            Storage::disk('public')->put($photoname, file_get_contents($request->photo));
    
            $article = new ArticleVente([
                'libelle' => $libelle,
                'reference' => $reference,
                'categorievente_id' => $categorie->id,
                'prix' => $prixTotal ,
                'qtstock' => $stock,
                'photo' =>  $photoname,
                'marge'=>$marge
            ]);
    
            $article->save();
    
            $article->article()->attach($selectedArticles);
    
            $categorie->updateTotalPrice();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Article créé avec succès',
                'data' => new ArticleVenteResource($article),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de l\'article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function update(ArticleVenteRequest $request, $id)
    {
        try {
            $article = ArticleVente::find($id);
    
            if (!$article) {
                return response()->json([
                    'message' => 'Article non trouvé',
                    'error' => true,
                ], 404);
            }
    
            $photoname = $article->photo;
            $selectedArticles = $request->input('articles');
    
    
            if ($request->hasFile('photo')) {
                Storage::disk('public')->delete($photoname);
    
                $photoFile = $request->file('photo');
                $photoname = Str::random(30) . "." . $photoFile->getClientOriginalExtension();
                $photoPath = $photoFile->storeAs('photos', $photoname, 'public');
                $article->photo = $photoPath;

            }
    
            $categorie = CategorieVente::findOrFail($request->input('categorie_id'));
            $numero_ordre = ArticleVente::where('categorievente_id', $categorie->id)->count() + 1;
            $reference = 'REF-' . strtoupper(substr($article->libelle, 0, 3))
             . '-' . strtoupper(($categorie->libelle)) . '-' . $numero_ordre;
    
            $article->libelle = $request->input('libelle');
            $article->qtStock = $request->input('qtstock');
            $article->reference = $reference;
            $article->photo = $photoname;
    
            $prixTotal = 0;
            foreach ($selectedArticles as $articleId) {
                $articleAssocie = Article::find($articleId);
                if ($articleAssocie) {
                    $prixTotal += $articleAssocie->prix;
                }
            }
            $article->prix = $prixTotal;
    
            $article->save();
            $article->article()->sync($selectedArticles);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Article modifié avec succès',
                'article' => new ArticleVenteResource($article),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
{
    try {
        $article = ArticleVente::find($id);

        if (!$article) {
            return response()->json([
                'message' => 'Article non trouvé',
                'error' => true,
            ], 404);
        }

        $photoname = $article->photo;
        if ($photoname) {
            Storage::disk('public')->delete($photoname);
        }

        $article->article()->detach();

        $article->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Article supprimé avec succès',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la suppression de l\'article',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function paginateArticle($perPage)
    {
        $articles = ArticleVente::paginate($perPage);
        return ArticleVenteResource::collection($articles);
    }


}
