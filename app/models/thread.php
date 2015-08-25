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

   public function getComments() {
       $db = DB::conn();
       $rows = $db->rows('SELECT * FROM comment WHERE thread_id=? ORDER BY created ASC', array($this->id));

       $comments = array();
       foreach ($rows as $row) {
           $comments[] = new Comment($row);
       }

       return $comments;
   }

   public function write(Comment $comment) {
       $db = DB::conn();
       $db->query('INSERT INTO comment SET id=?, username=?, body=?, created=NOW()', array($this->id, $comment->username, $comment->body));
   }
}
