<?php

namespace Backend\Controller;

use Product\Entity\Product;
use RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ProductController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $productManager = $serviceManager->get('Product\Manager\ProductManager');

        $products = $productManager->getAll('pid DESC');

        return array(
            'products' => $products,
        );
    }

    public function editAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $productManager = $serviceManager->get('Product\Manager\ProductManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $pid = $this->params()->fromRoute('pid');

        if ($pid) {
            $product = $productManager->get($pid);
        } else {
            $product = null;
        }

        $editProductForm = $formElementManager->get('Backend\Form\Product\EditProductForm');

        if ($this->getRequest()->isPost()) {
            $editProductForm->setData($this->params()->fromPost());

            if ($editProductForm->isValid()) {
                $epd = $editProductForm->getData();

                if (! $product) {
                    $product = new Product();
                    $product->set('status', 'enabled');
                }

                $product->setMeta('name', $epd['epf-name']);
                $product->setMeta('description', $epd['epf-description']);

                $productManager->save($product);

                $this->flashMessenger()->addSuccessMessage('Product has been saved');

                return $this->redirect()->toRoute('backend/product');
            }
        } else {
            if ($product) {
                $editProductForm->setData(array(
                    'epf-name' => $product->getMeta('name'),
                    'epf-description' => $product->getMeta('description'),
                ));
            }
        }

        return array(
            'editProductForm' => $editProductForm,
            'product' => $product,
        );
    }

    public function deleteAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleItemManager = $serviceManager->get('Bundle\Manager\BundleItemManager');
        $productManager = $serviceManager->get('Product\Manager\ProductManager');

        $pid = $this->params()->fromRoute('pid');

        $product = $productManager->get($pid);

        $bundleItems = $bundleItemManager->getBy(array('pid' => $pid));

        if ($bundleItems) {
            throw new RuntimeException('Product cannot be deleted while in use in a bundle');
        }

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $productManager->delete($product);

            $this->flashMessenger()->addSuccessMessage('Product has been deleted');

            return $this->redirect()->toRoute('backend/product');
        }

        return array(
            'product' => $product,
        );
    }

    public function interpretAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $productManager = $serviceManager->get('Product\Manager\ProductManager');

        $term = $this->params()->fromQuery('term');
        $id = $this->params()->fromQuery('id', 'true');

        $productsMax = 15;

        $products = $productManager->interpret($term, $productsMax);

        $productsList = array();

        foreach ($products as $pid => $product) {
            if ($id == 'true') {
                $productsList[] = sprintf('%s (%s)', $product->getMeta('name'), $pid);
            } else {
                $productsList[] = sprintf('%s', $product->getMeta('name'));
            }
        }

        if (count($productsList) == $productsMax) {
            $productsList[] = '[...]';
        }

        return $this->jsonViewModel($productsList);
    }

}