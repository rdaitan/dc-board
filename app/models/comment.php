<?php
class Comment extends AppModel
{
    const MIN_BODY_LENGTH = 1;
    const MAX_BODY_LENGTH = 200;

    public $validation = array(
        'body' => array('length' => array('validate_between', self::MIN_BODY_LENGTH, self::MAX_BODY_LENGTH)),
    );

    public function __construct(array $data = array())
    {
        parent::__construct($data);

        if (!isset($this->user_id)) {
            return;
        }

        $user = User::getById($this->user_id);
        $this->username = $user->username;
    }

    public static function countAll($thread_id)
    {
        $db = DB::conn();
        return $db->value("SELECT COUNT(*) FROM comment WHERE thread_id=?",
            array($thread_id));
    }

    public static function getAll($thread_id, $offset, $limit)
    {
        $db     = DB::conn();
        $rows   = $db->rows(
            sprintf("SELECT * FROM comment WHERE thread_id=%d LIMIT %d, %d",
                $thread_id, $offset, $limit));

        $comments = array();

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }

        return $comments;
    }

    public function create(Thread $thread)
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid comment.');
        }

        $db = DB::conn();
        $db->insert('comment', array(
            'thread_id' => $thread->id,
            'user_id'   => $this->user_id,
            'body'      => $this->body
        ));
    }

}
