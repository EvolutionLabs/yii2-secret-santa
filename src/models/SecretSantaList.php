<?php declare(strict_types=1);

namespace evolutionlabs\ssanta\models;

use Yii;
use common\models\User;
use evolutionlabs\ssanta\yii\base\Event;

/**
 * This is the model class for table "{{%secret_santa_list}}".
 *
 * @property string $id
 * @property int $user_id
 * @property string $status
 * @property string $name
 *
 * @property User $user
 * @property SecretSantaListMember[] $members
 * @property SecretSantaListPair[] $pairs
 */
class SecretSantaList extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_READY = 'ready';

    const EVENT_SECRET_SANTA_LIST_SENT = 'secret-santa.models.secret-santa-list.sent';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%secret_santa_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['status'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'status'  => Yii::t('app', 'Status'),
            'name'    => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return bool
     */
    public function send(): bool
    {
        $pairs = $this->getPairs();

        if ((int)$pairs->count() < 1) {
            return false;
        }
        foreach ($pairs->each(30) as $pair) {
            /* Trigger the event */
            app()->trigger(self::EVENT_SECRET_SANTA_LIST_SENT, new Event([
                'params' => [
                    'pair' => $pair,
                ]
            ]));
        }
        $this->saveStatus(SecretSantaList::STATUS_SENT);

        return true;
    }

    /**
     * @param string $status
     * @return bool
     */
    public function saveStatus(string $status = self::STATUS_DRAFT): bool
    {
        if ($this->getIsNewRecord()) {
            return false;
        }

        if ($status && $status === (string)$this->status) {
            return true;
        }

        if ($status) {
            $this->status = $status;
        }

        return (bool)$this->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(SecretSantaListMember::class, ['list_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPairs()
    {
        return $this->hasMany(SecretSantaListPair::class, ['list_id' => 'id']);
    }
}
