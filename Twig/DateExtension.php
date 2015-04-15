<?php

namespace Ticketpark\LocaleBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

class DateExtension extends \Twig_Extension
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tpDate', array($this, 'dateFilter')),
            new \Twig_SimpleFilter('tpDateLong', array($this, 'dateLongFilter')),
            new \Twig_SimpleFilter('convertTimezone', array($this, 'convertTimezoneFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('timespan', array($this, 'timespan')),
            new \Twig_SimpleFunction('timespanShort', array($this, 'timespanShort')),
        );
    }

    /**
     * Format date
     *
     * For more formatting options
     * see http://userguide.icu-project.org/formatparse/datetime
     *
     * @param \DateTime $date
     * @param string    $locale
     * @return string
     */
    public function dateFilter($date, $locale=null, $addWeekday=true, $addTime = true)
    {
        if (null === $locale) {
            $locale = \Locale::getDefault();
        }

        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }

        if ($addTime) {
            $time = \IntlDateFormatter::SHORT;
        } else {
            $time = \IntlDateFormatter::NONE;
        }

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::SHORT, $time);
        $pattern = $formatter->getPattern();

        //add comma before hours
        $pattern = preg_replace('/ (h)/i', ', ${1}', $pattern);

        //remove any double commas
        $pattern = str_replace(',,', '', $pattern);

        //display 4-digit years
        $pattern = preg_replace('/(y{1,})/', 'yyyy', $pattern);

        //prepend pattern with weekday
        if ($addWeekday) {
            $pattern = 'E '.$pattern;
        }


        $formatter->setPattern($pattern);
        $result = $formatter->format($date);

        //Weekday always with a capital letter
        $result = ucfirst($result);

        return $result;
    }

    /**
     * Format date
     *
     * For more formatting options
     * see http://userguide.icu-project.org/formatparse/datetime
     *
     * @param \DateTime $date
     * @param string    $locale
     * @return string
     */
    public function dateLongFilter($date, $locale=null, $addWeekday=true, $addTime=true)
    {
        if (null === $locale) {
            $locale = \Locale::getDefault();
        }

        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }

        if ($addTime) {
            $time = \IntlDateFormatter::SHORT;
        } else {
            $time = \IntlDateFormatter::NONE;
        }

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, $time);
        $pattern = $formatter->getPattern();

        //add comma before hours
        $pattern = preg_replace('/ (h)/i', ', ${1}', $pattern);

        //prepend pattern with weekday
        if ($addWeekday) {
            $pattern = 'EEEE, '.$pattern;
        }

        $formatter->setPattern($pattern);
        $result = $formatter->format($date);

        return $result;
    }

    /**
     * Formats a timespan to a string
     *
     * For more formatting options
     * see http://userguide.icu-project.org/formatparse/datetime
     *
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @return string
     */
    public function timespan(\DateTime $date1, \DateTime $date2, $locale=null, $short=false)
    {
        if (null === $locale) {
            $locale = \Locale::getDefault();
        }

        //Which date is earlier in time?
        $earlier = $date1;
        $later   = $date2;
        if($date1 > $date2){
            $earlier = $date2;
            $later   = $date1;
        }

        $dateFormat = \IntlDateFormatter::FULL;

        if ($short) {
            $dateFormat = \IntlDateFormatter::MEDIUM;
        }

        $formatter = new \IntlDateFormatter($locale, $dateFormat, \IntlDateFormatter::NONE);

        //Same day?
        $diff = $earlier->diff($later);
        if (0 === $diff->d) {

            return $formatter->format($earlier);
        }

        $string = $this->translator->trans('dates.timespan', array(
            '%earlier_date%'  => $formatter->format($earlier),
            '%later_date%'    => $formatter->format($later)
        ),
        'TicketparkLocaleBundle',
        $locale);

        return $string;
    }

    /**
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @param $locale
     * @return string
     */
    public function timespanShort(\DateTime $date1, \DateTime $date2, $locale=null)
    {
        return $this->timespan($date1, $date2, $locale, true);
    }
    
	/**
     * Convert datetime between timezones
     *
     * @param \DateTime $dateTime
     * @param string|null $targetTimezone
     * @param string|null $originTimezone
     *
     * @return \DateTime
     */
    public function convertTimezoneFilter(\DateTime $dateTime, $targetTimezone=null, $originTimezone=null)
    {
        if (null !== $originTimezone) {
           $dateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'), new \DateTimeZone($originTimezone));
        }
 
        if (null !== $targetTimezone) {
            $dateTime->setTimezone(new \DateTimeZone($targetTimezone));
        } else {
            $dateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'), new \DateTimeZone(date_default_timezone_get()));
        }
 
        return $dateTime;
    }

    public function getName()
    {
        return 'ticketpark_date_extension';
    }
}