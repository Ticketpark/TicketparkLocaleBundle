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
     * @dataProvider getDatesWithoutWeekday
     */
    public function testDateFilterWithoutWeekday(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateFilter($date, $locale, false));
    }

    public function getDatesWithoutWeekday()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), '27.05.2013, 20:00' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), '5/27/2013, 8:00 PM', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), '27/05/2013, 20:00', 'fr'),
        );
    }

    /**
     * @dataProvider getDatesWithoutTime
     */
    public function testDateFilterWithoutTime(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateFilter($date, $locale, true, false));
    }

    public function getDatesWithoutTime()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), 'Mo. 27.05.2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Mon 5/27/2013', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Lun. 27/05/2013', 'fr'),
        );
    }

    /**
     * @dataProvider getDatesWithoutWeekdayAndTime
     */
    public function testDateFilterWithoutWeekdayAndTime(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateFilter($date, $locale, false, false));
    }

    public function getDatesWithoutWeekdayAndTime()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), '27.05.2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), '5/27/2013', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), '27/05/2013', 'fr'),
        );
    }

    /**
     * @dataProvider getLongDates
     */
    public function testDateLongFilter(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateLongFilter($date, $locale));
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
     * @dataProvider getLongDatesWithoutWeekday
     */
    public function testDateLongFilterWithoutWeekday(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateLongFilter($date, $locale, false));
    }

    public function getLongDatesWithoutWeekday()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), '27. Mai 2013, 20:00' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), 'May 27, 2013, 8:00 PM', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), '27 mai 2013, 20:00', 'fr'),
        );
    }

    /**
     * @dataProvider getLongDatesWithoutTime
     */
    public function testDateLongFilterWithoutTime(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateLongFilter($date, $locale, true, false));
    }

    public function getLongDatesWithoutTime()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), 'Montag, 27. Mai 2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Monday, May 27, 2013', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), 'lundi, 27 mai 2013', 'fr'),
        );
    }

    /**
     * @dataProvider getLongDatesWithoutWeekdayAndTime
     */
    public function testDateLongFilterWithoutWeekdayAndTime(\DateTime $date, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->dateLongFilter($date, $locale, false, false));
    }

    public function getLongDatesWithoutWeekdayAndTime()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), '27. Mai 2013' , 'de'),
            array(new \DateTime('2013-05-27 20:00:00'), 'May 27, 2013', 'en'),
            array(new \DateTime('2013-05-27 20:00:00'), '27 mai 2013', 'fr'),
        );
    }

	/**
     * @dataProvider getTimezones
     */
    public function testConvertTimezoneFilter(\DateTime $date, $target, $origin, $expected)
    {
        $this->assertEquals($expected, $this->dateExtension->convertTimezoneFilter($date, $target, $origin)->format('Y-m-d H:i:s'));
    }

    public function getTimezones()
    {
        return array(
            array(new \DateTime('2013-05-27 20:00:00'), 'America/New_York', 'Europe/Zurich', '2013-05-27 14:00:00'),
            array(new \DateTime('2013-05-27 20:00:00'), 'Europe/Zurich', 'America/New_York', '2013-05-28 02:00:00'),
            array(new \DateTime('2016-02-28 00:00:00'), 'Asia/Singapore', 'Europe/Zurich', '2016-02-28 07:00:00'),
        );
    }

    /**
     * @dataProvider getTimespanFunctionDates
     */
    public function testTimespanFunction(\DateTime $date1, \DateTime $date2, $expected, $locale)
    {
        $this->assertEquals($expected, $this->dateExtension->timespan($date1, $date2, $locale));
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
        $this->assertEquals($expected, $this->dateExtension->timespanShort($date1, $date2, $locale));
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
        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
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
