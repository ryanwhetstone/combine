<?php 

/**
 * Combine\CommentBuilder
 * 
 * @author Junior Grossi <juniorgro@gmail.com>
 */

namespace Combine;

use Illuminate\Database\Eloquent\Builder;

class CommentBuilder extends Builder
{
    /**
     * Where clause for only approved comments
     * 
     * @return \Combine\CommentBuilder
     */
    public function approved()
    {
        return $this->where('comment_approved', 1);
    }

}