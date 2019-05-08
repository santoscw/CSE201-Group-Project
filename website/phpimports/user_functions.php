<?php
    require_once 'phpimports/functions.php';
    
    /**
     * function to update a user with admin permissions
     * 
     * @return string  the error message if the query failed, or success message if it didn't
     */
    function makeAdmin($admin_target) {
        $query = "UPDATE `user` SET `level` = '2' WHERE `user`.`uid` = $admin_target";
        $result = queryData($query);
        if (!$result) {
            return $data->error;
        } else {
            return "Successfully updated.";
        }
    }

    /**
     * function to update a user with moderator permissions
     * 
     * @return string  the error message if the query failed, or success message if it didn't
     */
    function makeMod($mod_target) {
        $query = "UPDATE `user` SET `level` = '1' WHERE `user`.`uid` = $mod_target";
        $result = queryData($query);
        if (!$result) {
            return $data->error;
        } else {
            return "Successfully updated.";
        }
    }

?>