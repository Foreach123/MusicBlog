<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

class Post extends ActiveRecord
{
    /** @var UploadedFile|null */
    public $imageFile;
    public $tagsInput;

    // db table
    public static function tableName()
    {
        return 'posts';
    }

    // category
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['category_id', 'published'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 255],
            // validation for upload
            [
                ['imageFile'],
                'file',
                'extensions' => 'png, jpg, jpeg, gif',
                'checkExtensionByMimeType' => false,
                'maxSize' => 1024 * 1024 * 5,
            ],
            [['tagsInput'], 'safe'],
        ];
    }

    //label for file input
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels() ?? [], [
            'imageFile' => 'Image',
            'tagsInput' => 'Tags (comma separated)'

        ]);
    }

    /**
     * Save uploaded file to disk and set $this->image
     * @return bool
     */
    public function uploadImage()
    {
        if (!$this->imageFile instanceof UploadedFile) {
            return true; // nothing uploaded
        }

        $uploadsPath = Yii::getAlias('@webroot/uploads');
        if (!is_dir($uploadsPath)) {
            mkdir($uploadsPath, 0755, true);
        }

        // unique filename
        $filename = uniqid('post_') . '.' . $this->imageFile->extension;
        $full = $uploadsPath . DIRECTORY_SEPARATOR . $filename;

        if ($this->imageFile->saveAs($full)) {
            // delete old image if exists
            if (!empty($this->image) && file_exists($uploadsPath . DIRECTORY_SEPARATOR . $this->image)) {
                @unlink($uploadsPath . DIRECTORY_SEPARATOR . $this->image);
            }
            $this->image = $filename;
            return true;
        }

        return false;
    }

    // Remove image file from disk
    public function removeImageFile()
    {
        if (!empty($this->image)) {
            $path = Yii::getAlias('@webroot/uploads/' . $this->image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    public function getPostTags()
    {
        return $this->hasMany(PostTag::class, ['post_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tags::class, ['id' => 'tag_id'])
            ->via('postTags');
    }

    public function syncTagsFromInput(): void
    {
        $raw = (string)$this->tagsInput;
        $names = array_filter(array_map('trim', preg_split('/[,;]+/', $raw)));

        // delete old links
        PostTag::deleteAll(['post_id' => $this->id]);

        if (!$names) return;

        $tagIds = [];
        foreach ($names as $name) {
            $tag = Tags::findOne(['name' => $name]);
            if (!$tag) {
                $tag = new Tags();
                $tag->name = $name;
                $tag->slug = $this->slugify($name);
                $tag->save(false);
            }
            $tagIds[] = $tag->id;
        }

        // insert new links
        foreach (array_unique($tagIds) as $tagId) {
            $link = new PostTag();
            $link->post_id = $this->id;
            $link->tag_id = $tagId;
            $link->save(false);
        }
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9а-яіїєґ]+/iu', '-', $text);
        $text = trim($text, '-');
        return $text ?: uniqid('tag_');
    }

    public function loadTagsToInput(): void
    {
        $this->tagsInput = implode(', ', array_map(fn($t) => $t->name, $this->tags));
    }

    public function getComments()
    {
        return $this->hasMany(\app\models\Comment::class, ['post_id' => 'id'])
            ->andWhere(['status' => 1, 'parent_id' => null])
            ->orderBy(['created_at' => SORT_DESC]);
    }
}
