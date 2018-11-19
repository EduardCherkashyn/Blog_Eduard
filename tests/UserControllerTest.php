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
            $crawler->filter('html:contains("Email")')->count()
        );
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Name")')->count()
        );
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Password")')->count()
        );

    }

//    public function testFormSubmission()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/registration');
//        $client->followRedirects();
//        $form = $crawler->selectButton('Submit')->form();
//        $form['registration[email]']->setValue( 'lllucas1@ukr.net');
//        $form['registration[name]']->setValue( 'Lucas');
//        $form['registration[plainpassword]']->setValue('1234567');
//        $crawler = $client->submit($form);
//        $this->assertTrue($client->getResponse()->isRedirect());
//    }

//    public function testLoginForm()
//    {
//       $client = static::createClient();
//        $crawler = $client->request('GET', '/login');
//        echo $client->getRequest()->getUri();
//
//        $form = $crawler->selectButton('Submit')->form(array(
//            'login[email]' => 'edikcherkashyn@ukr.net',
//            'login[password]' => '12345',
//        ),'POST');
//        $crawler = $client->submit($form);
//        $this->assertTrue($client->getResponse()->isRedirect());
//    }
}
