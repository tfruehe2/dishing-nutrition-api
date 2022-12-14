<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Factory as Validator;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $recipes = Recipe::with(['ingredients', 'tags']);

        $request->whenFilled('filters', function ($filters) use ($recipes) {
            foreach($filters as $filter => $value)
            {
                switch($filter)
                {
                    case 'name':
                        $recipes->where('name', 'Like', "%{$value}%");
                        break;
                    
                    case 'ingredients':
                        $ingredients = explode(',', $value);
                        $recipes->whereHas('ingredients', function($query) use ($ingredients) {
                            foreach($ingredients as $ingredient)
                            {
                                $query->where('name', $ingredient);
                            }  
                        });

                    default:
                        break;
                }
            }
        });

        $request->whenFilled('sortby', function ($sortby) use ($recipes) {
            foreach($sortby as $field => $order)
            {
                switch($field)
                {
                    case 'name':
                        $recipes->orderBy('name', $order);
                        break;

                    default:
                        break;
                }
            }
        });
        
        return RecipeResource::collection($recipes->paginate(config('app.pagination'))->withQueryString())->response()->getData(true);
    }

    public function store(Request $request, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'name' => ['required','string', Rule::unique('recipes', 'name')],
            'description' => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.order' => ['required', 'integer'],
            'ingredients.*.measurement_unit_id' => ['required', 'integer'],
            'ingredients.*.quanitity' => ['required', 'numeric'],
            'tag_ids' => ['sometimes', 'array']
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $recipe = Recipe::create($validate->validated());

            return response()->json([
                'recipe_id' => $recipe->id,
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 422
            ], 422);
        }
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'tags']);

        return response()->json([
            'recipe'=> new RecipeResource($recipe),
            'status_code' => 200
        ], 200);
    }

    public function update(Request $request, Recipe $recipe, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'name' => ['required','string', Rule::unique('recipes', 'name')->ignore($recipe->id)],
            'description' => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.order' => ['required', 'integer'],
            'ingredients.*.measurement_unit_id' => ['required', 'integer'],
            'ingredients.*.quanitity' => ['required', 'numeric'],
            'tag_ids' => ['sometimes', 'array']
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $recipe->update($validate->validated());

            $recipe->refresh();
            $recipe->load(['ingredients', 'tags']);
            return response()->json([
                'allergen' => new RecipeResource($recipe),
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 422
            ], 422);
        }
    }

    public function destroy($id)
    {
        Recipe::findOrFail($id)->delete();
        return response()->json([
            'data' =>[
                'message' => "Recipe with ID: {$id} has been deleted.",
                'id' => $id
            ],
            'status_code' => 200
        ], 200);
    }
}
