<?php
class ThreadController extends AppController {
    /*
     * Show all threads.
     */
    public function index() {
        $threads = Thread::getAll();
        $this->set(get_defined_vars());
    }

    /*
    * Show a specific thread
    */
   public function view() {
       $thread = Thread::get(Param::get('thread_id'));
       $comments = $thread->getComments();

       $this->set(get_defined_vars());
   }

    public function write() {
        $thread = Thread::get(Param::get('thread_id'));
        $page   = Param::get('page_next');

        switch ($page) {
            case 'write_end':
                $comment            = new Comment;
                $comment->username  = Param::get('username');
                $comment->body      = Param::get('body');

                try{
                    $thread->write($comment);
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
