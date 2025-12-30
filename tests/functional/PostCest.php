<?php

namespace tests\functional;

use app\models\Post;
use app\models\Category;

class PostCest
{
    private int $cat1Id;
    private int $post1Id;


    public function _before(\FunctionalTester $I)
    {
        Post::deleteAll();
        Category::deleteAll();

        $cat1 = new Category(['name' => 'Test Category 1']);
        $cat1->save(false);
        $this->cat1Id = (int)$cat1->id;

        $cat2 = new Category(['name' => 'Test Category 2']);
        $cat2->save(false);

        $post1 = new Post([
            'title' => 'Тестовий пост 1',
            'content' => 'Контент поста 1',
            'category_id' => $cat1->id,
            'published' => 1,
        ]);
        $post1->save(false);
        $this->post1Id = (int)$post1->id;


        (new Post([
            'title' => 'Тестовий пост 2',
            'content' => 'Контент поста 2',
            'category_id' => $cat2->id,
            'published' => 1,
        ]))->save(false);
    }

    public function openPostsPage(\FunctionalTester $I)
    {
        $I->amOnPage('/post/index');
        $I->see('Smart music');
        $I->seeResponseCodeIs(200);
    }

    public function postsAreVisible(\FunctionalTester $I)
    {
        $I->amOnPage('/post/index');
        $I->see('Тестовий пост 1');
        $I->see('Тестовий пост 2');
        $I->see('Контент поста 1');
        $I->see('Контент поста 2');
    }

    public function unpublishedPostHidden(\FunctionalTester $I)
    {
        (new Post([
            'title' => 'Прихований пост',
            'content' => 'textxxx',
            'category_id' => $this->cat1Id,
            'published' => 0,
        ]))->save(false);

        $I->amOnPage('/post/index');
        $I->dontSee('Прихований пост');
    }

    public function paginationWorks(\FunctionalTester $I)
    {
        for ($i = 1; $i <= 15; $i++) {
            (new Post([
                'title' => 'Post ' . $i,
                'content' => 'Lorem ipsum ' . $i,
                'category_id' => $this->cat1Id,
                'published' => 1,
            ]))->save(false);
        }

        $I->amOnPage('/post/index');
        $I->seeElement('.pagination');

        $I->click('2');
        $I->seeResponseCodeIs(200);
    }

    public function filterByCategoryWorks(\FunctionalTester $I)
    {
        $I->amOnPage('/post/index?cat=' . $this->cat1Id);
        $I->see('Тестовий пост 1');
        $I->dontSee('Тестовий пост 2');
    }

    public function searchByQueryWorks(\FunctionalTester $I)
    {
        $I->amOnPage('/post/index?q=пост+1');
        $I->see('Тестовий пост 1');
        $I->dontSee('Тестовий пост 2');
    }

    public function guestCannotSeeCommentForm(\FunctionalTester $I)
    {
        $I->amOnPage('/post/view?id=' . $this->post1Id);
        $I->seeResponseCodeIs(200);

        // form not shown for guest
        $I->dontSeeElement('form[action*="add-comment"]');
    }
}
