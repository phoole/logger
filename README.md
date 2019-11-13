# logger
[![Build Status](https://travis-ci.com/phoole/logger.svg?branch=master)](https://travis-ci.com/phoole/logger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phoole/logger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phoole/logger/?branch=master)
[![Code Climate](https://codeclimate.com/github/phoole/logger/badges/gpa.svg)](https://codeclimate.com/github/phoole/logger)
[![PHP 7](https://img.shields.io/packagist/php-v/phoole/logger)](https://packagist.org/packages/phoole/logger)
[![Latest Stable Version](https://img.shields.io/github/v/release/phoole/logger)](https://packagist.org/packages/phoole/logger)
[![License](https://img.shields.io/github/license/phoole/logger)]()

[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"

Simple &amp; Slim [PSR-3][PSR-3] Logger for PHP. It helps to **'DELIVER CERTAIN MESSAGES TO SOMEWHERE'**

Installation
---
Install via the `composer` utility.

```
composer require "phoole/logger"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phoole/logger": "1.*"
    }
}
```

Usage
---

Create the logger instance with a channel id,

```php
use Psr\Log\LogLevel;
use Phoole\Logger\Logger;
use Phoole\Logger\Entry\MemoryInfo;
use Phoole\Logger\Handler\SyslogHandler;
use Phoole\Logger\Handler\TerminalHandler;

// with channel id
$logger = new Logger('MyApp');

// log every warning to syslog
$logger->addHandler(
    LogLevel::WARNING,
    new SyslogHandler()
);

// log to terminal for MemoryInfo entry
$logger->addHandler(
    LogLevel::INFO,
    new TerminalHandler(),
    MemoryInfo::class // handle this log object only
);

// log a text message
$logger->warning('a warning message');

// log memory usage
$logger->info(new MemoryInfo());
```

Concepts
---

- <a name="entry"></a>**Log entry**

  *A log entry* is a message in the form of an object. It solves the problem
  of **'WHAT TO BE SENT OUT'**. It has a message template, and some processors
  to process its context.
  
  For example, `Entry\MemoryInfo` is a predefined log entry with a message
  template of `{memory_used}M memory used , peak usage is {memory_peak}M`
  and one `Processor\MemoryProcessor` processor.
  
  ```php
  // with predefined template and processor
  $logger->warning(new MemoryInfo());
  
  // use new template
  $logger->warning(new MemoryInfo('Peak memory usage is {memory_peak}M'));
  ```
  
  `Entry\LogEntry` is the log entry prototype used whenever text message is
  to be logged
  
  ```php
  // using LogEntry
  $logger->info('test only');
  ```
  
  To define your own log entry,
  
  ```php
  use Phoole\Logger\Entry\LogEntry;
  
  class MyMessage extends LogEntry
  {
      // message template
      protected $message = 'your {template}';
  }
  
  // add handler
  $logger->addHandler(
      'warning', // level
      function(LogEntry $entry) { // a handler
          echo (string) $entry;
      },
      MyMessage::class // handle this type of message only
  );
  
  // output: 'your wow'
  $logger->error(new MyMessage(), ['template' => 'wow']);
  ```
  
- <a name="processor"></a>**Processor**

  *Processors* are associated with log entry classes. They solve the problem of
  **'WHAT EXTRA INFO TO SENT OUT'**. They will inject information into entries'
  context. Processors are `callable(LogEntryInterface $entry)`,
  
  ```php
  use Phoole\Logger\Processor\ProcessorAbstract;
  
  // closure
  $processor1 = function(LogEntry $entry) {
  };
  
  // invokable object
  $processor2 = new class() {
      public function __invoke(LogEntry $entry)
      {
      }
  }
  
  // extends
  class Processor3 extends ProcessorAbstract
  {
      protected function updateContext(array $context): array
      {
          $context['bingo'] = 'wow';
          return $context;
      }
  } 
  ```
  
  Processors are attached to log entries either in the entry class definition
  as follows,
  
  ```php
  class MyMessage extends LogEntry
  {
      // message template
      protected $message = 'your {template}';
        
      // define processors for this class
      protected static function classProcessors(): array
      {
          return [
              function(LogEntry $entry) {
                  $context = $entry->getContext();
                  $context['template'] = 'wow';
                  $entry->setContext($context);
              },
              new myProcessor(),
          ];
      }
  }
  ```
  
  or during the handler attachment
  
  ```php
  use Phoole\Logger\Handler\SyslogHandler;
  
  // will also add 'Processor1' and 'Processor2' to 'MyMessage' class
  $logger->addHandler(
      'info',
      new SyslogHandler(),
      MyMessage::addProcessor(
          new Processor1(),
          new Processor2(),
          ...
      )
  );
  ```
  
- <a name="handler"></a>**Handler**

  *Handlers* solve the problem of **'WHERE TO SEND MESSAGE'**. They take a
  log entry object and send it to somewhere.
  
  Handlers takes the form of `callable(LogEntryInterface $entry)` as follows,
  
  ```php
  use Phoole\Logger\Handler\HandlerAbstract;
  
  $handler1 = function(LogEntry $entry) {
      echo (string) $entry;
  }
  
  $handler2 = new class() {
      public function __invoke(LogEntry $entry)
      {
      }
  }
  
  class Handler3 extends HandlerAbstract
  {
      protected function write(LogEntryInterface $entry)
      {
          echo $this->>getFormatter()->format($entry);
      }
  }
  ```
  
  Handlers are added to the `$logger` with specific log level and type of
  log message they are going to handle (default is `LogEntryInterface`).
  
  ```php
  $logger->addHandler(
      LogLevel::WARNING,
      new TerminalHandler(),
      LogEntryInterface::class // this is the default anyway
  );
  ```
  
- <a name="formatter"></a>**Formatter**

  *Formatters* solve the problem of **'HOW MESSAGE WILL BE PRESENTED''**.
  Each handler of the type `Handler\HandlerAbstract` may have formatter
  specified during its initiation.
  
  ```php
  use Phoole\Logger\Handler\TerminalHandler;
  use Phoole\Logger\Formatter\AnsiFormatter;
  
  // use ANSI Color formatter
  $handler = new TerminalHandler(new AnsiFormatter());
  
  // add handler handles 'ConsoleMessage' ONLY
  $logger->addHandler('debug', $handler, ConsoleMessage::class);
  
  // log to console
  $logger->info(new ConsoleMessage('exited with error.'));
  
  // this will goes handlers handling 'LogEntry'
  $logger->info('exited with error');
  ```
  
APIs
---

- <a name="loggerInterface"></a>`LoggerInterface` related

  See [PSR-3][PSR-3] for standard related APIs.

- <a name="logger"></a>`Phoole\Logger\Logger` related

  - `__construct(string $channel)`

    Create the logger with a channel id.

  - `addHandler(string $level, callable $handler, string $entryClass, int $priority = 50): $this`

    Add one handler to specified channel with the priority.

- <a name="entry"></a>`Phoole\Logger\Entry\LogEntry` related

  - `static function addProcessor(callable ...$callables): string`
  
    This method will returns called class name.
  
Testing
---

```bash
$ composer test
```

Dependencies
---

- PHP >= 7.2.0

- phoole/base 1.*

License
---

- [Apache 2.0](https://www.apache.org/licenses/LICENSE-2.0)