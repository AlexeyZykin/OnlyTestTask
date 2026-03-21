<?php

namespace controller;

use core\View;
use model\Template;

readonly class HomeController {

    public function __construct(private View $view) {}

    public function show(): void {
        $this->view->render(Template::HOME->value);
    }

}