<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function my(){

        $recipes = Recipe::
            where(['created_by' => auth()->user()->id])
            ->with([
                'items',
                'address'
            ])
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $recipes]);
    }

    public function latests(){
        $recipes = Recipe::
            with([
                'items',
                'address'
            ])
            ->orderByDesc('id')
            ->latest()
            ->take(100)
            ->get();
        return response()->json(['data' => $recipes]);
    }

    public function all(){
        $recipes = Recipe::
             with([
                'items',
                'address'
            ])
            ->orderByDesc('id')
            ->paginate(100000);
        return response()->json(['data' => $recipes]);
    }

    public function store(Request $request){
        $recipe = null;
        try {
            $recipe = Recipe::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
            ]);

            if ($recipe){
                $recipe->address()->create([
                    'city' => $request->get('address')['city'],
                    'district' => $request->get('address')['district'],
                    'neighbourhood' => $request->get('address')['neighbourhood'],
                    'model_class' => $recipe::class,
                ]);
                $items = collect($request->get('items'))->map(fn($item) => ['recipe_id' => $recipe->id, 'name' => $item['text'], 'count' => $item['count'], 'category_id' => $item['category_id']]);
                $recipe->items()->insert($items->toArray());
            }

            return response()->json([
                'status' => true,
                'message' => 'Kayıt başarıyla eklendi.'
            ]);
        }catch (\Exception $exception){
            if ($recipe){
                $recipe->delete();
            }

            return response()->json([
                'status' => false,
                'message' => 'Teknik bir sorun oluştu, işlem başarısız oldu.'
            ], 500);
        }
    }

    public function support(Recipe $recipe, Request $request){
        try {
            $recipe->fill([
                'supported' => true,
                'pharmacy_note' => $request->get('note'),
            ]);
            $recipe->save();

            return response()->json([
                'status' => true,
                'message' => 'Kayıt başarıyla eklendi.'
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => 'Teknik bir sorun oluştu, işlem başarısız oldu.'
            ], 500);
        }
    }

    public function recipe_item_categories(){
        $categories = RecipeItem::categories();

        return response()->json(['data' => $categories]);
    }
}
