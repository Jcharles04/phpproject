<?php

define("__APPDIR__", __DIR__);

session_start();

function h($str) {
    return htmlspecialchars($str);
}

