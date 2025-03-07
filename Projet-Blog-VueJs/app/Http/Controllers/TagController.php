<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;



class TagController extends Controller
{
    public function index() {
        $tags = Tag::all();
        return response()->json($tags); // JSON response for Vue.js
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json($tag, 201);
    }

        public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }



    public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
    
            $tag = Tag::findOrFail($id);
            $tag->update([
                'name' => $request->name,
            ]);
    
            return response()->json($tag);
        }


        public function destroy($id)
        {
            $tag = Tag::findOrFail($id);
            $tag->delete();

            return response()->json(['message' => 'Category deleted successfully']);
        }



}
    

   
   
