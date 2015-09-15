<?php
class ThreadController extends AppController
{
    const THREADS_PERPAGE   = 10;
    const COMMENTS_PERPAGE  = 15;
    const TRENDING_LIMIT    = 10;

    /*
     * Show all threads.
     */
    public function index()
    {
        $page   = Param::get('page', 1);
        $filter = Param::get('filter');

        // pagination
        $pagination = new SimplePagination($page, self::THREADS_PERPAGE);

        $threads = Thread::getAll(
            $pagination->start_index - 1,
            $pagination->count + 1,
            $filter
        );

        $pagination->checkLastPage($threads);

        $total = Thread::countAll($filter);
        $pages = ceil($total / self::THREADS_PERPAGE);

        // get other variables needed by the view
        $title      = 'All Threads';
        $auth_user  = User::getAuthenticated();
        $categories = Category::getAll();
        $trending   = Thread::getTrending(self::TRENDING_LIMIT);
        $this->set(get_defined_vars());
    }

    /*
     * Show a specific thread
     */
    public function view()
    {
        // paginate comments
        $page = Param::get('page', 1);

        $pagination = new SimplePagination($page, self::COMMENTS_PERPAGE);

        $thread     = Thread::get(Param::get('id'));
        $comments   = Comment::getAll(
            $thread->id,
            $pagination->start_index - 1,
            $pagination->count + 1
        );

        $pagination->checkLastPage($comments);

        $total = Comment::countAll($thread->id);
        $pages = ceil($total / self::COMMENTS_PERPAGE);

        // set other comment information needed by the view.
        $auth_user = User::getAuthenticated();

        // set other variables needed by the view
        $title = $thread->title;
        $this->set(get_defined_vars());
    }

    public function create()
    {
        redirect_guest_user(LOGIN_URL);

        $thread  = new Thread();
        $comment = new Comment();
        $page    = Param::get('page_next', 'create');
        $auth_user = User::getAuthenticated();

        switch ($page) {
        case 'create':
            $categories = Category::getAll();
            break;
        case 'create_end':
            $thread->title          = trim_collapse(Param::get('title'));
            $thread->category_id    = Param::get('category');
            $comment->user_id       = $auth_user->id;
            $comment->body          = Param::get('body');

            $db = DB::conn();
            try {
                $db->begin();
                $thread->create($comment);

                $follow             = new Follow();
                $follow->thread_id  = $thread->id;
                $follow->user_id    = $auth_user->id;

                $follow->create();

                $db->commit();
            } catch (ValidationException $e) {
                $page = 'create';
                $db->rollback();
            } catch (CategoryException $e) {
                $thread->validation_errors['category']['exists'] = true;
                $categories = Category::getAll();
                $page = 'create';
                $db->rollback();
            }
            break;
        default:
            throw new PageNotFoundException("{$page} is not found");
            break;
        }

        $title = 'Create Thread';
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function edit()
    {
        redirect_guest_user(LOGIN_URL);

        $page       = Param::get('page_next', 'edit');
        $thread     = Thread::get(Param::get('id'));
        $comment    = Comment::getFirstInthread($thread);
        $auth_user  = User::getAuthenticated();

        if (!$thread->isOwnedBy($auth_user)) {
            throw new PermissionException();
        }

        switch($page) {
        case 'edit':
            break;
        case 'edit_end':
            $thread->title          = trim_collapse(Param::get('title'));
            $thread->category_id    = Param::get('category');
            $comment->body          = Param::get('body');

            try {
                $thread->update($comment);
                redirect(APP_URL);  // TODO: redirect to actual thread
            } catch (ValidationException $e) {
                $page = 'edit';
            } catch (CategoryException $e) {
                $thread->validation_errors['category']['exists'] = true;
                $page = 'edit';
            }

            break;
        default:
            throw new PageNotFoundException();
            break;
        }

        // set other variables needed by the view
        $categories = Category::getAll();

        $title = 'Edit thread';
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function delete()
    {
        redirect_guest_user(LOGIN_URL);

        $page       = Param::get('page_next', 'delete');
        $thread     = Thread::get(Param::get('id'));
        $auth_user  = User::getAuthenticated();

        if (!$thread->isOwnedBy($auth_user)) {
            throw new PermissionException();
        }

        switch($page) {
        case 'delete':
            break;
        case 'delete_end':
            $thread->delete();
            redirect(LIST_THREADS_URL);
            break;
        default:
            break;
        }

        $title = 'Delete thread';
        $this->set(get_defined_vars());
    }

    public function rank()
    {
        $threads = Thread::getTrending(self::TRENDING_LIMIT);

        $title = 'Trending';
        $this->set(get_defined_vars());
    }
}
