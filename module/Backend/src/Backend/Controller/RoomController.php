<?php

namespace Backend\Controller;

use Room\Entity\Room;
use RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class RoomController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $rooms = $roomManager->getAll('rnr ASC');

        return array(
            'rooms' => $rooms,
        );
    }

    public function editAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $rid = $this->params()->fromRoute('rid');

        if ($rid) {
            $room = $roomManager->get($rid);
        } else {
            $room = null;
        }

        $editRoomForm = $formElementManager->get('Backend\Form\Room\EditRoomForm');

        if ($this->getRequest()->isPost()) {
            $editRoomForm->setData(array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            ));

            if ($editRoomForm->isValid()) {
                $erd = $editRoomForm->getData();

                if (! $room) {
                    $room = new Room();
                    $room->set('status', 'enabled');
                }

                $room->set('rnr', $erd['erf-rnr']);
                $room->set('status', $erd['erf-status']);
                $room->set('capacity', $erd['erf-capacity']);

                $room->setMeta('name', $erd['erf-name']);
                $room->setMeta('info', $erd['erf-info']);
                $room->setMeta('description', $erd['erf-description']);

                $roomManager->save($room);

                /* Check picture upload */

                if (isset($erd['erf-picture']['tmp_name']) && $erd['erf-picture']['tmp_name']) {
                    $pictureService = $serviceManager->get('Base\Service\PictureService');

                    $pictureService->uploadPicture($erd['erf-picture']['tmp_name'], sprintf('/room/%s/%s.jpg',
                        $room->need('rid'), $room->getNextPictureNumber()));

                    $room->addPictureNumber();

                    $roomManager->save($room);
                }

                $this->flashMessenger()->addSuccessMessage('Room has been saved');

                return $this->redirect()->toRoute('backend/room');
            }
        } else {
            if ($room) {
                $editRoomForm->setData(array(
                    'erf-rnr' => $room->need('rnr'),
                    'erf-status' => $room->need('status'),
                    'erf-capacity' => $room->need('capacity'),
                    'erf-name' => $room->getMeta('name'),
                    'erf-info' => $room->getMeta('info'),
                    'erf-description' => $room->getMeta('description'),
                ));
            }
        }

        return array(
            'editRoomForm' => $editRoomForm,
            'room' => $room,
        );
    }

    public function deleteAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $rid = $this->params()->fromRoute('rid');

        $room = $roomManager->get($rid);

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $roomManager->delete($room);

            $this->flashMessenger()->addSuccessMessage('Room has been deleted');

            return $this->redirect()->toRoute('backend/room');
        }

        return array(
            'room' => $room,
        );
    }

    public function promotePictureAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $rid = $this->params()->fromRoute('rid');
        $pid = $this->params()->fromRoute('pid');

        $room = $roomManager->get($rid);

        if (! $room->hasPictureNumber($pid)) {
            throw new RuntimeException('This picture is not registered');
        }

        $pictureNumbers = $room->getPictureNumbers();
        $pictureNumbersSorted = array();

        $pictureNumbersSorted[] = $pid;

        foreach ($pictureNumbers as $pictureNumber) {
            if ($pictureNumber != $pid) {
                $pictureNumbersSorted[] = $pictureNumber;
            }
        }

        $room->setPictures($pictureNumbersSorted);

        $roomManager->save($room);

        $this->flashMessenger()->addSuccessMessage('Picture has been made first');

        return $this->redirect()->toRoute('backend/room/edit', ['rid' => $rid]);
    }

    public function deletePictureAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $rid = $this->params()->fromRoute('rid');
        $pid = $this->params()->fromRoute('pid');

        $room = $roomManager->get($rid);

        if (! $room->hasPictureNumber($pid)) {
            throw new RuntimeException('This picture is not registered');
        }

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $pictureService = $serviceManager->get('Base\Service\PictureService');
            $pictureService->removePicture(sprintf('/room/%s/%s.jpg', $rid, $pid));

            $room->removePictureNumber($pid);
            $roomManager->save($room);

            $this->flashMessenger()->addSuccessMessage('Picture has been deleted');

            return $this->redirect()->toRoute('backend/room/edit', ['rid' => $rid]);
        }

        return array(
            'room' => $room,
            'pid' => $pid,
        );
    }

}