<?php
class ThreadController extends AppController {
    /*
     * Show all threads.
     */
    public function index() {
        $page = Param::get('page', 1);
        $per_page = 10;

        $pagination = new SimplePagination($page, $per_page);

        $threads    = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);

        $total = Thread::countAll();
        $pages = ceil($total / $per_page);

        $authUser   = User::getAuthUser();
        $this->set(get_defined_vars());
    }

    /*
     * Show a specific thread
     */
    public function view() {
        $page       = Param::get('page', 1);
        $per_page   = 10;

        $pagination = new SimplePagination($page, $per_page);


        $thread     = Thread::get(Param::get('thread_id'));
        $comments   = $thread->getComments($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($comments);

        $total = Comment::countAll($thread->id);
        $pages = ceil($total / $per_page);

        $this->set(get_defined_vars());
    }

    public function create()
    {
        notAuthRedirect('user/authenticate');

        $thread  = new Thread;
        $comment = new Comment;
        $page    = Param::get('page_next', 'create');

        switch($page) {
            case 'create':
                break;
            case 'create_end':
                $thread->title      = trim_collapse(Param::get('title'));
                $comment->user_id   = User::getAuthUser()->id;
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
