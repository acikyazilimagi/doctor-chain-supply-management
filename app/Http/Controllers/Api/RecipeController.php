<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\ChangeStatusRequest;
use App\Http\Requests\Recipe\StoreRequest;
use App\Models\Recipe;
use App\Models\RecipeItemCategory;

class RecipeController extends Controller
{
    public function my(){
        $recipes = Recipe::
            select([
              'id',
              'title',
              'description',
              'status',
              'created_by',
              'created_at',
              'status_updated_at',
            ])
            ->with([
              'items' => function($q){
                  $q->select([
                      'recipe_id',
                      'name',
                      'count',
                      'category_id',
                  ])->with([
                      'category' => function($q){
                          $q
                              ->select(['id', 'name']);
                      }
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
            ->orderBy('status', 'ASC')
            ->orderBy('id', 'DESC')
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
                'status',
                'created_by',
                'created_at',
                'status_updated_at',
            ])
            ->with([
                'items' => function($q){
                    $q->select([
                        'recipe_id',
                        'name',
                        'count',
                        'category_id',
                    ])->with([
                        'category' => function($q){
                            $q
                                ->select(['id', 'name']);
                        }
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
            ->orderBy('status', 'ASC')
            ->orderBy('id', 'DESC')
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

        $filter = collect(request()->get('filter', []));

        $recipes = Recipe::
            select([
                'id',
                'title',
                'description',
                'status',
                'created_by',
                'created_at',
                'status_updated_at',
            ])
            ->with([
                'items' => function($q) use($filter) {
                    $q
                        ->select([
                            'recipe_id',
                            'name',
                            'count',
                            'category_id',
                        ])
                        ->with([
                            'category' => function($q){
                                $q->select(['id', 'name']);
                            }
                        ]);
                    if ($filter->has('category_id') && $filter->get('category_id') !== null){
                        $q->where('category_id', '=', intval($filter->get('category_id')));
                    }
                },
                'address' => function($q) use($filter) {
                    $q->select([
                        'model_class',
                        'model_id',
                        'city',
                        'district',
                        'neighbourhood',
                        'address_detail',
                    ]);
                    if ($filter->has('address') && is_array($filter->get('address'))){
                        if (array_key_exists('city', $filter->get('address')) && intval($filter->get('address')['city']) > 0){
                            $q->where('city', '=', $filter->get('address')['city']);
                        }

                        if (array_key_exists('district', $filter->get('address')) && strlen($filter->get('address')['district']) > 1){
                            $q->where('district', '=', $filter->get('address')['district']);
                        }
                    }
                },
            ])
            ->where(function ($q) use ($filter) {
                if ($filter->has('title') && strlen($filter->get('title')) > 0){
                    $q->where('title', 'like', '%' . $filter->get('title') . '%');
                }
                if ($filter->has('status') && $filter->get('status') !== null){
                    $q->where(['status' => $filter->get('status')]);
                }
            })
            ->orderBy('status', 'ASC')
            ->orderBy('id', 'DESC')
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
            if ($recipe->created_by === auth()->user()->id){
                $recipe->fill([
                    'status' => 1,
                    'status_updated_at' => now()
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
            }else{
                return response()->json([
                    "status" => false ,
                    "message" => [
                        "title" => "Hata !",
                        "body" => "Hack girişiminde bulundunuz. Size ait olmayan bir kaydı güncelleyemezsiniz !",
                        "type" => "error",
                    ]
                ]);
            }
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
        $categories = RecipeItemCategory::select(['id', 'name'])->get();

        return response()->json([
            "status" => true,
            "data" => $categories,
        ]);
    }
}
