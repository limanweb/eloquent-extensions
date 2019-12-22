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
    ], 
];