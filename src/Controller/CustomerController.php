<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

use Psr\Log\LoggerInterface;

// src/Controller/CustomerController.php

// Add this use statement at the beginning of the file
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends AbstractController
{

   
   
    private $message = "Customers imported successfully!";
    private $customerRepository;
    private $logger;

    public function __construct(CustomerRepository $customerRepository, LoggerInterface $logger)
    {
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    public function index(CustomerRepository $customerRepository): Response
    {
        $customers = $customerRepository->findAll();

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }

    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customers' => $customer,
        ]);
    }

    public function import(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customers' => $customer,
        ]);
    }

    public function indexTest(CustomerRepository $customerRepository): JsonResponse
    {
        $customers = $customerRepository->findAll();
        $customerData = [];

        foreach ($customers as $customer) {
            $customerData[] = [
                'full_name' => $customer->getFirstName() . ' ' . $customer->getLastName(),
                'email' => $customer->getEmail(),
                'nationality' => $customer->getNationality(),
            ];
        }

        return new JsonResponse($customerData);
    }

    /**
     * @Route("/{customerId}", methods={"GET"})
     */
    public function showTest2(?int $customerId, CustomerRepository $customerRepository): JsonResponse
    {
      
        if ($customerId === null) {
            return new JsonResponse(['error' => 'Customer ID is required'], 400);
        }
    
        $customer = $customerRepository->find($customerId);
    
        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], 404);
        }
    
        $customerData = [
            'full_name' => $customer->getFirstName() . ' ' . $customer->getLastName(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'nat' => $customer->getNationality(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ];
    
        return new JsonResponse(['success' => 'Customer not found'], 404);
    }

    /**
     * @Route("/customer/{id}", name="customer_show", methods={"GET"})
     */
    public function showTest($id): JsonResponse
    {
        $this->logger->info('Fetching customer with ID: ' . $id);
       
        try {
          $customer = $this->customerRepository->find($id);
    
            if (!$customer) {
                $this->logger->info('Customer not found for ID: ' . $id);
                return new JsonResponse(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse($customer);
        } catch (\Exception $e) {
            $this->logger->error('An error occurred: ' . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
?>