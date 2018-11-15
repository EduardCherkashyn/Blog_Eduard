<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/12/18
 * Time: 5:46 PM
 */

namespace App\Tests\Appbundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{

    public function testRegistrationPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/registration');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Email")')->count());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Name")')->count());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Password")')->count());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Repeat Password")')->count());
    }

    public function testFormSubmission()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/registration');
        $crawler->selectButton('Submit');
        $form = $crawler->selectButton('Submit')->form();
        $form['registration[email]'] = 'lllucas1@ukr.net';
        $form['registration[name]'] = 'Lucas';
        $form['registration[password][first]'] = '12345';
        $form['registration[password][second]'] = '12345';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());


    }

//    public function testLoginForm()
//    {
//       $client = static::createClient();
//        $crawler = $client->request('GET', '/login');
//        echo $client->getRequest()->getUri();
//
//        $form = $crawler->selectButton('Submit')->form(array(
//            'email' => 'edikcherkashyn@ukr.net',
//            'password' => '12345',
//        ),'POST');
//        $crawler = $client->submit($form);
//        $this->assertTrue($client->getResponse()->isRedirect());
//    }

}