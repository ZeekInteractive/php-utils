# PHP Utils 

[![Build Status](https://travis-ci.org/ZeekInteractive/php-utils.svg?branch=master)](https://travis-ci.org/ZeekInteractive/php-utils)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/72110913193d45529694e58592f42e56)](https://www.codacy.com/app/ZeekInteractive/php-utils?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ZeekInteractive/php-utils&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/72110913193d45529694e58592f42e56)](https://www.codacy.com/app/ZeekInteractive/php-utils?utm_source=github.com&utm_medium=referral&utm_content=ZeekInteractive/php-utils&utm_campaign=Badge_Coverage)

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
