<?php

namespace tests\functional;

use app\models\Post;
use FunctionalTester;

class PostCest
{
    public function _before(FunctionalTester $I)
    {
        // clean table before each test
        Post::deleteAll();

        // create first test post
        $post1 = new Post([
            'title' => 'Тестовий пост 1',
            'content' => 'Контент поста 1',
            'category_id' => 1,
            'published' => 1,
        ]);
        $post1->save();

        // create second test post
        $post2 = new Post([
            'title' => 'Тестовий пост 2',
            'content' => 'Контент поста 2',
            'category_id' => 2,
            'published' => 1,
        ]);
        $post2->save();
    }

    /**
     * Test: page should load successfully
     */
    public function openPostsPage(FunctionalTester $I)
    {
        $I->amOnPage('/post/index');
        $I->see('Smart music');
        $I->seeResponseCodeIs(200);
    }

    /**
     * Test: posts from DB should be visible
     */
    public function postsAreVisible(FunctionalTester $I)
    {
        $I->amOnPage('/post/index');

        $I->see('Тестовий пост 1');
        $I->see('Тестовий пост 2');

        $I->see('Контент поста 1');
        $I->see('Контент поста 2');
    }

    /**
     * Test: unpublished posts must not be shown
     */
    public function unpublishedPostHidden(FunctionalTester $I)
    {
        $post = new Post([
            'title' => 'Прихований пост',
            'content' => 'textxxx',
            'category_id' => 1,
            'published' => 0
        ]);
        $post->save();

        $I->amOnPage('/post/index');

        $I->dontSee('Прихований пост');
    }

    /**
     * Test: pagination should work correctly
     */
    public function paginationWorks(FunctionalTester $I)
    {
        // generate many posts for pagination
        for ($i = 1; $i <= 15; $i++) {
            $p = new Post([
                'title' => 'Post ' . $i,
                'content' => 'Lorem ipsum ' . $i,
                'category_id' => 1,
                'published' => 1,
            ]);
            $p->save();
        }

        $I->amOnPage('/post/index');

        $I->seeElement('.pagination'); // pagination is rendered

        $I->click('2'); // go to next page
        $I->seeResponseCodeIs(200);
    }
}
