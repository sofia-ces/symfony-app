<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CustomerControllerTest extends WebTestCase
{

    private $entityManager;

   
    
    public function testIndex(): void
    {

        $client = static::createClient();

        $customerRepository = $this->createMock(\App\Repository\CustomerRepository::class);
        $client->getContainer()->set('doctrine.orm.entity_manager', $this->createMock(\Doctrine\ORM\EntityManager::class));
        $client->getContainer()->set('App\Repository\CustomerRepository', $customerRepository);


        $customer2 = new \App\Entity\Customer();
        $customer2->setFirstName('Jane');
        $customer2->setLastName('Smith');
        $customer2->setEmail('jane.smith@example.com');
        $customer2->setNationality('Canada');
        $customer2->setGender('female');
        $customer2->setCity('Queanbeyan');
        $customer2->setPhone('02-2322-9102');

        $customer1 = new \App\Entity\Customer();
        $customer1->setFirstName('Dolores');
        $customer1->setLastName('Thomas');
        $customer1->setEmail('dolores.thomas@example.com');
        $customer1->setUsername('goldengorilla897');
        $customer1->setGender('female');
        $customer1->setNationality('AU');
        $customer1->setCity('Queanbeyan');
        $customer1->setPhone('02-2322-9102');



        $customerRepository->method('findAll')->willReturn([$customer1, $customer2]);

        $client->request('GET', '/customerstest');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

       $responseData = json_decode($client->getResponse()->getContent(), true);
       $this->assertCount(2, $responseData);
        $this->assertArrayHasKey('full_name', $responseData[0]);
        $this->assertArrayHasKey('email', $responseData[0]);
        $this->assertArrayHasKey('nationality', $responseData[0]);
    }


    public function testShow(): void
    {
        $client = static::createClient();
        // Mock the CustomerRepository
        $customerRepository = $this->createMock(\App\Repository\CustomerRepository::class);
        $client->getContainer()->set('doctrine.orm.entity_manager', $this->createMock(\Doctrine\ORM\EntityManager::class));
        $client->getContainer()->set('App\Repository\CustomerRepository', $customerRepository);

          // Create a new customer entity and persist it to the database
            $customer = new \App\Entity\Customer();
            $customer->setFirstname('Dolores');
            $customer->setLastName('Thomas');
            $customer->setEmail('dolores.thomas@example.com3');
            $customer->setUsername('goldengorilla897');
            $customer->setPassword('12345');
            $customer->setGender('female');
            $customer->setNationality('AU');
            $customer->setCity('Queanbeyan');
            $customer->setPhone('02-2322-9102');

           $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
           $this->entityManager->persist($customer);
           $this->entityManager->flush();
  
          $client->request('GET', "/customershowtest/" . '1');
  
          $response = $client->getResponse();
          $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
  
          $content = $response->getContent();
          $this->assertJson($content);
  
          $data = json_decode($content, true);
          $this->assertArrayHasKey('id', $data);


    }

    public function testShowNotFound(): void
    {
        $client = static::createClient();
    
        // Mock the CustomerRepository
        $customerRepository = $this->createMock(\App\Repository\CustomerRepository::class);
        $client->getContainer()->set('doctrine.orm.entity_manager', $this->createMock(\Doctrine\ORM\EntityManager::class));
        $client->getContainer()->set('App\Repository\CustomerRepository', $customerRepository);
    
        $customerRepository->method('find')->willReturn(null);

       
    
        $client->request('GET', '/customershowtest/1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    
        // Ensure the response content is not empty
        $this->assertNotEmpty($client->getResponse()->getContent());
    
        // Handle JSON decoding and error handling
        $responseData = json_decode($client->getResponse()->getContent(), true);

       
      
        if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->fail('Invalid JSON response: ' . json_last_error_msg());
        }
    
        // Assert the structure of the response JSON
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Customer not found', $responseData['error']);
    }
}