<?php

define("__APPDIR__", __DIR__);

session_start();

function h($str) {
    return htmlspecialchars($str, ENT_COMPAT | ENT_HTML5 | ENT_QUOTES);
}
