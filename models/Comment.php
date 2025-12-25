<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property string $content
 * @property string $created_at
 * @property int $status
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends ActiveRecord
{
    public static function tableName()
    {
        return 'comment';
    }

    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string', 'min' => 1, 'max' => 2000],
            [['post_id', 'user_id', 'status', 'parent_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function getReplies()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id'])
            ->andWhere(['status' => 1])
            ->orderBy(['created_at' => SORT_ASC]);
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Comment',
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    
}
