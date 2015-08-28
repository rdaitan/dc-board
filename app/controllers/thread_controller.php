<?php
class ThreadController extends AppController {
    /*
     * Show all threads.
     */
    public function index() {
        $authUser   = User::getAuthUser();
        $threads    = Thread::getAll();
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

    public function create() {
        $thread  = new Thread;
        $comment = new Comment;
        $page    = Param::get('page_next', 'create');

        switch($page) {
            case 'create':
                break;
            case 'create_end':
                $thread->title      = trim_collapse(Param::get('title'));
                $comment->username  = trim_collapse(Param::get('username'));
                $comment->body      = Param::get('body');

                try {
                    $thread->create($comment);
                }
                catch(ValidationException $e) {
                    $page = 'create';
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
