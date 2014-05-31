<?php

namespace Backend\Controller;

use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $users = $userManager->getRegular('uid DESC');

        return array(
            'users' => $users,
        );
    }

    public function editAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $userManager = $serviceManager->get('User\Manager\UserManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $uid = $this->params()->fromRoute('uid');

        if ($uid) {
            $user = $userManager->get($uid);
        } else {
            $user = null;
        }

        $editUserForm = $formElementManager->get('Backend\Form\User\EditUserForm');

        if ($this->getRequest()->isPost()) {
            $editUserForm->setData($this->params()->fromPost());

            if ($editUserForm->isValid()) {
                $eud = $editUserForm->getData();

                if (! $user) {
                    $user = new User();
                }

                /* Account data */

                $user->set('alias', $eud['euf-alias']);
                $user->set('status', $eud['euf-status']);
                $user->set('email', $eud['euf-email']);

                $pw = $eud['euf-pw'];

                if ($pw) {
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost(6);

                    $user->set('pw', $bcrypt->create($pw));
                }

                /* Personal data */

                $user->setMeta('gender', $eud['euf-gender']);
                $user->setMeta('firstname', $eud['euf-firstname']);
                $user->setMeta('lastname', $eud['euf-lastname']);
                $user->setMeta('street', $eud['euf-street']);
                $user->setMeta('zip', $eud['euf-zip']);
                $user->setMeta('city', $eud['euf-city']);

                if ($eud['euf-country'] == '0') {
                    $country = null;
                } else {
                    $country = $eud['euf-country'];
                }

                $user->setMeta('country', $country);

                $user->setMeta('phone', $eud['euf-phone']);
                $user->setMeta('notes', $eud['euf-notes']);

                $userManager->save($user);

                $this->flashMessenger()->addSuccessMessage('User has been saved');

                return $this->redirect()->toRoute('backend/user');
            }
        } else {
            if ($user) {
                $editUserForm->setData(array(
                    'euf-uid' => $user->need('uid'),
                    'euf-alias' => $user->need('alias'),
                    'euf-status' => $user->need('status'),
                    'euf-email' => $user->need('email'),
                    'euf-gender' => $user->getMeta('gender'),
                    'euf-firstname' => $user->getMeta('firstname'),
                    'euf-lastname' => $user->getMeta('lastname'),
                    'euf-street' => $user->getMeta('street'),
                    'euf-zip' => $user->getMeta('zip'),
                    'euf-city' => $user->getMeta('city'),
                    'euf-country' => $user->getMeta('country'),
                    'euf-phone' => $user->getMeta('phone'),
                    'euf-notes' => $user->getMeta('notes'),
                ));
            }
        }

        return array(
            'editUserForm' => $editUserForm,
            'user' => $user,
        );
    }

    public function deleteAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $uid = $this->params()->fromRoute('uid');

        $user = $userManager->get($uid);

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $userManager->delete($user);

            $this->flashMessenger()->addSuccessMessage(sprintf($this->t('User %s has been deleted'), $user->need('alias')));

            return $this->redirect()->toRoute('backend/user');
        }

        return array(
            'user' => $user,
        );
    }

    public function interpretAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $term = $this->params()->fromQuery('term');

        $usersMax = 15;

        $users = $userManager->interpret($term, $usersMax);

        $usersList = array();

        foreach ($users as $uid => $user) {
            $usersList[] = sprintf('%s (%s)', $user->need('alias'), $uid);
        }

        if (count($usersList) == $usersMax) {
            $usersList[] = '[...]';
        }

        return $this->jsonViewModel($usersList);
    }

}