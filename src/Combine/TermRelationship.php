<?php

namespace Combine;

use Illuminate\Database\Eloquent\Model;

class TermRelationship extends Model
{
    protected $table = 'wp_term_relationships';
    protected $primaryKey = array('object_id', 'term_taxonomy_id');

    public function post()
    {
        return $this->belongsTo('Combine\Post', 'object_id');
    }

    public function taxonomy()
    {
        return $this->belongsTo('Combine\TermTaxonomy', 'term_taxonomy_id');
    }
}