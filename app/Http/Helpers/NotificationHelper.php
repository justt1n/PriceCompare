<?php
namespace App\Http\Helpers;
use Illuminate\Support\Facades\Session;
class NotificationHelper {
    public static function errorNotification($message)
    {
        Session::flash('message', $message);
        Session::flash('alert-type', 'alert-error');
    }

    public static function successNotification($message) {
        Session::flash('message', $message);
        Session::flash('alert-type','alert-success');
    }

    public static function warningNotification($message) {
        Session::flash('message', $message);
        Session::flash('alert-type','alert-warning');
    }
}
?>