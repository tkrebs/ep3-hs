<?php

namespace Base\Controller\Plugin;

use Zend\I18n\Translator\TranslatorInterface as Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Translate extends AbstractPlugin
{

    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke($message)
    {
        return $this->translator->translate($message);
    }

}