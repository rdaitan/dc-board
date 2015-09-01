<?php
class Comment extends AppModel
{
    public $validation = array(
        'body'          => array(
            'length'    => array('validate_between', 1, 200),
        ),
    );

    public function __construct(array $data = array())
    {
        parent::__construct($data);

        if(!isset($this->user_id)) return;

        $user = User::get($this->user_id);
        $this->username = $user->username;
    }

    public static function countAll($thread_id)
    {
        $db = DB::conn();
        return $db->value("SELECT COUNT(*) FROM comment WHERE thread_id={$thread_id}");
    }

    public static function getAll($thread_id, $offset, $limit)
    {
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE thread_id={$thread_id} ORDER BY created ASC LIMIT {$offset}, {$limit}");

        $comments = array();
        foreach ($rows as $row) {
            $comments[] = new Comment($row);
        }

        return $comments;
    }

    public function create(Thread $thread)
    {
        if(!$this->validate()) {
            throw new ValidationException('Invalid comment.');
        }

        $values = array(
            'thread_id' => $thread->id,
            'user_id'   => $this->user_id,
            'body'      => $this->body
        );

        $db = DB::conn();
        $db->insert('comment', $values);
    }

}
