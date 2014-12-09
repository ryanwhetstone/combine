<?php 

/**
 * Combine\PostMeta
 * 
 * @author Junior Grossi <juniorgro@gmail.com>
 */

namespace Combine;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PostMeta extends Eloquent
{
    protected $table = 'wp_postmeta';
    protected $primaryKey = 'meta_id';
    public $timestamps = false;
    protected $fillable = array('meta_key', 'meta_value', 'post_id');

    /**
     * Post relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('Combine\Post');
    }

    /**
     * Override newCollection() to return a custom collection
     * 
     * @param array $models
     * @return \Combine\PostMetaCollection
     */
    public function newCollection(array $models = array())
    {
        return new PostMetaCollection($models);
    }
}