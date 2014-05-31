<?php

namespace Setup\Controller;

use Setup\Form\OptionsForm;
use Setup\Form\UserForm;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $this->validateSetup('index');
    }

    public function tablesAction()
    {
        $this->validateSetup('tables');

        $import = false;
        $importMessage = null;

        $sqlFile = getcwd() . '/data/db/ep3-hs.sql';

        if (is_readable($sqlFile)) {

            $sqlContent = file_get_contents($sqlFile);

            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $db = $dbAdapter->getDriver()->getConnection()->getResource();

            if ($db instanceof \PDO) {
                $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 0);

                try {
                    $db->exec($sqlContent);

                    $statement = $db->query('SHOW TABLES');
                    $statement->execute();

                    $res = $statement->fetchAll();

                    if (count($res) > 10) {
                        $import = true;
                    } else {
                        $import = false;
                    }
                } catch (\PDOException $e) {
                    $importMessage = $e->getMessage();
                }
            } else {
                $importMessage = 'Unsupported database adapter configured (PDO required)';
            }
        } else {
            $importMessage = 'SQL file <code>' . $sqlFile . '</code> not found';
        }

        return array(
            'import' => $import,
            'importMessage' => $importMessage,
        );
    }

    public function recordsAction()
    {
        $this->validateSetup('records');

        $optionsForm = new OptionsForm();
        $optionsForm->init();

        if ($this->getRequest()->isPost()) {
            $optionsForm->setData($this->params()->fromPost());

            if ($optionsForm->isValid()) {
                $optionsData = $optionsForm->getData();

                /* Setup options */

                $optionManager = $this->getServiceLocator()->get('Base\Manager\OptionManager');

                foreach (OptionsForm::$definitions as $key => $value) {
                    $formKey = str_replace('.', '_', $key);
                    $formValue = $optionsData['cf-' . $formKey];

                    $optionManager->set($key, $formValue);
                }

                /* Setup default options */

                $uri = $this->getRequest()->getUri();
                $base = sprintf('%s://%s/', $uri->getScheme(), $uri->getHost());

                $optionManager->set('service.website', $base);
                $optionManager->set('service.branding', 'true');
                $optionManager->set('service.branding.name', $this->t('ep-3 Hotelsystem'));
                $optionManager->set('service.branding.website', 'http://hs.hbsys.de/');

                return $this->redirect()->toRoute('setup/user');
            }
        } else {
            $optionsForm->setData(array(
                'cf-service_name_full' => 'Bookingsystem',
                'cf-service_name_short' => 'BS',
                'cf-subject_type' => 'our Hotel',
            ));
        }

        return array(
            'optionsForm' => $optionsForm,
        );
    }

    public function userAction()
    {
        $this->validateSetup('user');

        $userForm = new UserForm();
        $userForm->init();

        if ($this->getRequest()->isPost()) {
            $userForm->setData($this->params()->fromPost());

            if ($userForm->isValid()) {
                $userData = $userForm->getData();

                $firstname = $userData['uf-firstname'];
                $lastname = $userData['uf-lastname'];
                $email = $userData['uf-email'];
                $pw = $userData['uf-pw'];

                $alias = sprintf('%s %s',
                    $firstname,
                    $lastname);

                $userManager = $this->getServiceLocator()->get('User\Manager\UserManager');

                $user = $userManager->create($alias, 'admin', $email, $pw);

                if ($user) {
                    return $this->redirect()->toRoute('setup/complete');
                }
            }
        } else {
            $userForm->setData(array(
                'uf-email' => $this->option('client.contact.email'),
            ));
        }

        return array(
            'userForm' => $userForm,
        );
    }

    public function completeAction()
    { }

}