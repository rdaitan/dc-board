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

    public $validation = array(
        'title' => array('length' => array('validate_between', 1, 30)),
    );

    public function create(Comment $comment)
    {
        $this->validate(); // before calling this method, $this->title was set first
        $comment->validate();
        if($this->hasError() || $comment->hasError()) {
            throw new ValidationException('Invalid thread or comment.');
        }

        $db = DB::conn();
        $db->begin();
        $db->insert('thread', array('title' => $this->title));

        // write first comment
        $this->id = $db->lastInsertId();
        $comment->create($this);

        $db->commit();
    }
}
