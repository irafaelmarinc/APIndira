<?php

namespace App\Http\Services\i18n;

use App\Constants\Code;
use App\Constants\Constants;
use App\Http\Services\Https\Headers;

class LanguageService extends Headers
{
    protected $namespaceLanguage = Constants::LANGUAGE_NAMESPACE;
    protected $defaultLanguage   = "\App\Constants\languages\Spanish";

    function getTranslate($constant) {
        $this->setNamespaceLanguage();
        return $this->getConstantValue($constant);
    }

    private function setNamespaceLanguage() {
        $class = str_replace('[className]', Code::LANGUAGES[$this->getLanguage()], $this->namespaceLanguage);
        if (class_exists($class)) {
            $this->defaultLanguage = $class;
        }
    }

    private function getConstantValue($constantName) {
        set_error_handler(function($errstr) {
            throw new \Exception($errstr);
        });
        try {
            $translateValue = constant($this->defaultLanguage . "::" . $constantName);
        } catch (\Exception $e) {
            $translateValue = $constantName;
        }
        restore_error_handler();
        return $translateValue;
    }
}
