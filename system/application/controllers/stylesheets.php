<?php
class Stylesheets extends Controller
{
    function Stylesheets() {
        parent::Controller();
    }

    function index() {
        /**
         * Stream stylesheets out as one file to cut down the number of connections on
         * initial page load of the site.
         *
         * Note: not using include or require because they both evaluate the code.
         */
        $stylesheet_names = array('css/howler', 'css/jquery-ui-1.7.1.custom');

        foreach ($stylesheet_names as $stylesheet) {
//            if (MODE == 'dev') {
                $sheet = "$stylesheet.css";
//            } else {
//                $sheet = "$stylesheet.min.css";
//            }

            if (is_file($sheet)) {
                readfile($sheet);
            } else {
                echo "/* ---------- unable to read $sheet ---------- */";
            }
        }
    }
}