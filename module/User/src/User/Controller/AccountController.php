<?php

namespace User\Controller;

use RuntimeException;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;

class AccountController extends AbstractActionController
{

    public function passwordAction()
    {
        $serviceManager = $this->getServiceLocator();
        $formElementManager = $serviceManager->get('FormElementManager');

        $passwordForm = $formElementManager->get('User\Form\PasswordForm');
        $passwordMessage = null;

        if ($this->getRequest()->isPost()) {
            $passwordForm->setData($this->params()->fromPost());

            if ($passwordForm->isValid()) {
                $passwordData = $passwordForm->getData();

                $userManager = $serviceManager->get('User\Manager\UserManager');
                $user = current( $userManager->getBy(array('email' => $passwordData['pf-email'])) );

                if ($user) {
                    $mailMessage = $this->t('We have just received your request to reset your password.') . "\r\n\r\n";

                    switch ($user->need('status')) {
                        case 'placeholder':
                            $mailMessage .= $this->t('Unfortunately, your account is considered a placeholder and thus cannot login.');
                            break;
                        case 'guest':
                            $mailMessage .= $this->t('Unfortunately, your account is considered a guest account and thus cannot login.');
                            break;
                        case 'blocked':
                            $mailMessage .= $this->t('Unfortunately, your account is currently blocked. Please contact us for support.');
                            break;
                        case 'disabled':
                            $mailMessage .= $this->t('Unfortunately, your account is currently disabled. Please contact us for support.');
                            break;
                        case 'enabled':
                            $resetCode = base64_encode( substr($user->need('pw'), 16, 8) );

                            $mailMessage .= $this->t('Simply visit the following website to type your new password:') . "\r\n\r\n";
                            $mailMessage .= rtrim($this->option('service.website', false), '/') .
                                $this->url()->fromRoute('user/password-reset', [], ['query' => ['id' => $user->need('uid'), 'code' => $resetCode]]);

                            break;
                        case 'assist':
                        case 'admin':
                            $mailMessage .= $this->t('However, you are using a privileged account. For safety, you cannot reset your password this way. Please contact the system support.');
                            break;
                        default:
                            $mailMessage .= $this->t('Unfortunately, your account seems somewhat unique, thus we are unsure how to treat it. Mind contacting us?');
                            break;
                    }

                    $userMailService = $serviceManager->get('User\Service\MailService');
                    $userMailService->send($user, $this->t('Forgot your password?'), $mailMessage);
                }
            }

            $passwordForm->get('pf-email')->setValue('');

            $passwordMessage = sprintf('%s <div class="small-text">(%s)</div>',
                $this->t('All right, you should receive an email from us soon'),
                $this->t('if we find a valid user account with this email address'));
        }

        return array(
            'passwordForm' => $passwordForm,
            'passwordMessage' => $passwordMessage,
        );
    }

    public function passwordResetAction()
    {
        $resetUid = $this->params()->fromQuery('id');
        $resetCode = $this->params()->fromQuery('code');

        if (! (is_numeric($resetUid) && $resetUid > 0 && preg_match('/^[a-zA-Z0-9\+\/\=]+$/', $resetCode))) {
            throw new RuntimeException('Your token to reset your password is invalid or expired. Please request a new email.');
        }

        $serviceManager = $this->getServiceLocator();

        $userManager = $serviceManager->get('User\Manager\UserManager');
        $user = $userManager->get($resetUid, false);

        if (! $user) {
            throw new RuntimeException('Your token to reset your password is invalid or expired. Please request a new email.');
        }

        $actualResetCode = base64_encode( substr($user->need('pw'), 16, 8) );

        if ($resetCode != $actualResetCode) {
            throw new RuntimeException('Your token to reset your password is invalid or expired. Please request a new email.');
        }

        $formElementManager = $serviceManager->get('FormElementManager');

        $resetForm = $formElementManager->get('User\Form\PasswordResetForm');
        $resetMessage = null;

        if ($this->getRequest()->isPost()) {
            $resetForm->setData($this->params()->fromPost());

            if ($resetForm->isValid()) {
                $resetData = $resetForm->getData();

                $bcrypt = new Bcrypt();
                $bcrypt->setCost(6);

                $user->set('pw', $bcrypt->create($resetData['prf-pw1']));

                $user->set('last_activity', date('Y-m-d H:i:s'));
                $user->set('last_ip', $_SERVER['REMOTE_ADDR']);

                $userManager->save($user);

                $resetMessage = 'All right, your password has been changed. You can now log into your account.';
            }
        }

        return array(
            'resetUid' => $resetUid,
            'resetCode' => $resetCode,
            'resetForm' => $resetForm,
            'resetMessage' => $resetMessage,
        );
    }

    public function registrationAction()
    {
        $serviceManager = $this->getServiceLocator();
        $formElementManager = $serviceManager->get('FormElementManager');

        $registrationForm = $formElementManager->get('User\Form\RegistrationForm');

        if ($this->getRequest()->isPost()) {
            $registrationForm->setData($this->params()->fromPost());

            if ($registrationForm->isValid()) {
                $registrationData = $registrationForm->getData();

                $email = $registrationData['rf-email1'];
                $pw = $registrationData['rf-pw1'];

                $meta = array();
                $meta['gender'] = $registrationData['rf-gender'];
                $meta['firstname'] = ucfirst($registrationData['rf-firstname']);
                $meta['lastname'] = ucfirst($registrationData['rf-lastname']);

                $alias = $meta['firstname'] . ' ' . $meta['lastname'];

                $meta['street'] = $registrationData['rf-street'] . ' ' . $registrationData['rf-number'];
                $meta['zip'] = $registrationData['rf-zip'];
                $meta['city'] = $registrationData['rf-city'];
                $meta['country'] = $registrationData['rf-country'];
                $meta['phone'] = $registrationData['rf-phone'];

                $userManager = $serviceManager->get('User\Manager\UserManager');
                $userSessionManager = $serviceManager->get('User\Manager\UserSessionManager');

                $userManager->create($alias, 'enabled', $email, $pw, $meta);
                $userSessionManager->login($email, $pw);

                return $this->redirectBack()->toOrigin();
            }
        }

        return array(
            'registrationForm' => $registrationForm,
        );
    }

