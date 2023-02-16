<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\ChangeStatusRequest;
use App\Http\Requests\Recipe\StoreRequest;
use App\Models\Recipe;
use App\Models\RecipeItem;

class RecipeController extends Controller
{
    public function my(){
        $recipes = Recipe::
            select([
              'id',
              'title',
              'description',
              'status'
            ])
            ->with([
              'items' => function($q){
                  $q->select([
                      'recipe_id',
                      'name',
                      'count',
                      'category_id',
                  ]);
              },
              'address' => function($q){
                  $q->select([
                      'model_class',
                      'model_id',
                      'city',
                      'district',
                      'neighbourhood',
                      'address_detail',
                  ]);
              },
            ])
            ->where(['created_by' => auth()->user()->id])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            "status" => true,
            "data" => $recipes,
        ]);
    }

    public function latests(){
        $recipes = Recipe::
            select([
                'id',
                'title',
                'description',
                'status'
            ])
            ->with([
                'items' => function($q){
                    $q->select([
                        'recipe_id',
                        'name',
                        'count',
                        'category_id',
                    ]);
                },
                'address' => function($q){
                    $q->select([
                        'model_class',
                        'model_id',
                        'city',
                        'district',
                        'neighbourhood',
                        'address_detail',
                    ]);
                },
            ])
            ->orderByDesc('id')
            ->latest()
            ->take(100)
            ->get();

        return response()->json([
            "status" => true,
            "data" => $recipes,
        ]);
    }

    public function all(){
        $per_page = is_numeric(request('per_page')) ? request('per_page') : 15;
        $page = is_numeric(request('page')) ? request('page') : 1;
        
        $recipes = Recipe::
            select([
                'id',
                'title',
                'description',
                'status'
            ])
            ->with([
                'items' => function($q){
                    $q->select([
                        'recipe_id',
                        'name',
                        'count',
                        'category_id',
                    ]);
                },
                'address' => function($q){
                    $q->select([
                        'model_class',
                        'model_id',
                        'city',
                        'district',
                        'neighbourhood',
                        'address_detail',
                    ]);
                },
            ])
            ->orderByDesc('id')
            ->paginate($per_page);

        return response()->json([
            "status" => true,
            "data" => $recipes,
        ]);
    }

    public function store(StoreRequest $request){
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
                    'address_detail' => $request->get('address')['address_detail'],
                    'neighbourhood' => $request->get('address')['neighbourhood'],
                    'model_class' => $recipe::class,
                ]);
                $items = collect($request->get('items'))->map(fn($item) => ['recipe_id' => $recipe->id, 'name' => $item['text'], 'count' => $item['count'], 'category_id' => $item['category_id']]);
                $recipe->items()->insert($items->toArray());
            }

            return response()->json([
                "status" => true ,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kayıt başarıyla eklendi",
                    "type" => "success",
                ]
            ]);
        }catch (\Exception $exception){
            if ($recipe){
                $recipe->delete();
            }

            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "İşlem başarısız oldu",
                    "type" => "error",
                ]
            ], 500);
        }
    }

    public function change_status(ChangeStatusRequest $request){
        try {
            $recipe = Recipe::find($request->get('id'));
            $recipe->fill([
                'status' => 1,
            ]);
            $recipe->save();

            return response()->json([
                "status" => true ,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kayıt başarıyla eklendi.",
                    "type" => "success",
                ]
            ]);
        }catch (\Exception $exception){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "İşlem başarısız oldu",
                    "type" => "error",
                ]
            ], 500);
        }
    }

    public function recipe_item_categories(){
        $categories = RecipeItem::categories();

        return response()->json([
            "status" => true,
            "data" => $categories,
        ]);
    }
}
