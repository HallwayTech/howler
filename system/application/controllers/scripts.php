<?php
class Scripts extends Controller
{
    function Scripts() {
        parent::Controller();
    }

    function index() {
        /**
         * Stream scripts out as one file to cut down the number of connections on
         * initial page load of the site.
         *
         * Note: not using include or require because they both evaluate the code.
         */
        $libraries = array('lib/jquery-1.3.2.min.js', 'lib/jquery-ui-1.7.1.custom.min.js', 'lib/swfobject-2.2.min.js');
        $script_names = array('js/playlist', 'js/player', 'js/collection', 'js/actions');

        // echo libraries untransformed
        foreach ($libraries as $script) {
            $this->_echo_script($script, false);
        }

        // echo local script conditionally transformed
        foreach ($script_names as $script) {
            $this->_echo_script($script);
        }
    }

    function _echo_script($script, $transform = true) {
        $name = $script;
        if ($transform) {
//            if (MODE == 'dev') {
                $name = "$script.js";
//            } else {
//              $name = "$script.min.js";
//            }
        }

        if (is_file($name)) {
            echo "/*************************\n$name\n**************************/";
            readfile($name);
        } else {
            echo "/* unable to read $name */";
        }
    }
}
