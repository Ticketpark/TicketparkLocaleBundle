<?php

namespace Ticketpark\LocaleBundle\Twig;

use Symfony\Component\Intl\Intl;

class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('fullCountry', array($this, 'getFullCountryName')),
        );
    }

    public function getFullCountryName($countryCode, $locale = null)
    {
        return  Intl::getRegionBundle()->getCountryName(strtoupper($countryCode), $locale);
    }

    public function getName()
    {
        return 'ticketpark_locale_extension';
    }
}