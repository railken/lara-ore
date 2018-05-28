<?php

namespace Railken\LaraOre\Tests\Admin;

use Railken\Bag;
use Railken\LaraOre\Config\ConfigManager;

/**
 */
class ConfigsTest extends BaseTest
{
    use Traits\ApiTestCommonTrait;
    
    /**
     * Retrieve basic url.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return '/api/v1/admin/configs';
    }

    /**
     * Retrieve basic url.
     *
     * @return \Railken\Laravel\Manager\Contracts\ManagerContract
     */
    public function getManager()
    {
        return new ConfigManager();
    }

    /**
     * Retrieve correct bag of parameters.
     *
     * @return Bag
     */
    public function getParameters()
    {
        $bag = new bag();
        $bag->set('key', "This is a key");
        $bag->set('value', "This is a value");

        return $bag;
    }

    public function testSuccessCommon()
    {
        $this->commonTest($this->getBaseUrl(), $this->getParameters());
    }

    public function testWrongCreate()
    {
        $response = $this->post($this->getBaseUrl(), []);
        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                ['code' => 'CONFIG_KEY_NOT_DEFINED'],
                ['code' => 'CONFIG_VALUE_NOT_DEFINED'],
            ],
        ]);
    }

    public function testWrongName()
    {
        $response = $this->post($this->getBaseUrl(), $this->getParameters()->set('key', 'A name')->toArray());
        $response->assertStatus(200);

        $response = $this->post($this->getBaseUrl(), $this->getParameters()->set('key', 'A name')->toArray());
        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                ['code' => 'CONFIG_KEY_NOT_UNIQUE'],
            ],
        ]);
    }
}
