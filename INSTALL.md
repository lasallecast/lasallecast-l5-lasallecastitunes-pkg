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
Lasallecast\Lasallecastitunes\LasallecastitunesServiceProvider::class,
```


## Facade Alias

In config/app.php:
```
'Lasallecastitunes'       => Lasallecast\Lasallecastitunes\Facades\Lasallecastitunes::class,
```


## Dependencies

LaSalleCast, LaSalleCRM, LaSalleCMS


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