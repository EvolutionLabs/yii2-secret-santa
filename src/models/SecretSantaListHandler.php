<?php declare(strict_types=1);


namespace evolutionlabs\ssanta\models;


use common\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class SecretSantaListHandler
 * @package evolutionlabs\ssanta\models
 */
class SecretSantaListHandler extends Model
{
    /**
     * @var integer
     */
    public $listId;

    /**
     * @var integer
     */
    public $userId;

    /** @var array */
    private $_pairs;

    /**
     * @var SecretSantaList | null
     */
    private $_list;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['listId', 'userId'], 'required'],
            [['listId', 'userId'], 'integer'],
            [['listId'], 'exist', 'skipOnError' => true, 'targetClass' => SecretSantaList::class, 'targetAttribute' => ['listId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
            ['listId', '_validateSecretSantaList']
        ];
    }

    /**
     * @retrun void
     */
    public function run(): void
    {
        if (!$this->validate()) {
            return;
        }

        $this->createPairsModels();
    }

    /**
     * @retrun void
     */
    protected function createPairsModels(): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            //Cleanup previous assignments
            SecretSantaListPair::deleteAll(['list_id' => $this->listId]);

            $pairs = $this->getPairs();

            foreach ($pairs as $pair) {
                $pairModel = new SecretSantaListPair();
                $pairModel->list_id     = (int)$this->listId;
                $pairModel->giver_id    = $pair['id'];
                $pairModel->receiver_id = $pair['givingTo']['id'];
                $pairModel->status      = SecretSantaListPair::NOT_SENT;
                $pairModel->save(false);
            }

            $this->getList()->saveStatus(SecretSantaList::STATUS_READY);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('listId', $e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getPairs(): array
    {
        if ($this->_pairs !== null) {
            return $this->_pairs;
        }
        return $this->_pairs = $this->createPairs();
    }

    /**
     * @retrun array
     */
    protected function createPairs(): array
    {
        $members = $this->getList()->getMembers()->asArray()->all();

        $givers = $receivers = $members;

        foreach($givers as $i => $member) {
            $notAssigned = true;
            while ($notAssigned) {
                // randomly choose a person
                $randomMemberIndex = mt_rand(0, count($receivers) - 1);

                // if chosen member isn't themselves
                if (
                    $member['email'] !== $receivers[$randomMemberIndex]['email']
                    && (!isset($receivers[$randomMemberIndex]['receivingFrom']) || $receivers[$randomMemberIndex]['receivingFrom']['email'] != $member['email'])
                ) {
                    $receivers[$randomMemberIndex]['receivingFrom'] = $member;
                    // assign the user the randomly picked user
                    $givers[$i]['givingTo'] = $receivers[$randomMemberIndex];

                    // remove them from future receivers list
                    unset($receivers[$randomMemberIndex]);

                    // reset array keys allowing next iteration
                    $receivers = array_values($receivers);

                    $notAssigned = false;
                } else if (count($receivers) === 1) {
                    // if only one person left, and they've been assigned themselves
                    // swap givingTo person from the first user
                    $givers[$i]['givingTo'] = $givers[0]['givingTo'];
                    $givers[0]['givingTo'] = $givers[$i];
                    $notAssigned = false;
                }
            }
        }

        return $givers;
    }

    /**
     * @return array
     */
    public function getFromToEmails(): array
    {

        if (!($pairs = $this->pairs)) {
            return [];
        }

        $result = [];
        foreach ($pairs as $pair) {
            $result[] = $pair['email'] . ' -> ' . $pair['givingTo']['email'];
        }

        return $result;

    }

    /**
     * @return SecretSantaList|null
     */
    public function getList(): ?SecretSantaList
    {
        if ($this->_list !== null) {
            return $this->_list;
        }
        return $this->_list = SecretSantaList::findOne((int)$this->listId);
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function _validateSecretSantaList($attribute, $params)
    {
        if ($this->hasErrors($attribute)) {
            return;
        }

        if (!($list = SecretSantaList::findOne((int)$this->$attribute))) {
            $this->addError($attribute, t('app', 'Could not find the Secret Santa List'));
            return;
        }

        if (count($list->members) < 3) {
            $this->addError($attribute, t('app', 'Your Secret Santa list should have at least 3 members'));
            return;
        }
    }
}
