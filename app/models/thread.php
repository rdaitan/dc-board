<?php
class Thread extends AppModel
{
    const MIN_TITLE_LENGTH  = 1;
    const MAX_TITLE_LENGTH  = 30;
    const ERR_CATEGORY      = 1452; // actually a foreign key constraint failure.

    public $validation = array(
        'title' => array(
            'length' => array('validate_between', self::MIN_TITLE_LENGTH, self::MAX_TITLE_LENGTH)
        ),
    );

    public static function search($query, $offset, $limit)
    {
        $db = DB::conn();
        $rows = $db->rows(
            sprintf(
                "SELECT * FROM thread WHERE title LIKE ? ORDER BY id DESC LIMIT %d, %d",
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
        return $db->value("SELECT COUNT(*) FROM thread WHERE title LIKE ?", array("%{$query}%"));
    }

    public static function getAll($offset, $limit, $filter = null)
    {
        $where = is_null($filter) ? '' : sprintf('WHERE category_id=%d', $filter);

        $db     = DB::conn();
        $rows   = $db->rows(sprintf("SELECT * FROM thread %s ORDER BY id DESC LIMIT %d, %d", $where, $offset, $limit));

        $threads = array();

        foreach ($rows as $row) {
            $threads[] = new self($row);
        }

        return $threads;
    }

    public static function getAllByUser(User $user)
    {
        $db     = DB::conn();
        $rows   = $db->rows("SELECT * FROM thread WHERE user_id=? ORDER BY id DESC", array($user->id));

        $threads = array();

        foreach ($rows as $row) {
            $threads[] = new self($row);
        }

        return $threads;
    }

    public static function get($id)
    {
        $db     = DB::conn();
        $row    = $db->row('SELECT * FROM thread WHERE id=?', array($id));

        if (!$row) {
            throw new RecordNotFoundException('No record found');
        }

        return new self($row);
    }

    public static function countAll($filter = null)
    {
        $where = is_null($filter) ? '' : sprintf('WHERE category_id=%d', $filter);

        $db = DB::conn();
        return $db->value(sprintf("SELECT COUNT(*) FROM thread %s", $where));
    }

    public static function getTrending($limit)
    {
        $trends     = Comment::countToday();
        $threads    = array();

        foreach ($trends as $trend) {
            $thread = Thread::get($trend['thread_id']);
            $thread->count = $trend['count'];
            $threads[] = $thread;
        }

        usort(
            $threads,
            function ($thread_a, $thread_b) {
                $diff = $thread_b->count - $thread_a->count;

                if ($diff != 0) {
                    return $diff;
                } else {
                    return $thread_b->id - $thread_a->id;
                }
            }
        );

        return array_slice($threads, 0, $limit);
    }

    public function create(Comment $comment)
    {
        // before calling $this->validate(), $this->title was set first
        if (!$this->validate() | !$comment->validate()) {
            throw new ValidationException('Invalid thread or comment.');
        }

        $db = DB::conn();

        try {
            $db->begin();
            $db->insert(
                'thread',
                array(
                    'title'         => $this->title,
                    'category_id'   => $this->category_id,
                    'user_id'       => $this->user_id
                )
            );

            // write first comment
            $this->id = $db->lastInsertId();
            $comment->create($this);

            $db->commit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == self::ERR_CATEGORY) {
                throw new CategoryException();
            }

            $db->rollback();
        }
    }

    public function update(Comment $comment)
    {
        if (!$this->validate() | !$comment->validate()) {
            throw new ValidationException();
        }

        $db = DB::conn();

        try {
            $db->begin();
            $db->update(
                'thread',
                array('title' => $this->title, 'category_id' => $this->category_id),
                array('id' => $this->id)
            );
            $comment->update();
            $db->commit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == self::ERR_CATEGORY) {
                throw new CategoryException();
            }

            $db->rollback();
        }
    }

    public function delete()
    {
        $db = DB::conn();
        $db->query('DELETE FROM thread WHERE id=?', array($this->id));
    }

    public function isAuthor($user)
    {
        if (!$user) {
            return false;
        }

        return $user->id == $this->user_id;
    }

    public function isFollowedBy($user)
    {
        if (!$user) {
            return false;
        }

        return Follow::getByThreadAndUser($this->id, $user->id) ? true : false;
    }
}
