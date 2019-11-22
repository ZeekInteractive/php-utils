# PHP Utils 

![](https://github.com/ZeekInteractive/php-utils/workflows/Codeception/badge.svg)

## Purpose
Utility functions to help alleviate some common use cases.

### Safe Read

```php
safe_read( $item, $key )
```

Useful for accessing something without having to wrap it with `empty` or `isset` checks constantly.

Returns `false` if it's unable to find the item. Works with both objects and arrays.

### Is Constant True

```php
is_constant_true( $constant )
```

Checks the following in order: 
* Constant is defined
* Constant is `true`

Helpful to avoid always having to check if the constant is defined before checking the value.

Returns either `true` or `false`.

### Get File

```php
get_file( $filename, $extension )
```

Checks that the file exists and is readable.

Allowable file types are:
* xml
* sql
* txt
* json

Returns the data of the file or `false` if it failed.

### Recursively Remove Directory

```php
rrmdir( $dir )
```

Recursively delete a directory and all files and directories inside it.

This is useful because PHP's `rmdir` does not delete a directory if anything exists inside of it.

### Format Currency

Formats the given value with two decimal places, prefixed with a dollar sign.

```php
format_currency( 1.5 )
// $1.50
```
