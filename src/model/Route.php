<?php

namespace model;

enum Route: string {
    case HOME = "/";

    case LOGIN = "/login";

    case REGISTER = "/register";

    case PROFILE = "/profile";
}