<?php declare(strict_types=1);

namespace evo\ssanta\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%secret_santa_list_pair}}".
 *
 * @property string $id
 * @property string $list_id
 * @property string $status
 * @property string $giver_id
 * @property string $receiver_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SecretSantaListMember $giver
 * @property SecretSantaList $list
 * @property SecretSantaListMember $receiver
 */
class SecretSantaListPair extends \yii\db\ActiveRecord
{
    const NOT_SENT = 'not-sent';
    const SENT     = 'sent';
    const FAILED   = 'failed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%secret_santa_list_pair}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['list_id', 'giver_id', 'receiver_id'], 'required'],
            [['list_id', 'giver_id', 'receiver_id'], 'integer'],
            [['status'], 'string'],
            [
                ['list_id', 'giver_id', 'receiver_id'],
                'unique',
                'targetAttribute' => ['list_id', 'giver_id', 'receiver_id']
            ],
            [
                ['giver_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SecretSantaListMember::class,
                'targetAttribute' => ['giver_id' => 'id']
            ],
            [
                ['list_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SecretSantaList::class,
                'targetAttribute' => ['list_id' => 'id']
            ],
            [
                ['receiver_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => SecretSantaListMember::class,
                'targetAttribute' => ['receiver_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'list_id'     => Yii::t('app', 'List'),
            'status'      => Yii::t('app', 'Status'),
            'giver_id'    => Yii::t('app', 'Giver'),
            'receiver_id' => Yii::t('app', 'Receiver'),
            'created_at'  => Yii::t('app', 'Created at'),
            'updated_at'  => Yii::t('app', 'Updated at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGiver()
    {
        return $this->hasOne(SecretSantaListMember::class, ['id' => 'giver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(SecretSantaList::class, ['id' => 'list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(SecretSantaListMember::class, ['id' => 'receiver_id']);
    }
}
