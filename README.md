# TicketparkLocaleBundle

This bundle adds functionalities to simplify the handling of localized strings

## Functionalities
* Country (TwigExtension)
    * Filters:
        * Convert country short codes to localized full country names
    
* Dates (Twig Extension)
    * Filters:
        * Display dates as localized strings
        * Convert timezones of DateTime objects in directly Twig
    * Functions:
        * Display timespans as localized strings

## Installation
Add TicketparkFileBundle in your composer.json:

```js
{
    "require": {
        "ticketpark/locale-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ticketpark/locale-bundle
```

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ticketpark\LocaleBundle\TicketparkLocaleBundle(),
    );
}
```
## Usage of Country functionalities

### Twig
``` php
{{ CH|fullCountry }} // outputs the localized string for "Switzerland" depending on the current locale
{{ CH|fullCountry('de') }} // outputs the localized string for "Switzerland" in German ("Schweiz"),
```
    
## Usage of Date functionalities

### Twig - date filters
``` php
// output localized string like "So. 09.03.2014, 21:03" in current locale
{{ dateTimeObject|tpDate }}

// outputs localized string in specific locale: " Sun 3/9/2014, 9:0e PM"
{{ dateTimeObject|tpDate('en') }} 

// output long localized string like "Sonntag, 9. März 2014, 21:03" in current locale
{{ dateTimeObject|tpDateLong }}

// outputs long localized string in specific locale: " Sunday, March 9, 2014, 9:03 PM"
{{ dateTimeObject|tpDateLong('en') }} 
```
### Twig - timezone filter
The timezone filter always returns a DateTime object which must be converted to a string with another filter.
``` php
// convert a datetime object from it's current timezone to a different one
{{ dateTimeObject|convertTimezone('America/New_York')|tpDate }}

// convert a datetime object from it's current timezone to your default timezone
{{ dateTimeObject|convertTimezone()|tpDate }}

// convert a datetime object by setting its current timezone first
// and the converting it to a different one.
// This may be useful if you have a localized datetime saved in an entity along
// with its timezone.
// Example: event.start|convertTimezone(user.timezone, event.venue.timezone)
{{ dateTimeObject|convertTimezone('America/New_York', 'Asia/Singapore')|tpDate }}
```

### Twig - timespan functions
``` php
// output localized string like "Sonntag, 9. März 2014 bis Donnerstag, 12. Juni 2014" in current locale
{{ timespan(dateTimeObject1, dateTimeObject2) }}

// output localized string in specific locale "Sunday, March 9, 2014 until Thursday, June 12, 2014" in current locale
{{ timespan(dateTimeObject1, dateTimeObject2, 'en') }}

// Correct order is made by extension: "Sunday, March 9, 2014 until Thursday, June 12, 2014"
{{ timespan(dateTimeObject2, dateTimeObject1, 'en') }}

// Twice the same day return only one date: "Sunday, March 9, 2014"
{{ timespan(dateTimeObject1, dateTimeObject1, 'en') }}

// Same procedures are available in short versions: "Mar 9, 2014 until Jun 12, 2014"
{{ timespanShort(dateTimeObject1, dateTimeObject2, 'en') }}
```



## License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
