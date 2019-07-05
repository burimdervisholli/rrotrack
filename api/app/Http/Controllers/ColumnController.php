<?php

namespace App\Http\Controllers;

use Task;
use Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColumnController extends Controller
{
    public function create(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails())
        {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()->all()
            ], 422);
        }
        else
        {
            $column = Column::create([
                'name'          => $request['name'],
                'order_number'  => $request['order_number'],
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Column created successfully.',
                'column'    => $column,
            ], 200);
        }
    }

    public function edit(Request $request, $id)
    {
        $column = Column::findOrFail($id);

        if($column)
        {
            $validator = $this->validator($request->all());

            if($validator->fails())
            {
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors()->all()
                ], 422);
            }
            else
            {
                $column->name = $request['name'];
                $column->order_number = $request['order_number'];
                $column->save();

                return response()->json([
                    'success'   => true,
                    'message'   => 'Column updated successfully.',
                    'column'    => $column,
                ], 200);
            }
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Column not found.'
            ], 404);
        }
    }

    public function delete($id)
    {

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'         => 'required|string|max:199',
            'order_number' => 'required',
        ]);
    }
}
