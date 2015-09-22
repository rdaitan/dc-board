<?php
class Follow extends AppModel
{
    const TABLE_NAME = 'follow';

    public static function getByThreadAndUser($thread_id, $user_id)
    {
        $db = DB::conn();
        $row = $db->row(
            'SELECT * FROM follow WHERE thread_id=? AND user_id=?',
            array(
                $thread_id,
                $user_id
            )
        );

        return !$row ? false : new self($row);
    }

    public static function getAll($user)
    {
        if (!$user) {
            return array();
        }

        $db     = DB::conn();
        $rows   = $db->rows('SELECT * FROM follow WHERE user_id=?', array($user->id));

        $follows = array();

        foreach ($rows as $row) {
            $follows[] = new self($row);
        }

        return $follows;
    }

    public static function getOrFail($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM follow WHERE id=?', array($id));

        if ($row) {
            return new self($row);
        }

        throw new RecordNotFoundException();
    }

    public static function getUpdates($user)
    {
        $follows = self::getAll($user);

        foreach ($follows as $follow_key => $follow) {
            $update_count = Comment::countAllAfter($follow->thread_id, $follow->last_comment);
            if ($update_count) {
                $follow->count = $update_count;
            } else {
                unset($follows[$follow_key]);
            }
        }

        return $follows;
    }

    public function create()
    {
        $db = DB::conn();
        try {
            $db->insert(
                'follow',
                array(
                    'thread_id'     => $this->thread_id,
                    'user_id'       => $this->user_id,
                    'last_comment'  => $this->last_comment
                )
            );
        } catch (PDOException $e) {
            // do nothing
        }
    }

    public function remove()
    {
        $db = DB::conn();
        $db->query('DELETE FROM follow WHERE id=?', array($this->id));
    }

    public function update()
    {
        $db = DB::conn();
        $db->update(
            'follow',
            array('last_comment' => $this->last_comment),
            array('id' => $this->id)
        );
    }
}
