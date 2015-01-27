<?php namespace Ryanwhetstone\Combine;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Combine{

	function __construct() {

	}

	public function make($slug=null)
	{
		$type = $this->getType();
		if($type == 'home') $context = $this->getHome();
		if($type == 'blog') $context = $this->getBlog();
		if($type == 'page') $context = $this->getPage($slug);
		if($type == 'single') $context = $this->getSingle($slug);
		if($type == 'archive') $context = $this->getArchive();		
		if($type == 'category') $context = $this->getCategory($slug);
		if($type == '404') $context = $this->get404();

		return($context);
	}

	public function getType() // Order matters, more specific types should go to the top
	{
		if(is_front_page()) return('home');
		if(is_home()) return('blog');
		if(is_page()) return('page');
		if(is_single()) return('single');
		if(is_archive()) return('archive');
		if(is_category()) return('category');
		if(is_404()) return('404');
	}


	public function getSingle($slug)
	{
		$context['post'] = Post::slug($slug)->published()->firstOrFail();
		$default = 'single';
		$override = 'singles.single-'.$context['post']->post_name;
		$context['template'] = $this->getUsableTemplate($override, $default);

		return($context);
	}

	public function getPage($slug)
	{
		$context['post'] = Post::slug($slug)->published()->firstOrFail();
		$default = 'page';
		$override = 'pages.page-'.$context['post']->post_name;
		$context['template'] = $this->getUsableTemplate($override, $default);

		return($context);
	}

	public function getHome()
	{
		$page_id = get_option('page_on_front');
		$context['post'] = Post::find($page_id);
		$default = 'page';
		$override = 'pages.page-home';
		$context['template'] = $this->getUsableTemplate($override, $default);

		return($context);
	}

	public function getBlog()
	{
		$posts_per_page = get_option('posts_per_page');
		$context['posts'] = Post::type('post')->published()->orderBy('post_date', 'desc')->paginate($posts_per_page);
		$context['template'] = 'index';

		return($context);
	}
	public function getCategory($slug)
	{
		$category_slug = str_replace('category/', '', $slug);
		$context['category'] = get_category_by_slug($category_slug);
		$context['posts'] = Post::taxonomy('category', $context['category']->slug)->published()->orderBy('post_date', 'desc')->get();
		$default = 'category';
		$override = 'categories.category-'.$context['category']->slug;
		$context['template'] = $this->getUsableTemplate($override, $default);

		return($context);
	}

	public function getArchive()
	{
		// TODO: This!
		// $context['posts'] = Post::taxonomy('category', $context['category']->slug)->published()->get();
		$context['posts'] = wp_get_archives( $args );
		dd($context['posts']);
		$default = 'index';
		$override = 'archive';
		$context['template'] = $this->getUsableTemplate($override, $default);

		return($context);
	}

	public function get404()
	{
		$content = new \StdClass;
		$context['template'] = '404';

		return($context);
	}

	protected function getUsableTemplate($override, $default, $catch_all='index')
	{
		if(\View::exists( $override )) return($override);
		if(\View::exists( $default )) return($default);

		return($catch_all);
	}

	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getACField($value)
	{
		return get_field($value);
	}

	public function getMenu($menu)
	{
		return wp_nav_menu( [ 'menu' => $menu ] );

	}
}
