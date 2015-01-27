<?php namespace Ryanwhetstone\Combine;

/**
 * Combine\Comment
 * 
 * @author Junior Grossi <juniorgro@gmail.com>
 */

namespace Ryanwhetstone\Combine;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Comment extends Eloquent
{
    protected $table = 'wp_comments';
    protected $primaryKey = 'comment_ID';

    /**
     * Post relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('Ryanwhetstone\Combine\Post', 'comment_post_ID');
    }

    /**
     * Original relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function original()
    {
        return $this->belongsTo('Ryanwhetstone\Combine\Comment', 'comment_parent');
    }

    /**
     * Replies relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany('Ryanwhetstone\Combine\Comment', 'comment_parent');
    }

    /**
     * Verify if the current comment is approved
     * 
     * @return bool
     */
    public function isApproved()
    {
        return $this->attributes['comment_approved'] == 1;
    }

    /**
     * Verify if the current comment is a reply from another comment
     * 
     * @return bool
     */
    public function isReply()
    {
        return $this->attributes['comment_parent'] > 0;
    }

    /**
     * Verify if the current comment has replies
     * 
     * @return bool
     */
    public function hasReplies()
    {
        return count($this->replies) > 0;
    }

    /**
     * Find a comment by post ID
     * 
     * @param int $postId
     * @return \Combine\Comment
     */
    public static function findByPostId($postId)
    {
        $instance = new static;
        return $instance->where('comment_post_ID', $postId)->get();
    }

    /**
     * Override the parent newQuery() to the custom CommentBuilder class
     * 
     * @param bool $excludeDeleted
     * @return \Combine\CommentBuilder
     */
    public function newQuery($excludeDeleted = true)
    {
        $builder = new CommentBuilder($this->newBaseQueryBuilder());
        $builder->setModel($this)->with($this->with);

        if ($excludeDeleted and $this->softDelete) {
            $builder->whereNull($this->getQualifiedDeletedAtColumn());
        }

        return $builder;
    }
}
