<?php
class FollowController extends AppController
{
    public function add()
    {
        redirect_guest_user(LOGIN_URL);

        $thread     = Thread::get(Param::get('id'));
        $auth_user  = User::getAuthenticated();

        if (!$auth_user) {
            die();
        }

        $follow             = new Follow();
        $follow->thread_id  = $thread->id;
        $follow->user_id    = $auth_user->id;

        try {
            $follow->create();
        } catch (PDOException $e) {
            // do nothing.
        }

        // var_dump($follow); die();

        redirect(VIEW_THREAD_URL, array('id' => $follow->thread_id));
    }

    public function remove()
    {
        redirect_guest_user(LOGIN_URL);

        $thread = Thread::get(Param::get('id'));
        $auth_user = User::getAuthenticated();

        if (!$auth_user) {
            die();
        }

        $follow = Follow::getByThreadAndUser($thread->id, $auth_user->id);

        if ($follow) {
            $follow->remove();
        }

        redirect(VIEW_THREAD_URL, array('id' => $follow->thread_id));
    }
}
