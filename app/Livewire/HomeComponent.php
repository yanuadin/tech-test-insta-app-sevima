<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class HomeComponent extends Component
{
    #[Validate('required')]
    public $comment = '';
    public $replyCommentObject = null;

    public function render()
    {
        $posts = Post::query()
            ->with(['user', 'comments' => function ($query) {
                $query->whereNull('comment_replied_to_id');
            }, 'comments.user', 'comments.replies'])
            ->withCount(['comments' => function ($query) {
                $query->whereNull('comment_replied_to_id');
            }])
            ->withCount(['likes' => function ($query) {
                $query->where('users.id', auth()->user()->id);
            }])
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);

        return view('livewire.home-component', ['posts' => $posts]);
    }

    public function addComment(Post $post): void
    {
        $this->validate();
        $comment_replied_to_id = $this->replyCommentObject?->id;

        Comment::query()->create([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comment' => $this->comment,
            'comment_replied_to_id' => $comment_replied_to_id
        ]);

        $this->replyCommentObject = null;
        $this->redirectRoute('home');
    }

    public function replyComment(Post $post, Comment $comment, string $repliedToUsername): void
    {
        $this->replyCommentObject = $comment;
        $this->comment = "@" . $repliedToUsername . " ";
    }

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();

        $this->redirectRoute('home');
    }

    public function like(Post $post): void
    {
        $userLikePost = Like::query()->where('user_id', auth()->user()->id)->where('post_id', $post->id)->first();
        if($userLikePost){
            $post->likes()->detach(auth()->user()->id);
        } else {
            $post->likes()->attach(auth()->user()->id);
        }
    }

    public function deletePost(Post $post): void
    {
        $post->delete();

        $this->redirectRoute('home');
    }
}
