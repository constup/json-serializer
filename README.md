# constup/json-serializer

## Note on stability

This package is for internal development and testing use only and might be unstable. 
It's not tested properly and new versions might be incompatible with the old ones.

## Description

JSON serializer for PHP objects which produces JSON object in a specific format. 
To learn more, take a look at the format specification.

## Prerequisites

- PHP 7.2.0+
    - `ext-json`

## Installation

```bash
composer require constup/json-serializer
```

## Features

- **All properties** of a class and all of its **parent** classes can be used in 
serialization;
- If your class has a parent classes or parent classes, you can choose to **serialize 
only the leaf class**. Useful, for example, for when you have an object filled with 
generic properties in abstract parent classes and you want to serialize only data in
the leaf class;
- **Includes fully qualified class names** of each property which is an object;
- If a property is an object and the class of that object is a part of a composer 
package, the serialized data will contain **composer package name** which holds the 
class.

## License

MIT License (See [LICENSE](LICENSE) for more details).

## Documentation