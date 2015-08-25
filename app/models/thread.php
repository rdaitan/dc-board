<?php
class Thread extends AppModel {
    public static function getAll() {
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM thread');

        $threads = array();
        foreach ($rows as $row) {
            $threads[] = new Thread($row);
        }

        return $threads;
    }
}
