<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllergenResource;
use App\Models\Allergen;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Factory as Validator;

class AllergenController extends Controller
{
    public function index(Request $request)
    {
        $allergen = Allergen::query();

        $request->whenFilled('filters', function ($filters) use ($allergen) {
            foreach($filters as $filter => $value)
            {
                switch($filter)
                {
                    case 'name':
                        $allergen->where('name', 'Like', "%{$value}%");
                        break;

                    default:
                        break;
                }
            }
        });

        $request->whenFilled('sortby', function ($sortby) use ($allergen) {
            foreach($sortby as $field => $order)
            {
                switch($field)
                {
                    case 'name':
                        $allergen->orderBy('name', $order);
                        break;

                    default:
                        break;
                }
            }
        });
        
        return [
            'allergens' => AllergenResource::collection($allergen->paginate(config('app.pagination'))->withQueryString())->response()->getData(true),
            'status_code' => 200
        ];
    }

    public function store(Request $request, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'name' => ['required','string', Rule::unique('allergens', 'name')],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $allergen = Allergen::create($validate->validated());

            return response()->json([
                'allergen_id' => $allergen->id,
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 422
            ], 422);
        }
    }

    public function show(Allergen $allergen)
    {
        return response()->json([
            'allergen'=> new AllergenResource($allergen),
            'status_code' => 200
        ], 200);
    }

    public function update(Request $request, Allergen $allergen, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'name' => ['required','string', Rule::unique('ingredients', 'name')->ignore($allergen->id)],
            'measurement_unit' => ['required', 'exists:measurement_units'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $allergen->update($validate->validated());

            $allergen->refresh();
            return response()->json([
                'data' => new AllergenResource($allergen),
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
        Allergen::findOrFail($id)->delete();
        return response()->json([
            'data' =>[
                'message' => "Allergen with ID: {$id} has been deleted.",
                'id' => $id
            ],
            'status_code' => 200
        ], 200);
    }
}
