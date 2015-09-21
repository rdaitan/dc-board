<div class="row">
    <h3>Create thread</h3>
</div>
<div class="row">
    <?php if ($thread->hasError() || $comment->hasError()) : ?>
        <div class="red">
            <?php if (!empty($thread->validation_errors['title']['length'])) : ?>
                <div>
                    <em>Title</em> must be between
                    <?php eh($thread->validation['title']['length'][1]); ?> and
                    <?php eh($thread->validation['title']['length'][2]); ?>
                    characters in length.
                </div>
            <?php endif; ?>
            <?php if (!empty($thread->validation_errors['category']['exists'])) : ?>
                <div>
                    <em>Category</em> must be exist.
                </div>
            <?php endif; ?>
            <?php if (!empty($comment->validation_errors['body']['length'])) : ?>
                <div>
                    <em>Comment</em> must be between
                    <?php eh($comment->validation['body']['length'][1]); ?> and
                    <?php eh($comment->validation['body']['length'][2]); ?>
                    characters in length.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<!--Thread creation form-->
<div class="row">
    <form action="<?php eh(url('')); ?>" method="post">
        <div class="row">
            <div class="six columns">
                <label for="title">Title</label>
                <input type="text" class='u-full-width' id='title' name="title" value="<?php eh(Param::get('title')); ?>">
            </div>
            <div class="six columns">
                <label  class='col-sm-2 control-label' for="category">Category</label>
                <select class='u-full-width' name="category" id="category">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php eh($category->id); ?>" <?php eh(isset($thread->category_id) && $thread->category_id == $category->id ? 'selected' : '')?>><?php eh($category->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <label class='col-sm-2 control-label' for="body">Comment</label>
            <textarea class='u-full-width' name="body" id='body'><?php eh(Param::get('body')); ?></textarea>
        </div>
        <div class="row">
            <input type="hidden" name="page_next" value="create_end">
            <button type="submit">Submit</button> or
            <a href="<?php eh(url(APP_URL)) ?>">cancel</a>
        </div>
    </form>
</div>
