<?php


namespace app\models;


use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, svg, jpeg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
        } else {
            return false;
        }
    }

    public function delete($file)
    {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $file)) {
            return unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $file);
        } else {
            return false;
        }

    }
}
