<?php

namespace App\Service;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

class CustomerService
{
    private $entityManager;
    private $apiUrl = 'https://randomuser.me/api';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importCustomers()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $this->apiUrl, [
            'query' => [
                'results' => 100,
                'nat' => 'au', // Filter for Australian nationality
            ],
        ]);

        $content = $response->toArray();
        $customers = $content['results'];

        foreach ($customers as $customerData) {
            $this->saveCustomer($customerData);
        }
    }

    private function saveCustomer(array $customerData)
    {
        $email = $customerData['email'];
        $existingCustomer = $this->entityManager->getRepository('App\Entity\Customer')->findOneBy(['email' => $email]);

        if ($existingCustomer) {
            // Update existing customer
            $existingCustomer->setFirstName($customerData['name']['first']);
            $existingCustomer->setLastName($customerData['name']['last']);
            $existingCustomer->setEmail($customerData['email']);
            $existingCustomer->setUsername(($customerData['login']['username']));
            $existingCustomer->setPassword(md5($customerData['login']['password']));
            $existingCustomer->setGender($customerData['gender']);
            $existingCustomer->setNationality($customerData['nat']);
            $existingCustomer->setCity($customerData['location']['city']);
            $existingCustomer->setPhone($customerData['phone']);

        } else {
            // Create new customer
            $customer = new Customer();
            $customer->setFirstName($customerData['name']['first']);
            $customer->setLastName($customerData['name']['last']);
            $customer->setEmail($customerData['email']);
            $customer->setUsername(($customerData['login']['username']));
            $customer->setPassword(md5($customerData['login']['password']));
            $customer->setGender($customerData['gender']);
            $customer->setNationality($customerData['nat']);
            $customer->setCity($customerData['location']['city']);
            $customer->setPhone($customerData['phone']);


            $this->entityManager->persist($customer);
        }

        $this->entityManager->flush();
    }
}
?>