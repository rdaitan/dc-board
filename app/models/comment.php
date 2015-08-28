<?php
class Comment extends AppModel {
    public $validation = array(
        'body'          => array(
            'length'    => array('validate_between', 1, 200),
        ),
    );

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
