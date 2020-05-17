<?php 

use Limanweb\EloquentExt\Handlers\ComparingFilterOperationHandler;
use Limanweb\EloquentExt\Handlers\SetFilterOperationHandler;

return [
    'sorting' => [
        'values' => [
            'ASC' => 'ASC',
            'ASCENDING' => 'ASC',
            'DESC' => 'DESC',
            'DESCENDING' => 'DESC',
        ],
    ],
    'filters' => [
        'operations' => [
            'eq' => [
                'handler' => ComparingFilterOperationHandler::class,
            ],
            'not-eq' => [
                'handler' => ComparingFilterOperationHandler::class,
            ],
            'lt' => [
                'handler' => ComparingFilterOperationHandler::class,
            ],
            'le' => [
                'handler' => ComparingFilterOperationHandler::class,
            ],
            'gt' => [
                'handler' => ComparingFilterOperationHandler::class,
            ],
            'ge' => [
                'handler' => ComparingFilterOperationHandler::class,
            ],
            'in' => [
                'handler' => SetFilterOperationHandler::class,
            ],
            'not-in' => [
                'handler' => SetFilterOperationHandler::class,
            ],
        ],
        'operation_sets' => [
            'string' => ['eq', 'not-eq', 'in', 'not-in', 'like', 'not-like', 'has-string', 'not-has-string'],
            'number' => ['eq', 'not-eq', 'gt', 'ge', 'lt', 'le', 'in', 'not-in', 'null', 'not-null'],
            'identifier' => ['eq', 'not-eq'],
            'date' => ['eq', 'not-eq', 'gt', 'ge', 'lt', 'le', 'in', 'not-in', 'null', 'not-null'],
            'boolean' => ['eq', 'not-eq', 'null', 'not-null']
        ],
//         'casts' => [
//             'string' => [
//                 'int' => 'castIntVal',
//                 'integer' => 'castIntVal',
//                 'real' => 'castFloatVal',
//                 'float' => 'castFloatVal',
//                 'double' => 'castFloatVal',
//                 'bool' => 'castBoolVal',
//                 'boolean' => 'castBoolVal',
//             ],                 
//         ]
        'cast-aliases' => [
            'real' => 'float',
            'float' => 'float',
            'double' => 'float',
            'int' => 'int',
            'integer' => 'int',
        ],
        'validation' => [
            'int' => ['integer'],
            'float' => ['numeric'],
            'string' => ['string'],
            'date' => ['date'],
        ],
    ], 
];