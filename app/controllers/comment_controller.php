<?php
class CommentController extends AppController
{
    public function create()
    {
        redirect_guest_user(LOGIN_URL);

        $thread     = Thread::get(Param::get('thread_id'));
        $page       = Param::get('page_next');
        $auth_user  = User::getAuthenticated();

        if(!$auth_user) {
            return;
        }

        switch ($page) {
        case 'create_end':
            $comment            = new Comment();
            $comment->body      = Param::get('body');
            $comment->user_id   = $auth_user->id;

            try {
                $comment->create($thread);
            } catch (ValidationException $e) {
                $page = 'create';
            }
            break;
        default:
            throw new PageNotFoundException("{$page} is not found");
            break;
        }

        $title = 'Create Comment';
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function edit()
    {
        redirect_guest_user(LOGIN_URL);

        $id             = Param::get('id');
        $page           = Param::get('page_next', 'edit');
        $auth_user      = User::getAuthenticated();
        $return_url     = Param::get('return_url', '');

        if(!$auth_user || !$id) {
            return;
        }


        switch($page) {
        case 'edit':
            $comment = Comment::get($id);
            break;
        case 'edit_end':
            $comment = new Comment();
            $comment->id = Param::get('id');
            $comment->body = Param::get('body');
            $comment->user_id = $auth_user->id;

            try {
                $comment->update();
                redirect($return_url);
            } catch (ValidationException $e) {
                $page = 'edit';
            }

            break;
        default:
            throw new PageNotFoundException("{$page} is not found");
            break;
        }

        $title = 'Edit comment';
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function view() {
        $comment = Comment::get(Param::get('id'));
        $auth_user = User::getAuthenticated();

        if(!$comment) {
            // TODO: GO TO 404
            echo ('404');
            die();
        }

        $thread = Thread::get($comment->thread_id);

        $comment->thread_title = $thread->title;
        $comment->url = get_current_url();

        if($auth_user && ($comment->user_id == $auth_user->id)) {
            $comment->edit_url = get_edit_url($comment);
        } else {
            $comment->edit_url = '';
        }

        $this->set(get_defined_vars());
    }

    public function delete()
    {
        redirect_guest_user(LOGIN_URL);

        $id         = Param::get('id');
        $comment    = Comment::get($id);

        $auth_user  = User::getAuthenticated();

        try {

            if (!$comment) {
                throw new RecordNotFoundException();
            } else if (!$auth_user || $comment->user_id != $auth_user->id) {
                throw new PermissionException();
            }

            $comment->delete();
            redirect(LIST_THREADS_URL);
        } catch (PermissionException $e) {
            echo ("YOU DON'T HAVE PERMISSION TO DELETE THIS COMMENT.");
            die();
        } catch (RecordNotFoundException $e) {
            echo ("COMMENT DOES NOT EXIST");
            die();
        }
    }
}
