<?php

class Session
{
    private static $timeout = 900; // 15 minutes in seconds

    public static function update() {

        // remove timeout session
        $session_timeout = self::$timeout;
        DB::execute("DELETE FROM session WHERE TIME_TO_SEC(TIMEDIFF(NOW(), last_visit)) > ${session_timeout}");

        // update current session visit
        $session_id = session_id();
        DB::execute("INSERT INTO session(session_id, last_visit) VALUES('${session_id}', NOW()) ON DUPLICATE KEY UPDATE last_visit=NOW()");
    }

    public static function total() {
        self::update();
        return DB::count('session');
    }
}
