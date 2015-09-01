<?php
class Thread extends AppModel
{
    public $validation = array(
        'title' => array('length' => array('validate_between', 1, 30)),
    );

    public static function getAll($offset, $limit)
    {
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread ORDER BY id DESC LIMIT {$offset}, {$limit}");

        $threads = array();
        foreach ($rows as $row) {
            $threads[] = new self($row);
        }

        return $threads;
    }

    public static function get($id)
    {
        $db = DB::conn();

        $row = $db->row('SELECT * FROM thread WHERE id=?', array($id));
        if(empty($row))
            throw new RecordNotFoundException('No record found');

        return new self($row);
    }


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

    public static function countAll()
    {
        $db = DB::conn();
        return $db->value("SELECT COUNT(*) FROM thread");
    }

}
