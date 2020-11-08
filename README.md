# Backporting Laravel 7+ Blade components to Laravel 5.8+

This package backports support for Laravel 7+'s Blade components on older Laravel version. This package has been tested on Laravel 5.8 & 6.
Older Laravel versions might also work if we lower the `illuminate` dependencies in the `composer.json` file. I have not tested any versions prior to 5.8 because I personally have no need for it but I'm open to any PR 👌.

This package does not attempt to do more nor less than what Laravel 8+ currently provides.


## Installation

Install the package via composer:

``` bash
composer require vicgutt/laravel-components-backport
```

This package automatically registers it's provider and will act as your app's new `ViewServiceProvider`. Therefore you should comment out the default one in your `config/app.php`:

``` php
// config/app.php
'providers' => [
    /*
    * Laravel Framework Service Providers...
    */
    // ...
    // Comment out or delete this line.
    Illuminate\View\ViewServiceProvider::class,

    /*
    * Package Service Providers...
    */
    // Optionally you may add The package's Service Provider manually.
    VicGutt\ComponentBackport\Providers\ComponentBackportServiceProvider::class,
],
```


## Documentation

For more details on how to use Laravel 7+'s components, head to the [official Laravel docs](https://laravel.com/docs/8.x/blade#components).


## What was the changes required to make this work ?

Not much, suprisingly. This package is mostly a fork of [illuminate/view](https://github.com/illuminate/view) _(v8.13.0 at this point)_ with a few tweaks:

### PhpEngine.php

```diff
/**
 * Get the evaluated contents of the view at the given path.
 *
 * @param  string  $path
 * @param  array  $data
 * @return string
 */
protected function evaluatePath($path, $data)
{
    $obLevel = ob_get_level();

    ob_start();

    // We'll evaluate the contents of the view inside a try/catch block so we can
    // flush out any stray output that might get out before an error occurs or
    // an exception is thrown. This prevents any partial views from leaking.
    try {
+        extract($data, EXTR_SKIP);
+
+        require $path;
-        $this->files->getRequire($path, $data);
    } catch (Throwable $e) {
        $this->handleViewException($e, $obLevel);
    }

    return ltrim(ob_get_clean());
}
```

### InvokableComponentVariable.php & DeferringDisplayableValue.php

```diff
use ArrayIterator;
use Closure;
- use Illuminate\Contracts\Support\DeferringDisplayableValue;
+ use VicGutt\ComponentBackport\Contracts\DeferringDisplayableValue;
use Illuminate\Support\Enumerable;
use IteratorAggregate;

class InvokableComponentVariable implements DeferringDisplayableValue, IteratorAggregate {}
```

```diff
+ <?php
+ 
+ namespace VicGutt\ComponentBackport\Contracts;
+ 
+ interface DeferringDisplayableValue
+ {
+     /**
+      * Resolve the displayable value that the class is deferring.
+      *
+      * @return \Illuminate\Contracts\Support\Htmlable|string
+      */
+     public function resolveDisplayableValue();
+ }
```