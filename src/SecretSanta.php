<?php declare(strict_types=1);

namespace evolutionlabs\ssanta;

use yii\base\Module;
use evolutionlabs\ssanta\{
    jobs\SecretSantaListSentNotifyGiver, models\SecretSantaList
};

/**
 * Class SecretSanta
 * @package evolutionlabs\ssanta
 */
class SecretSanta extends Module
{
    /**
     * @var string
     */
    public $emailTemplate = '@evo/ssanta/mail/notify-giver';

    /**
     * @var string
     */
    public $logo = '@evo/ssanta/img/logo-carturesti.png';

    /**
     * @var string
     */
    public $subject = 'You are secret Santa for somebody';

    /**
     * @var bool
     */
    public $useDefaultEmailSolution = false;

    /**
     * @var string
     */
    public $controllerNamespace = 'evolutionlabs\ssanta\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->useDefaultEmailSolution) {
            /* After the secret santa list is sent */
            app()->on(SecretSantaList::EVENT_SECRET_SANTA_LIST_SENT, function ($event) {
                queue()->delay(10)->push(new SecretSantaListSentNotifyGiver([
                    'pairId'        => $event->params['pair']->id,
                    'emailTemplate' => $this->emailTemplate,
                    'subject'       => $this->subject,
                    'logo'          => app()->frontendUrlManager->createAbsoluteUrl($this->logo)
                ]));
            });
        }

        parent::init();
    }
}
