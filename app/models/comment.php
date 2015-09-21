<?php
class Comment extends AppModel
{
    const MIN_BODY_LENGTH   = 1;
    const MAX_BODY_LENGTH   = 200;

    public $validation = array(
        'body' => array(
            'length' => array('validate_between', self::MIN_BODY_LENGTH, self::MAX_BODY_LENGTH)
        ),
    );

    public function __construct(array $data = array())
    {
        parent::__construct($data);

        if (!isset($this->user_id)) {
            return;
        }

        $user       = User::getById($this->user_id);
        $this->user = $user;
    }

    public static function search($query, $offset, $limit)
    {
        $db = DB::conn();
        $rows = $db->rows(
            sprintf(
                "SELECT * FROM comment WHERE body LIKE ? ORDER BY id DESC LIMIT %d, %d",
                $offset,
                $limit
            ),
            array("%{$query}%")
        );

        $results                 = new Search(get_called_class(), $rows);
        $results->total_result   = self::countResults($query);
        return $results;
    }

    public static function countResults($query)
    {
        $db = DB::conn();
        return $db->value("SELECT COUNT(*) FROM comment WHERE body LIKE ?", array("%{$query}%"));
    }

    public static function countAll($thread_id)
    {
        $db = DB::conn();
        return $db->value("SELECT COUNT(*) FROM comment WHERE thread_id=?", array($thread_id));
    }

    public static function countAllAfter($thread_id, $comment_id)
    {
        $db = DB::conn();
        return $db->value(
            "SELECT COUNT(*) FROM comment WHERE id > ? AND thread_id=? GROUP BY thread_id",
            array($comment_id, $thread_id)
        );
    }

    public static function getAll($thread_id, $offset, $limit)
    {
        $db     = DB::conn();
        $rows   = $db->rows(
            sprintf("SELECT * FROM comment WHERE thread_id=? LIMIT %d, %d", $offset, $limit),
            array($thread_id)
        );

        $comments = array();

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }

        return $comments;
    }

    public static function getAllByUser(User $user)
    {
        $db     = DB::conn();
        $rows   = $db->rows('SELECT * FROM comment WHERE user_id=? ORDER BY id DESC', array($user->id));

        $comments = array();

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }

        return $comments;
    }


    public static function get($id)
    {
        $db     = DB::conn();
        $row    = $db->row("SELECT * FROM comment WHERE id=?", array($id));

        return $row ? new self($row) : false;
    }

    public static function getOrFail($id)
    {
        $comment = self::get($id);

        if ($comment) {
            return $comment;
        } else {
            throw new RecordNotFoundException();
        }
    }

    public static function getFirstInThread(Thread $thread)
    {
        $db     = DB::conn();
        $row    = $db->row('SELECT * FROM comment WHERE thread_id=?', array($thread->id));

        return $row ? new self($row) : false;
    }

    public static function getLastInThread(Thread $thread)
    {
        $db     = DB::conn();
        $row    = $db->row('SELECT * FROM comment WHERE thread_id=? ORDER BY id DESC', array($thread->id));

        return $row ? new self($row) : false;
    }

    // return the count of new comments today in each thread
    public static function countToday()
    {
        $db = DB::conn();
        return $db->rows(
            'SELECT thread_id, COUNT(*) AS count FROM comment
                WHERE DATE(created_at)=DATE(CURRENT_TIMESTAMP)
                GROUP BY thread_id'
        );
    }

    public function create(Thread $thread)
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid comment.');
        }

        $db = DB::conn();
        $db->insert(
            'comment',
            array(
                'thread_id' => $thread->id,
                'user_id'   => $this->user_id,
                'body'      => $this->body,
                'created_at'=> null
            )
        );
    }

    public function update()
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid comment.');
        }

        $db = DB::conn();
        $db->update('comment', array('body' => $this->body), array('id' => $this->id));
    }

    public function delete()
    {
        $db = DB::conn();
        $db->query('DELETE FROM comment WHERE id=?', array($this->id));
    }

    public function isAuthor($user)
    {
        if (!$user) {
            return false;
        }

        return $user->id == $this->user_id;
    }

    public function isThreadBody()
    {
        $thread         = Thread::get($this->thread_id);
        $first_comment  = Comment::getFirstInThread($thread);

        return $first_comment->id == $this->id;
    }
}
