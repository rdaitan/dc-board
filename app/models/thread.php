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

    public static function get($id) {
       $db = DB::conn();

       $row = $db->row('SELECT * FROM thread WHERE id=?', array($id));
       if(empty($row))
           throw new RecordNotFoundException('No record found');

       return new self($row);
   }
}
