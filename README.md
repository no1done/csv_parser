# Street Group Tech Test

## Running the application

I have provided a basic docker configuration, but if you choose to run locally then PHP 8.3 is required. 
Run a composer install for twig templating package and dev dependencies.

Run with:

```
composer serve
```

And access the form via `http://localhost:8080`.

## Overview

Built in vanilla PHP rather than bootstrapping an entire framework for a small solution. 
There is a basic file input in the twig template file `views/form.html`. In order to keep
within the time limit, this form is not secured, validated or restricted in any way. But simply 
a means to initiate the parsing with different files if you so choose, and an easy output to
see the results.

### Directory Structure

```
Street
| - docs
    |- coverage/*               // Directory containing code coverage report in HTML
    |- readme-4-.md             // Specification for test
| - public
    |- index.php                // Entry point of application
| - src
    |- Form.php                 // Main entry class for processing file
    |- Person.php               // OOP Representation of a single person
    |- Row.php                  // OOP Representation of a single row in file
| - tests
    |- data/*                   // Directory container test csv files
    |- FormTest.php             // Tests for Form.php
    |- PersonTest.php           // Tests for Person.php
    |- RowTest.php              // Tests for Row.php
| - tools/*                     // Location install for PHP cs-fixer IDE helper
| - views
    |- form.html                // Twig template form served on index.php
```

### Tool Stack

For this test I used:

+ PHPStorm IDE
+ PHP Code Sniffer / Code Beautifier
+ PHPUnit
+ Twig

I use the CS fixers as standard to keep code compliant with PSR guidelines.