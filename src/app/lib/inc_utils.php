<?php
if (!defined('INC_UTILS')) {
    define('INC_UTILS', true);
    
    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        echo "<script>console.log('Debug Objects:" . $output . "/end' );</script>";
    }
}
