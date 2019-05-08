<?php
    require_once 'functions.php';
    
    /**
     * updates a user with admin permissions
     *
     * @param int $admin_target     The id of the user to change the permissions of.
     * @return string
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
     * updates a user with moderator permissions
     *
     * @param int $mod_target   The id of the user to change the permissions of.
     * @return string
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