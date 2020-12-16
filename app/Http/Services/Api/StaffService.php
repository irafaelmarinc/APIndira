<?php

namespace App\Http\Services\Api;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffService
{
    function getStaff($conditions) {
        try {
            $response = Staff::where($conditions)->get();
            return (count($response) > 0) ? json_decode(json_encode($response), true)[0] : [];
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
    }
}