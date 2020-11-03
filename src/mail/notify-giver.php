<?php
/** @var \evolutionlabs\ssanta\models\SecretSantaListMember $giver */
/** @var \evolutionlabs\ssanta\models\SecretSantaListMember $receiver */
/** @var \evolutionlabs\ssanta\models\SecretSantaList $list */

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td align="center" style="min-height: 120px;">
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center" style="padding: 10px 0 10px 0;">
                        <img src="<?php echo $logo; ?>" alt="Logo" width="100" height="100" style="display: block; height: 100px; width: auto;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 20px 0 20px;">
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" style="border-bottom: 1px solid #F5F5F5;">
                <tr>
                    <td align="center" style="padding: 10px 20px 30px 20px; color: #3C3C3B; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px;">
                        <p style="padding: 0 0 10px 0;  margin: 0 0 0 0;"><?php echo t('app', 'Dear, {giver}!', ['giver' => html_encode($giver->name)]);?></p>
                        <p style="padding: 0 0 10px 0;  margin: 0 0 0 0;"><?php echo t('app', 'You were selected as the Secret Santa for {receiver}', ['receiver' => html_encode($receiver->name)]);?></p>
                        <p style="padding: 0 0 10px 0;  margin: 0 0 0 0;"><?php echo t('app', 'Here are some suggestions for you...');?></p>
                        <p style="padding: 0 0 10px 0;  margin: 0 0 0 0;"><?php echo t('app', 'Best regards,');?></p>
                        <p style="padding: 0 0 10px 0;  margin: 0 0 0 0;"><?php echo t('app', 'Your truly {listOwner}', ['listOwner' => $list->user->getDisplayName()]);?></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
