<?php

namespace app\models;

use Yii;

use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model

{

    public $image;

    public function rules()
{
    return [
        [['image'], 'required'], // Поле `image` обов'язкове
        [['image'], 'file', 'extensions' => 'jpg, png', 'maxSize' => 1024 * 1024 * 5], // Обмеження по формату та розміру
    ];
}

    public function uploadFile(UploadedFile $file, $currentImage)
{
    $this->image = $file;
    if ($this->validate()) {
        $this->deleteCurrentImage($currentImage);
    
        $fileName = strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
        $filePath = Yii::getAlias('@webroot/uploads') . '/' . $fileName;

        if ($file->saveAs($filePath)) {
            return $fileName; // Повертаємо назву файлу
        }
    }

    return null; // Якщо файл не завантажено
}

public function deleteCurrentImage($currentImage)
{
    if (file_exists(Yii::getAlias('@web') . 'uploads/' . $currentImage) &&

        is_file(Yii::getAlias('@web') . 'uploads/' . $currentImage)) {

        unlink(Yii::getAlias('@web') . 'uploads/' . $currentImage);

    }
}

}