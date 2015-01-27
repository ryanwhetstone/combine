<?php namespace Ryanwhetstone\Combine;

namespace Ryanwhetstone\Combine;

use Illuminate\Database\Eloquent\Model;

class TermRelationship extends Model
{
    protected $table = 'wp_term_relationships';
    protected $primaryKey = array('object_id', 'term_taxonomy_id');

    public function post()
    {
        return $this->belongsTo('Ryanwhetstone\Combine\Post', 'object_id');
    }

    public function taxonomy()
    {
        return $this->belongsTo('Ryanwhetstone\Combine\TermTaxonomy', 'term_taxonomy_id');
    }
}