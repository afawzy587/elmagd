<?php if (!defined("inside")) exit;
if (!isset($_SESSION)) {
    session_start();
}
class systemMoney_transfers
{
    var $tableName     = "money_transfers";

    function getsiteMoney_transfers($addon = "")
    {
        $query = $GLOBALS['db']->query("SELECT * FROM `" . $this->tableName . "` WHERE `deposits_status` != '0'  ORDER BY `deposits_sn`  DESC " . $addon);
        $queryTotal = $GLOBALS['db']->resultcount($query);
        if ($queryTotal > 0) {
            return ($GLOBALS['db']->fetchlist());
        } else {
            return null;
        }
    }

}
