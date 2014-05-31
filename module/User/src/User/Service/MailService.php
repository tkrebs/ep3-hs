<?php

namespace User\Service;

use Base\Manager\ConfigManager;
use Base\Manager\OptionManager;
use Base\Service\AbstractService;
use Base\Service\MailService as BaseMailService;
use User\Entity\User;

class MailService extends AbstractService
{

    protected $baseMailService;
    protected $configManager;
    protected $optionManager;

    public function __construct(BaseMailService $baseMailService, ConfigManager $configManager, OptionManager $optionManager)
    {
        $this->baseMailService = $baseMailService;
        $this->configManager = $configManager;
        $this->optionManager = $optionManager;
    }

    public function send(User $recipient, $subject, $text, array $attachments = array())
    {
        $fromAddress = $this->configManager->need('mail.address');
        $fromName = $this->optionManager->need('client.name.short') . ' ' . $this->optionManager->need('service.name.full');

        $replyToAddress = $this->optionManager->need('client.contact.email');
        $replyToName = $this->optionManager->need('client.name.full');

        $toAddress = $recipient->need('email');
        $toName = $recipient->need('alias');

        $text = sprintf("%s %s,\r\n\r\n%s\r\n\r\n%s,\r\n%s %s-Team\r\n%s",
            $this->t('Dear'), $toName, $text, $this->t('Sincerely'), $this->t("Your"), $fromName, $this->optionManager->need('service.website'));

        $this->baseMailService->sendPlain($fromAddress, $fromName, $replyToAddress, $replyToName, $toAddress, $toName, $subject, $text, $attachments);
    }

    public function notify($subject, $text, array $attachments = array())
    {
        $fromAddress = $this->configManager->need('mail.address');
        $fromName = $this->optionManager->need('client.name.short') . ' ' . $this->optionManager->need('service.name.full');

        $replyToAddress = null;
        $replyToName = null;

        $toAddress = $this->optionManager->need('client.contact.email');
        $toName = $this->optionManager->need('client.name.full');

        $text = sprintf("%s\r\n\r\n%s,\r\n%s %s\r\n%s",
            $text, $this->t('Sincerely'), $this->t("Your"), $fromName, $this->optionManager->need('service.website'));

        $this->baseMailService->sendPlain($fromAddress, $fromName, $replyToAddress, $replyToName, $toAddress, $toName, $subject, $text, $attachments);
    }

}