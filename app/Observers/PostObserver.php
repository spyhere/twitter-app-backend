<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "save" event.
     *
     * @param Post $post
     * @return void
     */
    public function saved(Post $post)
    {
        $post->parseTags();
    }
}
