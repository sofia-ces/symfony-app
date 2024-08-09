<?php 
namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $customer = new Customer();
        $customer->setName('Test Customer');
        $manager->persist($customer);
        $manager->flush();

        // Store the reference for use in tests
        $this->addReference('test-customer', $customer);
    }
}

?>