# command-watcher

# Installation

1. Run `composer require sowbiba/command-watcher-bundle`

2. Register the bundle in your `app/AppKernel.php`:

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
  
4. Make sure directory defined in `log_path` exists and is writable by the web user
5. The graphs are accessible via `/command-watch`
