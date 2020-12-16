<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use App\Http\Services\i18n\LanguageService;

class CommonController extends Controller
{
    function ValidationRequest($request, $rule) {
        $validator = \Validator::make($request->all(), Config::get("validations." . $rule));
        if ($validator->fails()) {
            $message = $validator->errors()->first();
            throw new \Exception($message);
        }
    }

    function ValidationGuards($text, $encrypt) {
        $verify = password_verify($text, $encrypt);
        if (!$verify) throw new \Exception($this->getConstantsTranslate('INVALID_CREDENTIALS'));
    }

    function getConstantsTranslate($message) {
        $translate = new LanguageService();
        return $translate->getTranslate($message);
    }

    function ResponseSuccessfully($payload = [], $constant = "SUCCESSFULLY_REQUEST", $http_code = 200, $headers = []) {
        $message = array("status" => 1, 'code' => $http_code, 'message' => $this->getConstantsTranslate($constant), 'data' => !empty($payload) ? $payload : []);
        return Response::json($message, $http_code, $headers);
    }

    function ResponseError($constant = 'TRY_AGAIN_LATER', $http_code = 422, $headers = []) {
        $message = array("status" => 0, 'code' => $http_code, 'message' => $this->getConstantsTranslate($constant));
        return Response::json($message, $http_code, $headers);
    }

}
