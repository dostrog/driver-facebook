<?php

namespace Tests\Extensions;

use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ListTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

class ListTemplateTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $template = new ListTemplate;
        $this->assertInstanceOf(ListTemplate::class, $template);
    }

    /**
     * @test
     **/
    public function it_can_add_an_element()
    {
        $template = new ListTemplate;
        $template->addElement(Element::create('BotMan Documentation'));

        $this->assertSame('BotMan Documentation',
            Arr::get($template->toArray(), 'attachment.payload.elements.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_multiple_elements()
    {
        $template = new ListTemplate;
        $template->addElements([Element::create('BotMan Documentation'), Element::create('BotMan Laravel Starter')]);

        $this->assertSame('BotMan Documentation',
            Arr::get($template->toArray(), 'attachment.payload.elements.0.title'));
        $this->assertSame('BotMan Laravel Starter',
            Arr::get($template->toArray(), 'attachment.payload.elements.1.title'));
    }

    /**
     * @test
     **/
    public function it_can_add_a_global_list_button()
    {
        $template = new ListTemplate;
        $template->addGlobalButton(ElementButton::create('more'));

        $this->assertSame('more', Arr::get($template->toArray(), 'attachment.payload.buttons.0.title'));
    }

    /**
     * @test
     **/
    public function it_can_use_compact_list_view()
    {
        $template = new ListTemplate;
        $template->useCompactView();

        $this->assertSame('compact', Arr::get($template->toArray(), 'attachment.payload.top_element_style'));
    }
}
