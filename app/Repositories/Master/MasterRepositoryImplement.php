<?php

namespace App\Repositories\Master;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Master;
use App\Http\Controllers\BaseController;


class MasterRepositoryImplement extends Eloquent implements MasterRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Master $model)
    {
        $this->model = $model;
    }

    public function getData(){
        $query = $this->model->where('hapus','0')->paginate(10);

        if(empty($query)){
            return BaseController::error(NULL,"Data tidak ditemukan", 400);
        }

        return BaseController::success($query,"Sukses menarik data", 200);
    }

    public function createMaster($request){
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'content' => 'required',
            'article_image' => 'required',
            'article_creator' => 'required'
        ]);

        if($validator->fails()) {
            return BaseController::error($validator->errors(), 'Validation Error',400);
        }

        try {
            $input = new $this->model();
            $input->title = $request->title;
            $input->content = $request->content;
            $input->article_creator = $request->article_creator;
            if($input->article_image = $request->hasFile('article_image')) {
                $file = $request->file('article_image') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images' ;
                $file->move($destinationPath,$fileName);
                $input->article_image = $destinationPath;
        }

            $input->save();

        }catch(\Exception $e){

            return BaseController::error(NULL, $e->getMessage(),400);
        }

        return BaseController::success($input,"sukses menambah data",200);
    }
    public function updateMaster($id,$request)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'content' => 'required',
            'article_image' => 'required',
            'article_creator' => 'required'
        ]);

        if($validator->fails()) {
            return BaseController::error($validator->errors(), 'Validation Error',400);
        }

        try {
            $input = $this->model::where('id', '=', $id)->first();
            $input->title = $request->title;
            $input->content = $request->content;
            if($input->article_image = $request->hasFile('article_image')) {
                $file = $request->file('article_image') ;
                $fileName = $file->getClientOriginalName() ;
                $destinationPath = public_path().'/images' ;
                $file->move($destinationPath,$fileName);
                $input->article_image = $destinationPath;
        }
            $input->article_creator = $request->article_creator;
            $input->save();

        }catch(\Exception $e){

            return BaseController::error(NULL, $e->getMessage(),400);
        }

        return BaseController::success($input,"sukses menambah data",200);
    }

    public function deleteMaster($id)
    {
        $query = $this->model::where('id', '=', $id)->first();

        if (is_null($query)) {
            return BaseController::error(NULL, "Data tidak ditemukan", 400);
        }

        try {
            $query->hapus = 1;
            $query->save();
        } catch (\Exception $e) {

            return BaseController::error(NULL, $e->getMessage(), 400);
        }
        return BaseController::success($query, "Sukses menghapus data", 200);
    }

    public function detailMaster($id)
    {
        $query = $this->model::where('id', '=', $id)->where('hapus', '=', '0')->first();

        if (empty($query)) {
            return BaseController::error(NULL, "Data tidak ditemukan", 400);
        }

        return BaseController::success($query, "Sukses menarik data", 200);
    }
    // Write something awesome :)
}
