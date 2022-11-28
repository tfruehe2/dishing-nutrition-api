<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Factory as Validator;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $ingredients = Ingredient::with(['measurementUnit']);

        $request->whenFilled('filters', function ($filters) use ($ingredients) {
            foreach($filters as $filter => $value)
            {
                switch($filter)
                {
                    case 'name':
                        $ingredients->where('name', 'Like', "%{$value}%");
                        break;

                    default:
                        break;
                }
            }
        });

        $request->whenFilled('sortby', function ($sortby) use ($ingredients) {
            foreach($sortby as $field => $order)
            {
                switch($field)
                {
                    case 'name':
                        $ingredients->orderBy('name', $order);
                        break;

                    default:
                        break;
                }
            }
        });
        
        return [
            'ingredients' => IngredientResource::collection($ingredients->paginate(config('app.pagination'))->withQueryString())->response()->getData(true),
            'status_code' => 200
        ];
    }

    public function store(Request $request, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'name' => ['required','string', Rule::unique('ingredients', 'name')],
            'measurement_unit' => ['required', 'exists:measurement_units'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $ingredient = Ingredient::create($validate->validated());

            return response()->json([
                'ingredient_id' => $ingredient->id,
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 422
            ], 422);
        }
    }

    public function show(Ingredient $ingredient)
    {
        $ingredient->load(['measurementUnit']);

        return response()->json([
            'ingredient'=> new IngredientResource($ingredient),
            'status_code' => 200
        ], 200);
    }

    public function update(Request $request, Ingredient $ingredient, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'name' => ['required','string', Rule::unique('ingredients', 'name')->ignore($ingredient->id)],
            'measurement_unit' => ['required', 'exists:measurement_units'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $ingredient->update($validate->validated());

            $ingredient->refresh();
            $ingredient->load(['measurementUnit']);
            return response()->json([
                'ingredient' => new IngredientResource($ingredient),
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
        Ingredient::findOrFail($id)->delete();
        return response()->json([
            'data' =>[
                'message' => "Ingredient with ID: {$id} has been deleted.",
                'id' => $id
            ],
            'status_code' => 200
        ], 200);
    }
}
