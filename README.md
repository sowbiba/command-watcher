# Command-watcher
This bundle is for supervising your symfony commands.

It uses the Symfony Stopwatch component to get duration and memory statistics.

When one of the commands registered in the config file starts, a stopwatch event is triggered and is stopped at the command end. Each command has its log file in the `log_path` where to put stats data `start;end;duration;memory;command parameters`.

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
    log_path: "%kernel.root_dir%/tmp"
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

5. Make sure directory defined in `log_path` exists and is writable by the web user
6. The graphs are accessible via `/command-watch`
