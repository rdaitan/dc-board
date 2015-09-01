<?php
class ThreadController extends AppController
{
    const THREADS_PERPAGE   = 10;
    const COMMENTS_PERPAGE  = 15;

    /*
     * Show all threads.
     */
    public function index()
    {
        $page = Param::get('page', 1);

        $pagination = new SimplePagination($page, self::THREADS_PERPAGE);

        $threads    = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);

        $total = Thread::countAll();
        $pages = ceil($total / self::THREADS_PERPAGE);

        $authUser   = User::getAuthenticated();
        $title      = 'All Threads';
        $this->set(get_defined_vars());
    }

    /*
     * Show a specific thread
     */
    public function view()
    {
        $page       = Param::get('page', 1);

        $pagination = new SimplePagination($page, self::COMMENTS_PERPAGE);

        $thread     = Thread::get(Param::get('thread_id'));
        $comments   = Comment::getAll($thread->id, $pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($comments);

        $total = Comment::countAll($thread->id);
        $pages = ceil($total / self::COMMENTS_PERPAGE);

        $title = $thread->title;
        $this->set(get_defined_vars());
    }

    public function create()
    {
        notAuthRedirect('user/authenticate');

        $thread  = new Thread();
        $comment = new Comment();
        $page    = Param::get('page_next', 'create');

        switch($page) {
            case 'create':
                break;
            case 'create_end':
                $thread->title      = trim_collapse(Param::get('title'));
                $comment->user_id   = User::getAuthenticated()->id;
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

        $title = 'Create Thread';
        $this->set(get_defined_vars());
        $this->render($page);
    }
}
