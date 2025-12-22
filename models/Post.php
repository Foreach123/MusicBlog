<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

class Post extends ActiveRecord
{
    /** @var UploadedFile|null */
    public $imageFile;

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
            [['title','content'], 'required'],
            [['category_id','published'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 255],
            // validation for upload
            [['imageFile'], 'file',
    'extensions' => 'png, jpg, jpeg, gif',
    'checkExtensionByMimeType' => false,
    'maxSize' => 1024 * 1024 * 5,
],

        ];
    }

    //label for file input
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels() ?? [], [
            'imageFile' => 'Image'
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
}
