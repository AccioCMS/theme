<?php

namespace Themes\DefaultTheme\Controllers;

use App\Models\Media;
use App\Models\MediaRelation;
use App\Models\MenuLink;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Accio\App\Models\PostModel;
use Accio\Support\Facades\Pagination;
use App\Http\Controllers\Frontend\MainController;

class PostController extends MainController{

    /**
     * Route names that can be chosen as template from MenuLink
     */
    protected static function menuLinkRoutes(){
        return [
            // Post Type Routes
            'post_type' => [
                'post_articles' => [
                    'defaultRoute' => 'post.articles.index',
                    'list' => [
                        'post.articles.index' => 'Post Articles Index',
                    ]
                ]
            ],

            // Single post
            'post_articles' => [
                'defaultRoute' => 'post.articles.single',
                'list' => [
                    'post.articles.single' => 'Single Post Article'
                ]
            ]
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(){
        if(PostType::validatePostType()){
            return error404();
        }

        $posts = Post::cache('post_articles')
          ->published()
          ->orderBy('published_at','DESC')
          ->take(2)
          ->getItems()
          ->paginate(10);

        return view(Theme::view('posts/index'),compact('posts'));
    }

    public function single(){
        $post = Post::findBySlug(\Request::route('postSlug'));
        if(!$post){
            return error404();
        }

        return view(Theme::view('posts/single'),compact('post'));
    }
}