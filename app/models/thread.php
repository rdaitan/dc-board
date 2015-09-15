<?php
class Thread extends AppModel
{
    const MIN_TITLE_LENGTH  = 1;
    const MAX_TITLE_LENGTH  = 30;
    const TABLE_NAME        = 'thread';
    const ERR_CATEGORY      = 1452; // actually a foreign key constraint failure.

    public $validation = array(
        'title' => array(
            'length' => array('validate_between', self::MIN_TITLE_LENGTH, self::MAX_TITLE_LENGTH)
        ),
    );

    public static function getAll($offset, $limit, $filters = array())
    {
        $where = '';

        foreach ($filters as $column => $value) {
            $where .= !$where ? 'WHERE ' : ', ';
            $where .= "{$column}={$value}";
        }

        $db = DB::conn();
        $rows = $db->rows(
            sprintf("SELECT * FROM thread %s ORDER BY id DESC LIMIT %d, %d", $where, $offset, $limit)
        );

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
        // $trends = Comment::getTrendingThreadIds($limit);
        $trends = Comment::countToday();
        $threads = array();

        foreach ($trends as $trend) {
            $thread = Thread::get($trend['thread_id']);
            $comment = Comment::getFirstInThread($thread);
            $thread->count = $trend['count'];
            $thread->created_at = $comment->created_at;
            $threads[] = $thread;
        }

        usort(
            $threads,
            function ($thread_a, $thread_b)
            {
                $diff = $thread_b->count - $thread_a->count;

                if ($diff != 0) {
                    return $diff;
                } else {
                    $datetime_a = new DateTime($thread_a->created_at);
                    $datetime_b = new DateTime($thread_b->created_at);

                    $datetime_diff = $datetime_a->diff($datetime_b);
                    return $datetime_diff->invert ? -$datetime_diff->s : $datetime_diff->s;
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
            $db->insert('thread', array('title' => $this->title, 'category_id' => $this->category_id));

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
        if(!$this->validate() | !$comment->validate()) {
            throw new ValidationException();
        }

        $db = DB::conn();

        try {
            $db->begin();
            $db->update(
                self::TABLE_NAME,
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
        $db->query(sprintf('DELETE FROM %s WHERE id=?', self::TABLE_NAME), array($this->id));
    }

    public function isOwnedBy($user)
    {
        if (!$user) {
            return false;
        }

        return $user->id == Comment::getFirstInThread($this)->user_id;
    }

    public function isFollowedBy($user)
    {
        if (!$user) {
            return false;
        }

        return Follow::getByThreadAndUser($this->id, $user->id) ? true : false;
    }
}
