<?php

// config for Lartisan/ResourceGenerator
return [
    'database' => [
        'column_types' => [
            'bigIncrements' => 'Big Increments',
            'bigInteger' => 'Big Integer',
            'binary' => 'Binary',
            'boolean' => 'Boolean',
            'char' => 'Char',
            'dateTimeTz' => 'Date Time Tz',
            'dateTime' => 'Date Time',
            'date' => 'Date',
            'decimal' => 'Decimal',
            'double' => 'Double',
            //        'enum' => 'Enum', // todo: needs mandatory column $allowed
            'float' => 'Float',
            'foreignId' => 'Foreign Id',
            'foreignIdFor' => 'Foreign Id For',
            'foreignUlid' => 'Foreign Ulid',
            'foreignUuid' => 'Foreign Uuid',
            'geometryCollection' => 'Geometry Collection',
            'geometry' => 'Geometry',
            'id' => 'Id',
            'increments' => 'Increments',
            'integer' => 'Integer',
            'ipAddress' => 'Ip Address',
            'json' => 'Json',
            'jsonb' => 'Jsonb',
            'lineString' => 'Line String',
            'longText' => 'Long Text',
            'macAddress' => 'Mac Address',
            'mediumIncrements' => 'Medium Increments',
            'mediumInteger' => 'Medium Integer',
            'mediumText' => 'Medium Text',
            //        'morphs' => 'Morphs',
            'multiLineString' => 'Multi Line String',
            'multiPoint' => 'Multi Point',
            'multiPolygon' => 'Multi Polygon',
            //        'nullableMorphs' => 'Nullable Morphs',
            //        'nullableTimestamps' => 'Nullable Timestamps',
            //        'nullableUlidMorphs' => 'Nullable Ulid Morphs',
            //        'nullableUuidMorphs' => 'Nullable Uuid Morphs',
            'point' => 'Point',
            'polygon' => 'Polygon',
            'rememberToken' => 'Remember Token',
            //        'set' => 'Set',
            'smallIncrements' => 'Small Increments',
            'smallInteger' => 'Small Integer',
            'softDeletesTz' => 'Soft Deletes Tz',
            'softDeletes' => 'Soft Deletes',
            'string' => 'String',
            'text' => 'Text',
            'timeTz' => 'Time Tz',
            'time' => 'Time',
            'timestampTz' => 'Timestamp Tz',
            'timestamp' => 'Timestamp',
            'timestampsTz' => 'Timestamps Tz',
            'timestamps' => 'Timestamps',
            'tinyIncrements' => 'Tiny Increments',
            'tinyInteger' => 'Tiny Integer',
            'tinyText' => 'Tiny Text',
            'unsignedBigInteger' => 'Unsigned Big Integer',
            'unsignedDecimal' => 'Unsigned Decimal',
            'unsignedInteger' => 'Unsigned Integer',
            'unsignedMediumInteger' => 'Unsigned Medium Integer',
            'unsignedSmallInteger' => 'Unsigned Small Integer',
            'unsignedTinyInteger' => 'Unsigned Tiny Integer',
            //        'ulidMorphs' => 'Ulid Morphs',
            //        'uuidMorphs' => 'Uuid Morphs',
            'ulid' => 'Ulid',
            'uuid' => 'Uuid',
            'year' => 'Year',
        ],

        'columns_with_primary_key' => [
            'id',
            'bigIncrements',
            'increments',
            'mediumIncrements',
            'smallIncrements',
            'tinyIncrements',
        ],

        // Used to hide the column_name field
        'columns_with_no_params' => [
            'rememberToken',
            'nullableTimestamps',
            'timestampsTz',
            'timestamps',
        ],

        // Used to hide the modifiers
        'columns_with_return_void' => [
            'morphs',
            'nullableMorphs',
            'nullableTimestamps',
            'nullableUlidMorphs',
            'nullableUuidMorphs',
            'timestampsTz',
            'timestamps',
            'ulidMorphs',
            'uuidMorphs',
        ],

        // Columns that have one or more parameters
        'columns_with_default_values' => [
            'bigInteger' => [
                'autoIncrement' => false,
                'unsigned' => false,
            ],
            'char' => [
                'length' => null,
            ],
            'dateTimeTz' => [
                'precision' => 0,
            ],
            'dateTime' => [
                'precision' => 0,
            ],
            'decimal' => [
                'total' => 8,
                'places' => 2,
                'unsigned' => false,
            ],
            'double' => [
                'total' => null,
                'places' => null,
                'unsigned' => false,
            ],
            'float' => [
                'total' => 8,
                'places' => 2,
                'unsigned' => false,
            ],
            'foreignIdFor' => [
                'column' => null,
            ],
            'foreignUlid' => [
                'length' => 26,
            ],
            'integer' => [
                'autoIncrement' => false,
                'unsigned' => false,
            ],
            'mediumInteger' => [
                'autoIncrement' => false,
                'unsigned' => false,
            ],
            'point' => [
                'srid' => null,
            ],
            'smallInteger' => [
                'autoIncrement' => false,
                'unsigned' => false,
            ],
            'softDeletesTz' => [
                'precision' => 0,
            ],
            'softDeletes' => [
                'precision' => 0,
            ],
            'string' => [
                'length' => null,
            ],
            'timeTz' => [
                'precision' => 0,
            ],
            'time' => [
                'precision' => 0,
            ],
            'timestamp' => [
                'precision' => 0,
            ],
            'tinyInteger' => [
                'autoIncrement' => false,
                'unsigned' => false,
            ],
            'unsignedBigInteger' => [
                'autoIncrement' => false,
            ],
            'unsignedDecimal' => [
                'total' => 2,
                'places' => 2,
            ],
            'unsignedInteger' => [
                'autoIncrement' => false,
            ],
            'unsignedMediumInteger' => [
                'autoIncrement' => false,
            ],
            'unsignedSmallInteger' => [
                'autoIncrement' => false,
            ],
            'unsignedTinyInteger' => [
                'autoIncrement' => false,
            ],
            'ulidMorphs' => [
                'indexName' => null,
            ],
            'uuidMorphs' => [
                'indexName' => null,
            ],
            'ulid' => [
                'length' => 26,
            ],
        ],
    ],

    'model' => [
        'not_fillable_fields' => [
            'bigIncrements',
            'increments',
            'mediumIncrements',
            'smallIncrements',
            'tinyIncrements',
        ],
    ],

    'factory' => [
        'faker_types' => [
            // Faker\Provider\Base
            'randomDigit' => 'randomDigit',
            'randomDigitNotNull' => 'randomDigitNotNull',
            'randomNumber' => [
                'nbDigits' => null,
                'strict' => false,
            ],
            'randomFloat' => [
                'nbMaxDecimals' => null,
                'min' => 0,
                'max' => null,
            ],
            'numberBetween' => [
                'int1' => 0,
                'int2' => PHP_INT_MAX,
            ],
            'randomLetter' => 'randomLetter',

            // Faker\Provider\Lorem
            'word' => 'word',
            'words' => [
                'nb' => 3,
                'asText' => false,
            ],
            'sentence' => [
                'nbWords' => 6,
                'variableNbWords' => true,
            ],
            'sentences' => [
                'nb' => 3,
                'asText' => false,
            ],
            'paragraph' => [
                'nbSentences' => 3,
                'variableNbSentences' => true,
            ],
            'paragraphs' => [
                'nb' => 3,
                'asText' => false,
            ],
            'text' => [
                'maxNbChars' => 200,
            ],

            // Faker\Provider\en_US\Person
            'title' => [
                'gender' => null,
            ],
            'suffix' => 'suffix',
            'name' => [
                'gender' => null,
            ],
            'firstName' => [
                'gender' => null,
            ],
            'lastName' => 'lastName',

            // Faker\Provider\en_US\Address
            'state' => 'state',
            'city' => 'city',
            'address' => 'address',
            'postcode' => 'postcode',
            'country' => 'country',
            'latitude' => [
                'min' => -90,
                'max' => 90,
            ],
            'longitude' => [
                'min' => -90,
                'max' => 90,
            ],

            // Faker\Provider\en_US\PhoneNumber
            'phoneNumber' => 'phoneNumber',
            'e164PhoneNumber' => 'e164PhoneNumber',

            // Faker\Provider\en_US\Company
            'company' => 'company',
            'jobTitle' => 'jobTitle',

            // Faker\Provider\DateTime
            'unixTime' => [
                'max' => 'now',
            ],
            'dateTime' => [
                'max' => 'now',
                'timezone' => null,
            ],
            'date' => [
                'format' => 'Y-m-d',
                'max' => 'now',
            ],
            'time' => [
                'format' => 'H:i:s',
                'max' => 'now',
            ],
            'dateTimeBetween' => [
                'startDate' => '-30 years',
                'endDate' => 'now',
                'timezone' => null,
            ],
            'timezone' => 'timezone',

            // Faker\Provider\Internet
            'email' => 'email',
            'safeEmail' => 'safeEmail',
            'userName' => 'userName',
            'password' => 'password',
            'domainName' => 'domainName',
            'url' => 'url',
            'slug' => 'slug',
            'ipv4' => 'ipv4',
            'ipv6' => 'ipv6',
            'macAddress' => 'macAddress',

            // Faker\Provider\Image
            'imageUrl' => [
                'width' => 640,
                'height' => 480,
                'category' => null,
                'randomize' => true,
                'word' => null,
                'gray' => false,
                'format' => 'png',
            ],
            'image' => [
                'dir' => null,
                'width' => 640,
                'height' => 480,
                'category' => null,
                'fullPath' => false,
                'randomize' => true,
                'word' => null,
                'gray' => false,
            ],

            // Faker\Provider\Uuid
            'uuid' => 'uuid',

            // Faker\Provider\Miscellaneous
            'boolean' => [
                'chanceOfGettingTrue' => 50,
            ],
            'md5' => 'md5',
            'sha1' => 'sha1',
            'sha256' => 'sha256',
            'locale' => 'locale',
            'countryCode' => 'countryCode',
            'languageCode' => 'languageCode',
            'currencyCode' => 'currencyCode',
            'emoji' => 'emoji',
        ],
    ],

];
