<?php

namespace Backend\Controller;

use Backend\Form\Config\EditConfigForm;
use Zend\Mvc\Controller\AbstractActionController;

class ConfigController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $optionManager = $serviceManager->get('Base\Manager\OptionManager');

        $editForms = array();

        foreach ($this->backendConfigEditDefinitions() as $definitionId => $definition) {
            $definitionLabel = $definition['label'];
            $definitionElements = $definition['elements'];

            $editForm = new EditConfigForm($definitionId, $definitionElements);
            $editFormPrefix = $editForm->getPrefix();

            if ($this->getRequest()->isPost() && $this->params()->fromPost(sprintf('%s-submit', $editFormPrefix))) {
                $editForm->setData($this->params()->fromPost());

                if ($editForm->isValid()) {
                    $editData = $editForm->getData();

                    foreach ($definitionElements as $key => $definition) {
                        $id = sprintf('%s-%s',
                            $editFormPrefix, str_replace('.', '-', $key));

                        $type = $definition['type'];

                        if (isset($editData[$id])) {
                            $value = $editData[$id];

                            if ($type == 'Checkbox') {
                                if ($value) {
                                    $value = 'true';
                                } else {
                                    $value = 'false';
                                }
                            }

                            if (isset($definition['i18n']) && ! $definition['i18n']) {
                                $locale = null;
                            } else {
                                $locale = $this->config('i18n.locale');

                                if ($locale == 'en-US') {
                                    $locale = null;
                                }
                            }

                            $optionManager->set($key, $value, $locale);
                        }
                    }

                    $this->flashMessenger()->addSuccessMessage('Configuration has been updated');

                    return $this->redirect()->toRoute('backend/config');
                }
            } else {
                foreach ($definitionElements as $key => $definition) {
                    $id = sprintf('%s-%s',
                        $editFormPrefix, str_replace('.', '-', $key));

                    $type = $definition['type'];

                    $value = $optionManager->get($key);

                    if ($type == 'Checkbox') {
                        if ($value == 'true') {
                            $value = 1;
                        } else {
                            $value = 0;
                        }
                    }

                    $editForm->setData(array(
                        $id => $value,
                    ));
                }
            }

            $editForms[$definitionLabel] = $editForm;
        }

        return array(
            'editForms' => $editForms,
        );
    }

    public function infoAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $optionManager = $serviceManager->get('Base\Manager\OptionManager');

        $infoText = $optionManager->get('subject.info');

        if ($this->getRequest()->isPost()) {
            $infoTextParam = $this->params()->fromPost('ecf-info');

            $locale = $this->config('i18n.locale');

            if ($locale == 'en-US') {
                $locale = null;
            }

            $optionManager->set('subject.info', $infoTextParam, $locale);

            $this->flashMessenger()->addSuccessMessage('Info page has been updated');

            return $this->redirect()->toRoute('backend/config/info');
        }

        return array(
            'infoText' => $infoText,
        );
    }

    public function helpAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $optionManager = $serviceManager->get('Base\Manager\OptionManager');

        $helpText = $optionManager->get('subject.help');

        if ($this->getRequest()->isPost()) {
            $helpTextParam = $this->params()->fromPost('ecf-help');

            $locale = $this->config('i18n.locale');

            if ($locale == 'en-US') {
                $locale = null;
            }

            $optionManager->set('subject.help', $helpTextParam, $locale);

            $this->flashMessenger()->addSuccessMessage('Help page has been updated');

            return $this->redirect()->toRoute('backend/config/help');
        }

        return array(
            'helpText' => $helpText,
        );
    }

    public function rulesAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $optionManager = $serviceManager->get('Base\Manager\OptionManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $editRulesForm = $formElementManager->get('Backend\Form\Config\EditRulesForm');

        if ($this->getRequest()->isPost()) {
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $editRulesForm->setData($post);

            if ($editRulesForm->isValid()) {
                $editRulesData = $editRulesForm->getData();

                $locale = $this->config('i18n.locale');

                if ($locale == 'en-US') {
                    $locale = null;
                }

                $optionManager->set('subject.rules.document.name', $editRulesData['erf-document-name'], $locale);

                if (isset($editRulesData['erf-document-file']['name']) && isset($editRulesData['erf-document-file']['tmp_name'])) {
                    $documentFileTmpName = $editRulesData['erf-document-file']['tmp_name'];

                    $documentFileName = $editRulesData['erf-document-file']['name'];
                    $documentFileName = str_replace('.pdf', '', $documentFileName);
                    $documentFileName = trim($documentFileName);
                    $documentFileName = preg_replace('/[^a-zA-Z0-9 -]/', '', $documentFileName);
                    $documentFileName = str_replace(' ', '-', $documentFileName);
                    $documentFileName = strtolower($documentFileName);

                    $destination = sprintf('/docs-client/upload/%s.pdf',
                        $documentFileName);

                    move_uploaded_file($documentFileTmpName, sprintf('%s/public%s', getcwd(), $destination));

                    $optionManager->set('subject.rules.document.file', $destination, $locale);
                }

                $optionManager->set('subject.rules.text', $editRulesData['erf-text'], $locale);

                $this->flashMessenger()->addSuccessMessage('Rules have been updated');

                return $this->redirect()->toRoute('backend/config/rules');
            }
        } else {
            $editRulesForm->setData(array(
                'erf-document-name' => $optionManager->get('subject.rules.document.name'),
                'erf-text' => $optionManager->get('subject.rules.text'),
            ));
        }

        $documentFileElement = $editRulesForm->get('erf-document-file');
        $documentFileElementOptions = $documentFileElement->getOptions();
        $documentFileElementNotes = $documentFileElementOptions['notes'];

        $documentFileElement->setOptions(array_merge($documentFileElement->getOptions(), array(
            'notes' => sprintf('%s<br>(<a href="%s" target="_blank">%s</a> %s)',
                $this->t($documentFileElementNotes),
                $optionManager->get('subject.rules.document.file'),
                $this->t('current document'),
                $this->t('will be replaced')),
        )));

        return array(
            'editRulesForm' => $editRulesForm,
        );
    }

    public function confirmationAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $optionManager = $serviceManager->get('Base\Manager\OptionManager');

        $confirmationText = $optionManager->get('subject.confirmation.text');

        if ($this->getRequest()->isPost()) {
            $confirmationTextParam = $this->params()->fromPost('ecf-text');

            $locale = $this->config('i18n.locale');

            if ($locale == 'en-US') {
                $locale = null;
            }

            $optionManager->set('subject.confirmation.text', $confirmationTextParam, $locale);

            $this->flashMessenger()->addSuccessMessage('Confirmation text has been updated');

            return $this->redirect()->toRoute('backend/config/confirmation');
        }

        return array(
            'confirmationText' => $confirmationText,
        );
    }

}