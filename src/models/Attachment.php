<?php

namespace davidxu\base\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "{{%common_attachment}}".
 *
 * @property int $id ID
 * @property int|null $member_id Uploader
 * @property string|null $drive Driver
 * @property string|null $file_type File type
 * @property string|null $specific_type Specific type
 * @property string|null $path File path
 * @property string|null $poster Video poster
 * @property string|null $hash File hash
 * @property string|null $name Original name
 * @property string|null $extension Extension
 * @property int|null $size File size
 * @property int|null $year Year
 * @property int|null $month Month
 * @property int|null $day Day
 * @property int|null $width Width
 * @property int|null $height Height
 * @property string|null $duration Duration
 * @property string|null $upload_ip Upload IP
 * @property int|null $status Status[-1:Deleted;0:Disabled;1:Enabled]
 * @property int $created_at Created at
 * @property int $updated_at Updated at
 *
 */
class Attachment extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%common_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['member_id', 'size', 'year', 'month', 'day', 'width', 'height', 'status'], 'integer'],
            [['drive', 'extension', 'duration', 'upload_ip'], 'string', 'max' => 50],
            [['width', 'height'], 'default', 'value' => 0],
            ['drive', 'default', 'value' => 'local'],
            ['status', 'default', 'value' => 1],
            [['file_type'], 'string', 'max' => 10],
            [['specific_type', 'hash'], 'string', 'max' => 100],
            [['path', 'poster'], 'string', 'max' => 1024],
            [['name'], 'string', 'max' => 200],
//            [['hash'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('base', 'ID'),
            'member_id' => Yii::t('base', 'Uploader'),
            'drive' => Yii::t('base', 'Driver'),
            'file_type' => Yii::t('base', 'Upload type'),
            'specific_type' => Yii::t('base', 'Specific type'),
            'path' => Yii::t('base', 'File path'),
            'poster' => Yii::t('base', 'Video poster'),
            'hash' => Yii::t('base', 'File hash'),
            'name' => Yii::t('base', 'Original name'),
            'extension' => Yii::t('base', 'Extension'),
            'size' => Yii::t('base', 'File size'),
            'year' => Yii::t('base', 'Year'),
            'month' => Yii::t('base', 'Month'),
            'day' => Yii::t('base', 'Day'),
            'width' => Yii::t('base', 'Width'),
            'height' => Yii::t('base', 'Height'),
            'duration' => Yii::t('base', 'Duration'),
            'upload_ip' => Yii::t('base', 'Upload IP'),
            'status' => Yii::t('base', 'Status[-1:Deleted;0:Disabled;1:Enabled]'),
            'created_at' => Yii::t('base', 'Created at'),
            'updated_at' => Yii::t('base', 'Updated at'),
        ];
    }
}
