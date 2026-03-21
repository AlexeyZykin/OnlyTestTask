<?php

namespace core;

use Exception;

class RequestFactory {

    /**
     * @throws Exception
     */
    public function create(string $className) {
        if (!class_exists($className)) {
            throw new Exception("Фабрика не может создать несуществующий dto класс: $className");
        }

        $data = array_merge($_GET, $_POST);

        return new $className($data);
    }

}