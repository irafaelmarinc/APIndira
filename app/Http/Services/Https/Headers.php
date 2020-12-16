<?php

namespace App\Http\Services\Https;

use App\Constants\Code;
use Illuminate\Http\Request;

class Headers
{
    function getLanguage() {
        $request  = Request::capture();
        $language = $request->header('Accept-Language');
        $codeLang = (!in_array($language, array_keys(Code::LANGUAGES))) ? 'es' : $language;
        return $codeLang;
    }
}
