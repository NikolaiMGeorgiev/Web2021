<?php

    class AppBootStrap {

        public static function init() {

            spl_autoload_register(function ($className) {
                require_once("../src/" . $className . ".php");
            });
        }
    }

?>