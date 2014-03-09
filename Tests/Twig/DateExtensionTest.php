<?php

namespace Symfony\Component\Validator\Tests\Twig;

use Ticketpark\LocaleBundle\Twig\DateExtension;

class DateExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $dateExtension;

    protected function setUp()
    {
        $translatorMock = $this->getTranslatorMock();
        $this->dateExtension = new DateExtension($translatorMock);
    }

    /**
     * @dataProvider getDates
     */
    public function testDateFilter(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($this->dateExtension->dateFilter($date, $locale), $expected);
    }

    public function getDates()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), 'Mo. 27.05.2013, 20:00' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Mon 5/27/2013, 8:00 PM', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Lun. 27/05/2013, 20:00', 'fr'),
        );
    }

    /**
     * @dataProvider getLongDates
     */
    public function testDateLongFilter(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($this->dateExtension->dateLongFilter($date, $locale), $expected);
    }

    public function getLongDates()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), 'Montag, 27. Mai 2013, 20:00' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Monday, May 27, 2013, 8:00 PM', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), 'lundi, 27 mai 2013, 20:00', 'fr'),
        );
    }

    /**
     * @dataProvider getTimespanFunctionDates
     */
    public function testTimespanFunction(\DateTime $date1, \DateTime $date2, $expected, $locale)
    {
        $this->assertEquals($this->dateExtension->timespan($date1, $date2, $locale), $expected);
    }

    public function getTimespanFunctionDates()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-29 14:00:00'), 'Montag, 27. Mai 2013 - Mittwoch, 29. Mai 2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-27 22:00:00'), 'Montag, 27. Mai 2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-29 14:00:00'), 'Monday, May 27, 2013 - Wednesday, May 29, 2013' , 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-27 22:00:00'), 'Monday, May 27, 2013' , 'en'),
        );
    }

    /**
     * @dataProvider getTimespanShortFunctionDates
     */
    public function testTimespanShortFunction(\DateTime $date1, \DateTime $date2, $expected, $locale)
    {
        $this->assertEquals($this->dateExtension->timespanShort($date1, $date2, $locale), $expected);
    }

    public function getTimespanShortFunctionDates()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-29 14:00:00'), '27.05.2013 - 29.05.2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-27 22:00:00'), '27.05.2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-29 14:00:00'), 'May 27, 2013 - May 29, 2013' , 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), new \DateTime('2013-05-27 22:00:00'), 'May 27, 2013' , 'en'),
        );
    }

    public function getTranslatorMock()
    {
        $translator = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Translation\Translator')
            ->disableOriginalConstructor()
            ->setMethods(array('trans'))
            ->getMock();

        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnCallback(array($this, 'translatorTransCallback')));

        return $translator;
    }

    public function translatorTransCallback()
    {
        $args = func_get_args();

        //The hyphen is a placeholder for whatever text will be there in translations.
        return $args[1]['%earlier_date%'].' - '.$args[1]['%later_date%'];
    }
}
