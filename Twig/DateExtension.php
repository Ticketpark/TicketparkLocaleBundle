<?php

namespace Ticketpark\LocaleBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class DateExtension extends \Twig_Extension
{
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tpDate', array($this, 'dateFilter')),
            new \Twig_SimpleFilter('tpDateLong', array($this, 'dateLongFilter')),
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
    public function dateFilter($date, $locale=null)
    {
        if (null === $locale) {
            $locale = \Locale::getDefault();
        }

        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
        $pattern = $formatter->getPattern();

        //add comma before hours
        $pattern = preg_replace('/ (h)/i', ', ${1}', $pattern);

        //display 4-digit years
        $pattern = preg_replace('/(y{1,})/', 'yyyy', $pattern);

        //prepend pattern with weekday
        $pattern = 'E '.$pattern;

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
    public function dateLongFilter($date, $locale=null)
    {
        if (null === $locale) {
            $locale = \Locale::getDefault();
        }

        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, \IntlDateFormatter::SHORT);
        $pattern = $formatter->getPattern();

        //add comma before hours
        $pattern = preg_replace('/ (h)/i', ', ${1}', $pattern);

        //prepend pattern with weekday
        $pattern = 'EEEE, '.$pattern;

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

    public function getName()
    {
        return 'ticketpark_date_extension';
    }
}