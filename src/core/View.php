<?php

namespace core;

class View {

    function render(string $template, array $data = []): void {
        $requiredTemplate = __DIR__ . "/../view/" . $template . ".php";

        extract($data);

        require_once $requiredTemplate;
    }

}