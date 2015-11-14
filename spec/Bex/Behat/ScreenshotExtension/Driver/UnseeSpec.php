<?php

namespace spec\Bex\Behat\ScreenshotExtension\Driver;

use Bex\Behat\ScreenshotExtension\Driver\Service\UnseeApi;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UnseeSpec extends ObjectBehavior
{
    function let(UnseeApi $api)
    {
        $this->beConstructedWith($api);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bex\Behat\ScreenshotExtension\Driver\Unsee');
    }

    function it_should_call_the_api_with_the_correct_data(ContainerBuilder $container, UnseeApi $api)
    {
        $api->call('imgdata', 'img_file_name.png', 600)->shouldBeCalled()->willReturn('imgurl');
        $this->load($container, ['expire' => '10m']);
        $this->upload('imgdata', 'img_file_name.png')->shouldReturn('imgurl');
    }
}