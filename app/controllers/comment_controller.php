<?php
class CommentController extends AppController
{
    public function create() {
        $thread = Thread::get(Param::get('thread_id'));
        $page   = Param::get('page_next');

        switch ($page) {
            case 'write_end':
                $comment            = new Comment;
                $comment->username  = Param::get('username');
                $comment->body      = Param::get('body');

                try{
                    $comment->create($thread);
                }
                catch (ValidationException $e) {
                    $page = 'write';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }
}
