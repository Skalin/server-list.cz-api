<?php

namespace app\modules\admin\modules\FileModule\models;

use Yii;
use app\components\SC;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $filename
 * @property string $realpath
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'realpath'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'realpath' => 'Realpath',
        ];
    }

    public function getFileUrl()
    {
        return SC::getThemeFile($this->realpath);
    }
}
