<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Modality extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/modalities';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->visit($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function fillInModalityForm(Browser $browser, $name, $abbreviation)
    {
        $browser->assertSee('Modalities')
                ->press('@add-parent')
                ->waitForText('Add Parent')
                ->pause(1000)
                ->type('@parent-modality-name', $name)
                ->type('@parent-modality-abbreviation', $abbreviation)
                ->press('@save-parent-modality')
                ->assertSee('Modalities');
    }
}
