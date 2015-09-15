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

    public static function getAll($user) {
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

    public function create()
    {
        $db = DB::conn();
        $db->insert(self::TABLE_NAME, array('thread_id' => $this->thread_id, 'user_id' => $this->user_id));
    }

    public function remove()
    {
        $db = DB::conn();
        $db->query(sprintf('DELETE FROM %s WHERE id=?', self::TABLE_NAME), array($this->id));
    }
}
