<?php

namespace Limanweb\EloquentExt\Exceptions;

use Exception;

class BuilderConfiguratorException extends Exception
{
    
    public $status = \Illuminate\Http\Response::HTTP_BAD_REQUEST;
    
    protected $errors = [];
    
    /**
     *
     * @param string $message
     * @param string|array|null $errorDetails
     * @param string|null $logLevel
     */
    public function __construct(string $message, $errors)
    {
        parent::__construct(trans($message));
        
        $this->addErrors($errors);
        
    }
    
    public function render($request) 
    {
        $content = [
            'message' => $this->getMessage(),
            'details' => $this->errors()
        ];
        
        return response($content, $this->status);
    }
    
    /**
     * Set HTTP-status
     *
     * @param int $status
     * @return \App\Exceptions\CustomBusinessLogicException
     */
    public function status(int $status)
    {
        $this->status = $status;
        
        return $this;
    }
    
    public function getStatus()
    {
        return $this->status; 
    }
    
    public function addErrors($errors = null) {
        
        if (is_string($errors)) {
            $errors = ['error' => [$errors]];            
        }
        
        if (is_array($errors)) {
            foreach ($errors as $key => &$errorValues) {
                foreach ($errorValues as &$value) {
                    $value = trans($value);
                }
            }
            $this->errors = $errors;
        }
            
        return $this;    
    }
    
    public function addError(string $key, $value)
    {
        $this->errors[$key][] = trans($value);
        
        return $this;
    }
    
    public function errors()
    {
        return $this->errors;        
    }

}
