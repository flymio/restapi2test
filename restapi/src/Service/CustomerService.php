<?php

namespace App\Service;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;

class CustomerService extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->now = new \DateTime();
    }

    /**
     * @return object[]
     */
    public function listCustomers()
    {
        return $this->em->getRepository(Customer::class)->findAll();
    }

    /**
     * @param $id
     *
     * @throws \Exception
     *
     * @return Customer|bool
     */
    public function getOneCustomer($id)
    {
        try {
            /** @var Customer $customer */
            $customer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $id]);

            if ($customer instanceof Customer) {
                return $customer;
            }
        } catch (Exception $e) {
            throw new \Exception('invalid');
        }

        return false;
    }

    /**
     * @param Customer $customer
     *
     * @throws \Exception
     *
     * @return Customer
     */
    public function addCustomer(Customer $customer)
    {
        try {
            if ($customer instanceof Customer) {
                $customer->setUuid(Uuid::uuid4());
                $customer->setStatus('NEW');
                $this->em->persist($customer);
                $this->em->flush();
            }
        } catch (\Exception $e) {
            throw new \Exception('issue loading customer');
        }

        return $customer;
    }

    /**
     * @param Customer $customer
     *
     * @throws \Exception
     *
     * @return Customer
     */
    public function saveCustomer(Customer $customer)
    {
        try {
            if ($customer instanceof Customer) {
                $this->em->persist($customer);
                $this->em->flush();
            }
        } catch (\Exception $e) {
            throw new \Exception('issue save customer');
        }

        return $customer;
    }

    /**
     * @param $id
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function removeCustomer($id)
    {
        try {
            /** @var Customer $customer */
            $customer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $id]);
            if (null === $customer) {
                return false;
            }
            if ($customer instanceof Customer) {
                $this->em->remove($customer);
                $this->em->flush();
            }
        } catch (Exception $e) {
            throw new \Exception('invalid');
        }

        return true;
    }
}
