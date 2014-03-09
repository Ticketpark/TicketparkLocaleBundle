<?php

namespace Ticketpark\Bundle\BaseBundle\Tests\Twig;

use Ticketpark\LocaleBundle\Twig\CountryExtension;

class CountryExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFullCountryNameProvider
     */
    public function testGetFullCountryName($countryCode, $locale, $expected)
    {
        $localeExtension = new CountryExtension();
        $this->assertEquals($expected, $localeExtension->getFullCountryName($countryCode, $locale));
    }

    public function getFullCountryNameProvider()
    {
        return array(
            array('CH', 'en', 'Switzerland'),
            array('ch', 'de', 'Schweiz'),
            array('CH', 'fr', 'Suisse'),
            array('DE', 'en', 'Germany'),
            array('de', 'de', 'Deutschland'),
            array('DE', 'fr', 'Allemagne'),
            array('DE', null, 'Germany'),
            array(null, 'en', null),
        );
    }
}