<?php namespace Ryanwhetstone\Combine\Models;

use Ryanwhetstone\Combine;

/**
 * Category class
 *
 * @author Yoram de Langen <yoramdelangen@gmail.com>
 */
class CombineCategory extends Combine\TermTaxonomy
{
    /**
     * Used to set the post's type
     */
    protected $taxonomy = 'category';
}