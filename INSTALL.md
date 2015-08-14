# INSTALLATION

## Summary 
iTunes feed package for the LaSalleCast e-broadcasting platform.  


## composer.json:

```
{
    "require": {
        "lasallecast/lasallecastitunes": "1.*",
    }
}
```


## Service Provider

In config/app.php:
```
Lasallecast\Lasallecastapi\LasallecastiTunesServiceProvider::class,
```


## Facade Alias

* none


## Dependencies
* none


## Publish the Package's Config

With Artisan:
```
php artisan vendor:publish
```

## Migration

n/a

## Notes

none


## Serious Caveat 

This package is designed to run specifically with my LaSalleCast packages.