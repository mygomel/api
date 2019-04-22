<?php
/**
 * Created by Maus 23.03.2019 6:35 mygomel@gmail.com
 */


namespace App;


class Func
{
    public static function no_enter($pass)
    {
        //global $_COOKIE, $_GET;
        if (!isset($_COOKIE["pass"]) OR $_COOKIE["pass"] != $pass) {

            if (isset($_POST['pass']) AND $_POST['pass'] == $pass) {
                setcookie("pass", $pass, time() + 3600 * 24 * 365);
            } else {
                echo "
                <center><br><br><br><form method='post'>
                <input type=text name=pass>
                <input type=submit value='ok'>
                </form>";
                exit();
            }
        }
    }

}