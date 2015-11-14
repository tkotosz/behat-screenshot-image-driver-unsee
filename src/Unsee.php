<?php

namespace Bex\Behat\ScreenshotExtension\Driver;

use Bex\Behat\ScreenshotExtension\Driver\ImageDriverInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Bex\Behat\ScreenshotExtension\Driver\Service\UnseeApi;

class Unsee implements ImageDriverInterface
{
    const CONFIG_PARAM_EXPIRE = 'expire';

    /**
     * @var array
     */
    private $expireMapping = ['10m' => 600, '30m' => 1800, '1h' => 3600];

    /**
     * @var UnseeApi
     */
    private $api;

    /**
     * @var int
     */
    private $expire;

    /**
     * @param UnseeApi $api
     */
    public function __construct(UnseeApi $api = null)
    {
        $this->api = $api ?: new UnseeApi();
    }

    /**
     * @param  ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->enumNode(self::CONFIG_PARAM_EXPIRE)
                    ->values(array('10m', '30m', '1h'))
                    ->defaultValue('10m')
                ->end()
            ->end();
    }

    /**
     * @param  ContainerBuilder $container
     * @param  array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->expire = $this->convertExpireValue($config[self::CONFIG_PARAM_EXPIRE]);
    }

    /**
     * @param string $binaryImage
     * @param string $filename
     *
     * @return string URL to the image
     */
    public function upload($binaryImage, $filename)
    {
        return $this->api->call($binaryImage, $filename, $this->expire);
    }

    /**
     * @param  string $expire
     *
     * @return int
     */
    private function convertExpireValue($expire)
    {
        return $this->expireMapping[$expire];
    }
}