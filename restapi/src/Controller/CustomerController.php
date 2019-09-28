<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Exception\ApiException;
use App\Form\CustomerForm;
use App\Service\CustomerService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Customer controller.
 *
 * @Route("/api/customer", name="api_customer")
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @param CustomerService $customerService
     * @param Request         $request
     *
     * @return mixed
     * @Route("/list", name="getCustomers", methods={"GET"})
     */
    public function getCustomers(CustomerService $customerService, Request $request)
    {
        try {
            /** @var Customer $customers */
            $customers = $customerService->listCustomers();

            if (null === $customers || empty($customers)) {
                return $this->handleView($this->view('Customers do not exist', Response::HTTP_BAD_REQUEST));
            }
        } catch (ApiException $e) {
            throw new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, 'we have an error');
        }

        $view = $this->view($customers, Response::HTTP_OK);

        $view->getContext()->setGroups(['api']);

        return $this->handleView($view);
    }

    /**
     * @param CustomerService $customerService
     * @param $id
     *
     * @throws \Exception
     *
     * @return Response
     * @Route("/list/{id}", name="getCustomerById", methods={"GET"})
     */
    public function getCustomerById($id, CustomerService $customerService)
    {
        try {
            /** @var Customer $customers */
            $customers = $customerService->getOneCustomer($id);

            if (null === $customers || empty($customers)) {
                return $this->handleView($this->view('Customer does not exist', Response::HTTP_BAD_REQUEST));
            }
        } catch (ApiException $e) {
            throw new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, 'we have an error');
        }

        $view = $this->view($customers, Response::HTTP_OK);

        $view->getContext()->setGroups(['api']);

        return $this->handleView($view);
    }

    /**
     * @param CustomerService $customerService
     * @param Request         $request
     *
     * @throws \Exception
     *
     * @return Response
     * @Route("/new", name="newCustomer", methods={"POST"})
     */
    public function newCustomer(CustomerService $customerService, Request $request)
    {
        $ret = [];
        $customer = new Customer();

        try {
            $form = $this->createForm(CustomerForm::class, $customer);
            $data = $request->request->all();
            $form->submit($data);

            if (false === $form->isValid()) {
                throw new ApiException(
                JsonResponse::HTTP_BAD_REQUEST,
                $form->getErrors(true)
            );
            }

            $ret = $customerService->addCustomer($customer);
        } catch (ApiException $e) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, 'cannot save customer');
        }

        $view = $this->view($ret, Response::HTTP_OK);

        $view->getContext()->setGroups(['api']);

        return $this->handleView($view);
    }

    /**
     * @param CustomerService $customerService
     * @param $id
     *
     * @throws \Exception
     *
     * @return Response
     * @Route("/delete/{id}", methods={"GET"})
     */
    public function deleteCustomer($id, CustomerService $customerService)
    {
        try {
            $return = $customerService->removeCustomer($id);
            if (true === $return) {
                $code = Response::HTTP_OK;
                $message = 'User removed';
            } else {
                $code = Response::HTTP_BAD_REQUEST;
                $message = 'Unable to locate user';
            }
        } catch (ApiException $e) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, 'cannot remove customer');
        }

        $view = $this->view($message, $code);

        return $this->handleView($view);
    }

    /**
     * @param CustomerService $customerService
     * @param Request         $request
     *
     * @throws \Exception
     *
     * @return Response
     * @Route("/save/{id}", name="saveCustomer", methods={"POST"})
     */
    public function saveCustomer($id, CustomerService $customerService, Request $request)
    {
        $ret = [];
        /** @var Customer $customer */
        $customer = $customerService->getOneCustomer($id);
        try {
            $form = $this->createForm(CustomerForm::class, $customer);
            $data = $request->request->all();
            $form->submit($data);

            if (false === $form->isValid()) {
                throw new ApiException(
                    JsonResponse::HTTP_BAD_REQUEST,
                    $form->getErrors(true)
                );
            }

            $ret = $customerService->saveCustomer($customer);
        } catch (ApiException $e) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, 'cannot save customer');
        }

        $view = $this->view($ret, Response::HTTP_OK);

        $view->getContext()->setGroups(['api']);

        return $this->handleView($view);
    }
}
