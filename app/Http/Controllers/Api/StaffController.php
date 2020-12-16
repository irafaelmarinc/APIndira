<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Services\Tokens\lcobucci;
use App\Http\Services\Api\StaffService;
use App\Http\Controllers\CommonController;

class StaffController extends CommonController
{
    function SignIn(Request $request) {
        try {
            $this->ValidationRequest($request, 'SIGN_IN_STAFF');

            $conditions = [
                ['ci', $request['ci']],
                ['status', 1],
            ];

            $staff_srv = new StaffService();
            $staff = $staff_srv->getStaff($conditions);

            if (!$staff) return $this->ResponseError('UNREGISTERED');

            $this->ValidationGuards($request['password'], $staff['password']);

            $claims = array(
                'ci'        => $staff['ci'], 
                'name'      => $staff['name'], 
                'lastname'  => $staff['lastname'], 
                'rol'       => $staff['fk_rol'], 
            );

            $jwt = new lcobucci();
            $token = $jwt->GenerateToken($claims);

            unset($staff['password']);
            $staff['token'] = $token;

            return $this->ResponseSuccessfully($staff);
        } catch (\Exception $ex) {
            return response()->json(['status' => $ex->getCode(), 'message' => $ex->getMessage()], 422);
        }
    }

    function ValidAt(Request $request) {
        try {
            $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsImp0aSI6IjA5NDA4NzQ5ODUiLCJpYXQiOjE2MDgwMDIzODksIm5iZiI6MTYwODAwMjM4OSwiZXhwIjoxNjA4MDg4Nzg5LCJ1aWQiOnsiY2kiOiIwOTQwODc0OTg1IiwibmFtZSI6IktldmluIFJhZmFlbCIsImxhc3RuYW1lIjoiTWFyw61uIENvYmXDsWEiLCJyb2wiOjF9fQ.OlF7QfdFLMuzDk1vn7AgXShsyhg7IoQErMjKKGTiH88";
            $jwt = new lcobucci();
            $token = $jwt->ValidateToken($token);
            return $token;            
        } catch (\Exception $ex) {
            return response()->json(['status' => $ex->getCode(), 'message' => $ex->getMessage()], 422);
        }
    }
}
