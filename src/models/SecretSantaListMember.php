<?php declare(strict_types=1);

namespace evolutionlabs\ssanta\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%secret_santa_list_member}}".
 *
 * @property string $id
 * @property string $list_id
 * @property string $name
 * @property string $email
 *
 * @property SecretSantaList $list
 * @property SecretSantaListPair[] $pairsAsGiver
 * @property SecretSantaListPair[] $pairsAsReceiver
 */
class SecretSantaListMember extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%secret_santa_list_member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'address'], 'required'],
            [['name', 'email'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 255],
            ['email', 'email'],
            [['list_id', 'email'], 'unique', 'targetAttribute' => ['list_id', 'email']],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => SecretSantaList::class, 'targetAttribute' => ['list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => Yii::t('app', 'ID'),
            'list_id' => Yii::t('app', 'List'),
            'name'    => Yii::t('app', 'Name'),
            'email'   => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
        ];
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        if ($this->name) {
            return sprintf('%s - (%s)', (string)$this->name, (string)$this->email);
        }
        return (string)$this->email;
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
    public function getPairsAsGiver()
    {
        return $this->hasMany(SecretSantaListPair::class, ['giver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPairsAsReceiver()
    {
        return $this->hasMany(SecretSantaListPair::class, ['receiver_id' => 'id']);
    }
}
