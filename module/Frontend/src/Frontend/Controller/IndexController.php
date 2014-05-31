<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $calendarViewModel = $this->forward()->dispatch('Calendar\Controller\Index', ['action' => 'index']);
        $calendarViewModel->setCaptureTo('calendar');

        $this->redirectBack()->setOrigin('frontend');

        $viewModel = new ViewModel();
        $viewModel->addChild($calendarViewModel);

        return $viewModel;
    }

}