<?php
namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    // db table
    public static function tableName()
    {
        return 'category';
    }

    // all posts by category
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['category_id' => 'id']);
    }
}
