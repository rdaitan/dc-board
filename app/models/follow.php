<?php
class Follow extends AppModel
{
    const TABLE_NAME = 'follow';

    public static function getByThreadAndUser($thread_id, $user_id)
    {
        $db = DB::conn();
        $row = $db->row(
            sprintf(
                'SELECT * FROM %s WHERE thread_id=? AND user_id=?',
                self::TABLE_NAME
            ),
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
        $rows   = $db->rows(sprintf('SELECT * FROM %s WHERE user_id=?', self::TABLE_NAME), array($user->id));

        $follows = array();

        foreach ($rows as $row) {
            $follows[] = new self($row);
        }

        return $follows;
    }

    public static function getOrFail($id) {
        $db = DB::conn();
        $row = $db->row(sprintf('SELECT * FROM %s WHERE id=?', self::TABLE_NAME), array($id));

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
        $db->insert(
            self::TABLE_NAME,
            array(
                'thread_id'     => $this->thread_id,
                'user_id'       => $this->user_id,
                'last_comment'  => $this->last_comment
            )
        );
    }

    public function remove()
    {
        $db = DB::conn();
        $db->query(sprintf('DELETE FROM %s WHERE id=?', self::TABLE_NAME), array($this->id));
    }

    public function update()
    {
        $db = DB::conn();
        $db->update(
            self::TABLE_NAME,
            array('last_comment' => $this->last_comment),
            array('id' => $this->id)
        );
    }
}
