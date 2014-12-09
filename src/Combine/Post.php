<?php

/**
 * Post model
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */

namespace Combine;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent
{
    const CREATED_AT = 'post_date';
    const UPDATED_AT = 'post_modified';

    protected $table = 'wp_posts';
    protected $primaryKey = 'ID';
    protected $with = array('meta');

    /**
     * Meta data relationship
     *
     * @return Combine\PostMetaCollection
     */
    public function meta()
    {
        return $this->hasMany('Combine\PostMeta', 'post_id');
    }

    public function fields()
    {
        return $this->meta();
    }

    /**
     * Taxonomy relationship
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function taxonomies()
    {
        return $this->belongsToMany('Combine\TermTaxonomy', 'wp_term_relationships', 'object_id', 'term_taxonomy_id');
    }

    /**
     * Comments relationship
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function comments()
    {
        return $this->hasMany('Combine\Comment', 'comment_post_ID');
    }

    /**
     * Get attachment
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function attachment()
    {
        return $this->hasMany('Combine\Post', 'post_parent')->where('post_type', 'attachment');
    }


    /**
     * Get revisions from post
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function revision()
    {
        return $this->hasMany('Combine\Post', 'post_parent')->where('post_type', 'revision');
    }

    /**
     * Overriding newQuery() to the custom PostBuilder with some interesting methods
     *
     * @param bool $excludeDeleted
     * @return Combine\PostBuilder
     */
    public function newQuery($excludeDeleted = true)
    {
        $builder = new PostBuilder($this->newBaseQueryBuilder());
        $builder->setModel($this)->with($this->with);
        $builder->orderBy('post_date', 'desc');

        if (isset($this->postType) and $this->postType) {
            $builder->type($this->postType);
        }

        if ($excludeDeleted and $this->softDelete) {
            $builder->whereNull($this->getQualifiedDeletedAtColumn());
        }

        return $builder;
    }

    /**
     * Magic method to return the meta data like the post original fields
     *
     * @param string $key
     * @return string
     */
    public function __get($key)
    {
        if (!isset($this->$key)) {
            if (isset($this->meta()->get()->$key)) {
                return $this->meta()->get()->$key;
            }
        }

        return parent::__get($key);
    }

    public function save(array $options = array())
    {
        if (isset($this->attributes[$this->primaryKey])) {
            $this->meta->save($this->attributes[$this->primaryKey]);
        }

        return parent::save($options);
    }


}