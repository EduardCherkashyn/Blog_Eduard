<?php
/**
 * Created by PhpStorm.
 * User: madman
 * Date: 22/11/18
 * Time: 19:45
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegistration()
    {
        // TODO create database before test and remove it after test, better to extend WebTestCase and add this in setUp and TearDown methods
        $client = static::createClient();
        $crawler = $client->request('GET', '/registration');
        $crawler->selectButton('Submit');
        $form = $crawler->selectButton('Submit')->form();
        $form['registration[email]'] = time().'lllucas1@ukr.net';
        $form['registration[name]'] = 'Lucas';
        $form['registration[plainpassword]'] = '12345678';
        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertTrue(true);
    }
}