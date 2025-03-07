<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class ArticleController extends Controller
{
    // Show all articles
    // Get articles
    public function index()
    {
        $articles = Article::with(['category', 'tags'])->get();
        return response()->json($articles);
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // image validation
        ]);

        $article = new Article;
        $article->title = $request->title;
        $article->content = $request->content;
        $article->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');  // Storing in 'public' disk
            $article->image = $path;
        }

        $article->save();

        return response()->json($article, 201);
    }

    
    // Delete article
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }



 // Show single article with category and tags
 public function show($id)
 {
     // Use eager loading to load the category and tags with the article
     $article = Article::with('category', 'tags')->findOrFail($id);

     return response()->json($article);
 }

 // Update an article
 public function update(Request $request, $id)
 {
     // Validate the incoming request
     $validator = Validator::make($request->all(), [
         'title' => 'required|string',
         'content' => 'required|string',
         'category_id' => 'required|exists:categories,id',
         'tag_id' => 'required|exists:tags,id', // Ensure the selected tag exists
         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Image validation
     ]);

     // Check if validation fails
     if ($validator->fails()) {
         return response()->json(['errors' => $validator->errors()], 422);
     }

     // Find the article by its ID
     $article = Article::findOrFail($id);

     // Update the article's properties
     $article->title = $request->title;
     $article->content = $request->content;
     $article->category_id = $request->category_id;

     // Handle image upload if a new image is provided
     if ($request->hasFile('image')) {
         // Delete the old image if it exists
         if ($article->image) {
             Storage::disk('public')->delete($article->image);
         }

         // Store the new image and update the article
         $imagePath = $request->file('image')->store('articles_images', 'public');
         $article->image = $imagePath;
     }

     // Save the updated article
     $article->save();

     // Sync the tags (ensure the article has the selected tag)
     $article->tags()->sync([$request->tag_id]);

     // Return the updated article with tags
     return response()->json($article->load('tags'), 200);
 }

      

}
