<?php

namespace App\DataFixtures;

use App\Doctrine\EnumStatusDefaultType;
use App\Doctrine\EnumStatusExtendedType;
use Ramsey\Uuid\Uuid;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DataFixtures
 * @package App\DataFixtures
 */
class DataFixtures extends Fixture
{

    private $em;
    private $now;

    const fill_data = [
        "first_names" => [
            'Ivan', 'Marc', 'Lucas', 'Antony', 'Theodor', 'Patrick', 'Joseph',
        ],
        "last_names" => [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Rodriguez', 'Wilson',
        ],
        "product_names" => [
            'Corolla', 'Star Wars', 'iPad', 'Mario Bros', 'iPhone', 'Harry Potter', 'Rubik\'s Cube',
        ],
        "counts" => [
            "customer" =>  10,
            "users" =>  10,
            "products" => 40,
        ],
        "tlds" => ["com", "net", "gov", "org", "edu", "biz", "info"],
        "char" =>  '0123456789abcdefghijklmnopqrstuvwxyz',
    ];

    /**
     * DataFixtures constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->now = new \DateTime();
    }

    /**
     * @throws \Exception
     */
    private function users()
    {
        for ($i = 1; $i <= self::fill_data['counts']['users']; $i++) {
            $this->addUser([]);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    private function getRandomString($data){
        return $data[mt_rand(0, (sizeof($data)-1))];
    }


    /**
     * @return string
     * @throws \Exception
     */

    private function tokenGenerate(){
        return hash('sha256', Uuid::uuid4());
    }

    /**
     * @return string
     */
    private function emailGenerate()
    {
        $ulen = mt_rand(5, 10);
        $dlen = mt_rand(7, 17);
        $email = "";

        for ($i = 1; $i <= $ulen; $i++) {
            $email .= substr(self::fill_data['char'], mt_rand(0, strlen(self::fill_data['char'])), 1);
        }
        $email .= "@";

        for ($i = 1; $i <= $dlen; $i++) {
            $email .= substr(self::fill_data['char'], mt_rand(0, strlen(self::fill_data['char'])), 1);
        }
        $email .= ".";
        $email .= $this->getRandomString(self::fill_data['tlds']);

        return $email;
    }
    
    /**
     * @return string
     */
    private function IssnGenerate(){
        $time = time();
        $random_str = rand(0,$time) + $time;
        return substr($random_str,0,4) . substr($random_str,-4);
    }

    /**
     * @return false|string
     */
    private function genBirthDay()
    {
        $year = rand(16, 60);
        $diff = rand(0, 30000000);
        $timestamp = strtotime("$year years ago");
        return date('Y-m-d', $timestamp - $diff);
    }


    /**
     * @param array $role
     * @throws \Exception
     */
    private function addUser(array $role)
    {
        $email = $this->emailGenerate();
        $token = $this->tokenGenerate();

        $user = new User();
        $user
            ->setEmail($email)
            ->setRoles($role)
            ->setApiToken($token);

        $this->em->persist($user);
    }

    /**
     * @throws \Exception
     */
    private function customers()
    {

        for ($i = 1; $i <= self::fill_data['counts']['users']; $i++) {

            $customer = new Customer();
            $customer
                ->setFirstName($this->getRandomString(self::fill_data['first_names']))
                ->setLastName($this->getRandomString(self::fill_data['last_names']))
                ->setDateOfBirth(new \DateTime($this->genBirthDay()))
                ->setCreatedAt($this->now)
                ->setUuid(Uuid::uuid4())
                ->setStatus('NEW');

            $this->em->persist($customer);
            $this->products($customer);
        }
        $this->em->flush();
    }

    /**
     * @param Customer $customer
     */
    private function products(Customer $customer)
    {
        for ($i = 1; $i <= self::fill_data['counts']['products']; $i++) {
            $number = rand(100, 999);
            $product = new Product();
            $product
                ->setName($this->getRandomString(self::fill_data['product_names']) . " - " . $number)
                ->setIssn($this->IssnGenerate())
                ->setCustomer($customer)
                ->setCreatedAt($this->now)
                ->setStatus('NEW');
            $this->em->persist($product);
        }
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        $this->users();
        $manager->flush();
        $this->customers();
        $manager->flush();
    }
}