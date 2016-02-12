# Command-watcher
This bundle is for supervising your symfony commands.

It uses the Symfony Stopwatch component to get duration and memory statistics.

When one of the commands registered in the config file starts, a stopwatch event is triggered and is stopped at the command end.
Data collected are written with the service `log_writer` defined in the configuration.
Each command has its log entry with `start;end;duration;memory;command parameters`.

You can have a graph of duration/memory statistics calling `/command-watch` url.

For the graph, we use the external bundle `Ob\HighchartsBundle`

# Installation

1. Run `composer require sowbiba/command-watcher-bundle`

2. Register the bundles in your `app/AppKernel.php`
Ob\HighchartsBundle is required for the graphs

   ``` php
    <?php
    ...
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Sowbiba\CommandWatcherBundle\SowbibaCommandWatcherBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            ...
        );
    ...
   ```
3. Update `config.yml`, adding
  ```
  sowbiba_command_watcher:
    commands:
      - 'app:my-test'
      - 'cache:clear'
      - 'assets:install'
    log_writer: @my_log_writer
    log_reader: @my_log_reader
    ```
  If you want to prefix log files, add
  ```
    log_prefix: "my-prefix_"
  ```
  
4. Import the application routing in `app/config/routing.yml`
```
SowbibaCommandWatcher:
    resource: "@SowbibaCommandWatcherBundle/Resources/config/routing.yml"
```

5. The graphs are accessible via `/command-watch`


# Examples of service configuration for available Reader/Writer

__ File __

```
    sowbiba_command_watcher.log_reader:
        class: Sowbiba\CommandWatcherBundle\Reader\FileReader
        arguments:
            - %sowbiba_command_watcher.commands%
            - "app/tmp"
            - ""

    sowbiba_command_watcher.log_writer:
        class: Sowbiba\CommandWatcherBundle\Writer\FileWriter
        arguments:
            - "app/tmp"
            - ""
```

__ Mongo __

```
    sowbiba_command_watcher.log_reader:
        class: Sowbiba\CommandWatcherBundle\Reader\MongoReader
        arguments:
            - "%my_mongo_dsn%"
            - "%my_mongo_database%"
            - %sowbiba_command_watcher.commands%

    sowbiba_command_watcher.log_writer:
        class: Sowbiba\CommandWatcherBundle\Writer\MongoWriter
        arguments:
            - "%my_mongo_dsn%"
            - "%my_mongo_database%"
```
