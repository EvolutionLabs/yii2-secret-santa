<?php declare(strict_types=1);

namespace evo\ssanta\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;
use evo\ssanta\models\SecretSantaListPair;

/**
 * Class SecretSantaListSentNotifyGiver
 * @package evo\ssanta\jobs
 */
class SecretSantaListSentNotifyGiver extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $emailTemplate = '';

    /**
     * @var string
     */
    public $subject = 'You are secret Santa for somebody';

    /**
     * @var int
     */
    public $pairId = 0;

    /**
     * @var string
     */
    public $logo = '';

    /**
     * @var SecretSantaListPair
     */
    protected $pair;

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        /* @var SecretSantaListPair */
        $this->pair = SecretSantaListPair::findOne([
            'id'     => (int)$this->pairId,
            'status' => [SecretSantaListPair::NOT_SENT, SecretSantaListPair::FAILED]
        ]);

        if (empty($this->pair)) {
            log_error('Pair not found');
            return;
        }

        if (!($giver = $this->pair->giver)) {
            log_error('Giver not found');
            return;
        }

        if (!($receiver = $this->pair->receiver)) {
            log_error('Receiver not found');
            return;
        }

        if (!($list = $this->pair->list)) {
            log_error('List not found');
            return;
        }

        /* flag to see whether the email has been sent */
        $sent = false;

        try {
            $sent = (bool)mailer()
                ->compose($this->emailTemplate, [
                    'list'     => $list,
                    'giver'    => $giver,
                    'receiver' => $receiver,
                    'logo'     => $this->logo
                ])
                ->setTo($giver->email)
                ->setSubject(t('app', $this->subject))
                ->send();

        } catch (\Exception $e) {
            log_error($e->getMessage());
        }

        if ($sent) {
            $this->pair->status = SecretSantaListPair::SENT;
            $this->pair->save(false);
            return;
        }

        $this->pair->status = SecretSantaListPair::FAILED;
        $this->pair->save(false);

        /* we throw the exception so the queue can requeue and try resending */
        throw new \Exception(t('app', 'Email to member failed sending!'));
    }
}