    public function dashboardAction()
    {
        $this->authorize();
    }

    public function bookingsAction()
    {
        $serviceManager = $this->getServiceLocator();
        $userSessionManager = $serviceManager->get('User\Manager\UserSessionManager');

        $user = $userSessionManager->getSessionUser();

        if (! $user) {
            $this->redirectBack()->setOrigin('user/settings');

            return $this->redirect()->toRoute('user/login');
        }
    }

    public function settingsAction()
    {
        $serviceManager = $this->getServiceLocator();
        $userManager = $serviceManager->get('User\Manager\UserManager');
        $userSessionManager = $serviceManager->get('User\Manager\UserSessionManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $user = $userSessionManager->getSessionUser();

        if (! $user) {
            $this->redirectBack()->setOrigin('user/settings');

            return $this->redirect()->toRoute('user/login');
        }

        $editParam = $this->params()->fromQuery('edit');

        /* Email form */

        $editEmailForm = $formElementManager->get('User\Form\EditEmailForm');

        if ($this->getRequest()->isPost() && $editParam == 'email') {
            $editEmailForm->setData($this->params()->fromPost());

            if ($editEmailForm->isValid()) {
                $data = $editEmailForm->getData();

                $email = $data['eef-email1'];

                $user->set('email', $email);
                $userManager->save($user);

                $this->flashMessenger()->addSuccessMessage(sprintf($this->t('Your %semail address%s has been updated'),
                    '<b>', '</b>'));

                return $this->redirect()->toRoute('user/settings');
            }
        } else {
            $editEmailForm->get('eef-email1')->setValue($user->get('email'));
            $editEmailForm->get('eef-email2')->setValue($user->get('email'));
        }

        /* Password form */

        $editPasswordForm = $formElementManager->get('User\Form\EditPasswordForm');

        if ($this->getRequest()->isPost() && $editParam == 'password') {
            $editPasswordForm->setData($this->params()->fromPost());

            if ($editPasswordForm->isValid()) {
                $data = $editPasswordForm->getData();

                $passwordCurrent = $data['epf-pw-current'];
                $passwordNew = $data['epf-pw1'];

                $bcrypt = new Bcrypt();
                $bcrypt->setCost(6);

                if ($bcrypt->verify($passwordCurrent, $user->need('pw'))) {

                    $user->set('pw', $bcrypt->create($passwordNew));
                    $userManager->save($user);

                    $this->flashMessenger()->addSuccessMessage(sprintf($this->t('Your %spassword%s has been updated'),
                        '<b>', '</b>'));

                    return $this->redirect()->toRoute('user/settings');
                } else {
                    $editPasswordForm->get('epf-pw-current')->setMessages(array('This is not your correct password'));
                }
            }
        }

        /* Phone form */

        $editPhoneForm = $formElementManager->get('User\Form\EditPhoneForm');

        if ($this->getRequest()->isPost() && $editParam == 'phone') {
            $editPhoneForm->setData($this->params()->fromPost());

            if ($editPhoneForm->isValid()) {
                $data = $editPhoneForm->getData();

                $phone = $data['epf-phone'];

                $user->setMeta('phone', $phone);
                $userManager->save($user);

                $this->flashMessenger()->addSuccessMessage(sprintf($this->t('Your %sphone number%s has been updated'),
                    '<b>', '</b>'));

                return $this->redirect()->toRoute('user/settings');
            }
        } else {
            $editPhoneForm->get('epf-phone')->setValue($user->getMeta('phone'));
        }

        /* Delete account form */

        $deleteAccountForm = $formElementManager->get('User\Form\DeleteAccountForm');
        $deleteAccountMessage = null;

        if ($this->getRequest()->isPost() && $editParam == 'delete') {
            $deleteAccountForm->setData($this->params()->fromPost());

            if ($deleteAccountForm->isValid()) {
                $data = $deleteAccountForm->getData();

                $why = $data['daf-why'];
                $passwordCurrent = $data['daf-pw-current'];

                $bcrypt = new Bcrypt();
                $bcrypt->setCost(6);

                if ($bcrypt->verify($passwordCurrent, $user->need('pw'))) {

                    $user->set('status', 'deleted');
                    $user->set('last_activity', date('Y-m-d H:i:s'));
                    $user->set('last_ip', $_SERVER['REMOTE_ADDR']);

                    if ($why) {
                        $user->setMeta('deletion.reason', $why);
                    }

                    $userManager->save($user);
                    $userSessionManager->logout();

                    $deleteAccountMessage = sprintf($this->t('Your %suser account has been deleted%s. Good bye!'),
                        '<b>', '</b>');
                } else {
                    $editPasswordForm->get('epf-pw-current')->setMessages(array('This is not your correct password'));
                }
            }
        }

        return array(
            'user' => $user,
            'editEmailForm' => $editEmailForm,
            'editPasswordForm' => $editPasswordForm,
            'editPhoneForm' => $editPhoneForm,
            'deleteAccountForm' => $deleteAccountForm,
            'deleteAccountMessage' => $deleteAccountMessage,
        );
    }

}