<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ResourceArticle;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Fournisarticles;
use App\Models\Fournisseur;
use App\Http\Resources\RessourceAll;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ArticleController extends Controller
{
    public function index()
    {
    // code
    }


    public function show($id)
    {
        // code
    }

    public function store(ArticleRequest $request)
    {
        try {

            $photoname = Str::random(30) . "." . $request->photo->getClientOriginalExtension();

            $libelle = $request->input('libelle');
            $categorie = Categorie::findOrFail($request->input('categorie_id'));
            $selectedFournisseurs = $request->input('fournisseurs');
            $numero_ordre = Article::where('categorie_id', $categorie->id)->count() + 1;
            $prix = $request->input('prix');
            $stock = $request->input('quantite');
            $reference = 'REF-' . strtoupper(substr($libelle, 0, 3))
                . '-' . strtoupper(($categorie->libelle)) . '-' . $numero_ordre;

            if (Article::where(['libelle' => $libelle, 'categorie_id' => $categorie->id,])->exists()) {
                return response()->json([
                    'message' => 'Une ligne avec la même référence existe déjà.',
                    'error' => true,
                ], 400);
            }
            
            Storage::disk('public')->put($photoname, file_get_contents($request->photo));


            $article = new Article([
                'libelle' => $libelle,
                'reference' => $reference,
                'categorie_id' => $categorie->id,
                'prix' => $prix,
                'quantite' => $stock,
                'photo' =>  $photoname
            ]);

            $article->save();
            $article->fournisseur()->attach($selectedFournisseurs);

            return response()->json([
                'status' => 'success',
                'message' => 'Article créé avec succès',
                'data' => new ResourceArticle($article),
                

            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message'
            => 'Erreur lors de la création de l\'article', 'error' => $e->getMessage()], 500);
        }
    }


    public function getCategoriesArticlesFournisseurs()
    {
        $categories = Categorie::all();
        $articles = Article::all();
        $fournisseurs = Fournisseur::all();

        return response()->json([
            'categories' => $categories,
            'article' => new ResourceArticle($articles),
            'fournisseurs' => $fournisseurs,
        ]);
    }

    public function update(ArticleRequest $request,  $id)
    {
       

        try {
            // dd('salut');
            // ... Validation et autres vérifications ...

            // $articleId = $request->input('article_id'); // ID de l'article à modifier

            $article = Article::find($id); 
            if (!$article) {
                return response()->json([
                    'message' => 'Article non trouvé',
                    'error' => true,
                ], 404);
            }

            $article->libelle = $request->input('libelle');
            $article->categorie_id = $request->input('categorie_id');
            $article->prix = $request->input('prix');
            $article->quantite = $request->input('quantite');

            if ($request->hasFile('photo')) {
                $photoFile = $request->file('photo');
                $photoPath = $photoFile->store('photos', 'public');
                $article->photo = $photoPath;
            }
            $categorie = Categorie::findOrFail($request->input('categorie_id'));
            $numero_ordre = Article::where('categorie_id', $categorie->id)->count() + 1;
            $reference = 'REF-' . strtoupper(substr($article->libelle, 0, 3))
             . '-' . strtoupper(($categorie->libelle)) . '-' . $numero_ordre;

            $article->reference = $reference;
            $article->save();

            $fournisseurIds = $request->input('fournisseurs');
            $article->fournisseur()->sync($fournisseurIds);

            return response()->json([
                'status' => 'success',
                'message' => 'Article modifié avec succès',
                'article' => new ResourceArticle($article),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function paginateArticle($perPage)
    {
        $categories = Article::paginate($perPage);
        return ResourceArticle::collection($categories);
    }
    //
}
