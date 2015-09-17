<?php
class Comment extends AppModel
{
    const MIN_BODY_LENGTH   = 1;
    const MAX_BODY_LENGTH   = 200;
    const TABLE_NAME        = 'comment';

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
                "SELECT * FROM %s WHERE body LIKE ? ORDER BY id DESC LIMIT %d, %d",
                self::TABLE_NAME,
                $offset,
                $limit
            ),
            array("%{$query}%")
        );

        $search                 = new Search(get_called_class(), $rows);
        $search->total_result   = self::countResults($query);
        return $search;
    }

    public static function countResults($query)
    {
        $db = DB::conn();
        return $db->value(
            sprintf("SELECT COUNT(*) FROM %s WHERE body LIKE ?", self::TABLE_NAME),
            array("%{$query}%")
        );
    }

    public static function countAll($thread_id)
    {
        $db = DB::conn();
        return $db->value(sprintf("SELECT COUNT(*) FROM %s WHERE thread_id=?", self::TABLE_NAME), array($thread_id));
    }

    public static function countAllAfter($thread_id, $comment_id)
    {
        $db = DB::conn();
        return $db->value(
            sprintf("SELECT COUNT(*) FROM %s WHERE id > ? AND thread_id=? GROUP BY thread_id", self::TABLE_NAME),
            array($comment_id, $thread_id)
        );
    }

    public static function getAll($thread_id, $offset, $limit)
    {


        $db     = DB::conn();
        $rows   = $db->rows(
            sprintf("SELECT * FROM %s WHERE thread_id=? LIMIT %d, %d", self::TABLE_NAME, $offset, $limit),
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
        $rows   = $db->rows(
            sprintf('SELECT * FROM %s WHERE user_id=? ORDER BY id DESC', self::TABLE_NAME),
            array($user->id)
        );

        $comments = array();

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }

        return $comments;
    }


    public static function get($id)
    {
        $db     = DB::conn();
        $row    = $db->row(sprintf("SELECT * FROM %s WHERE id=?", self::TABLE_NAME), array($id));

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
        $row    = $db->row(sprintf('SELECT * FROM %s WHERE thread_id=? LIMIT 0, 1', self::TABLE_NAME), array($thread->id));

        return $row ? new self($row) : false;
    }

    public static function getLastInThread(Thread $thread)
    {
        $db     = DB::conn();
        $row    = $db->row(sprintf('SELECT * FROM %s WHERE thread_id=? ORDER BY id DESC LIMIT 0, 1', self::TABLE_NAME), array($thread->id));

        return $row ? new self($row) : false;
    }

    // return the count of new comments today in each thread
    public static function countToday()
    {
        $db = DB::conn();
        return $db->rows(
            sprintf(
                'SELECT thread_id, COUNT(*) AS count FROM %s
                    WHERE DATE(created_at)=DATE(CURRENT_TIMESTAMP)
                    GROUP BY thread_id',
                self::TABLE_NAME
            )
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
        $db->update(
            self::TABLE_NAME,
            array('body' => $this->body),
            array('id' => $this->id)
        );
    }

    public function delete()
    {
        $db = DB::conn();
        $db->query(sprintf('DELETE FROM %s WHERE id=?', self::TABLE_NAME), array($this->id));
    }

    public function isOwnedBy($user)
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
