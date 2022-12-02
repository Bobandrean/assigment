<?php

namespace App\Repositories\Master;

use LaravelEasyRepository\Repository;

interface MasterRepository extends Repository{

    public function getData();
    public function createMaster($request);
    public function updateMaster($id,$request);
    public function deleteMaster($id);
    public function detailMaster($id);
    // Write something awesome :)
}
