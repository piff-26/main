<?php

namespace App\Http\Controllers;

use App\Utils\HttpResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    protected $model;
    use HttpResponse;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success($this->model->with($this->model->relations())->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only($this->model->getFillable());

        $valid = Validator::make($data, $this->model->validationRules(), $this->model->validationMessages());

        if ($valid->fails()) {
            return $this->error($valid->errors()->first());
        }

        $model = $this->model->create($data);

        return $this->success($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->only($this->model->getFillable());

        $valid = Validator::make($data, $this->model->validationRules(), $this->model->validationMessages());

        if ($valid->fails()) {
            return $this->error($valid->errors()->first());
        }

        $update = $this->model->find($request->id);
        if (!$update) {
            return $this->error('ID not found !');
        }

        $update->update($data);

        return $this->success($update);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->error('ID not found !');
        }
        $data->delete();

        return $this->success(['message' => 'Successfully deleting data !']);
    }
}