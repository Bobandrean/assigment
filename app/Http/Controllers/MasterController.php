<?php

namespace App\Http\Controllers;

use App\Repositories\Master\MasterRepository;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    private $MasterRepository;

    public function __construct(MasterRepository $MasterRepository)
    {
        $this->MasterRepository = $MasterRepository;
    }

    public function index()
    {
        return $this->MasterRepository->getData();
    }

    public function create(request $request)
    {
        return $this->MasterRepository->createMaster($request);
    }

    public function update($id,request $request)
    {
        return $this->MasterRepository->updateMaster($id,$request);
    }

    public function delete($id)
    {
        return $this->MasterRepository->deleteMaster($id);
    }

    public function detail($id)
    {
        return $this->MasterRepository->detailMaster($id);
    }
}
