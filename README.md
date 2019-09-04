# eloquent

Extensionst for laravel Eloquent\Model and other classes

* trait HasUserstamps

## Installation
Run:
```bash
composer require "limanweb/eloquent-extension"
```
## Package contents

* **Models**\
  * **Concerns**\
    * **HasUsertimestamps** - trait for userstamps filling in model

## Usage

### HasUserstamps

Add into create or update table migration fields for userstamps

```php
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            ...
            // Userstamps fields
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            // if SoftDeleting used add deleted_by
            // $table->bigInteger('deleted_by')->nullable();
              
        });
    }

    ...

}
```

Use Limanweb\EloquentExt\Models\Concerns\HasUserstamps trait in model and set public property `userstamps` into true 


```php

class User extends Authenticatable
{
    use Notifiable;

    use Limanweb\EloquentExt\Models\Concerns\HasUserstamps;
    
    public $userstamps = true;

```

The `created_by` and `updated_by` fields in your model will now be populated in the same way as the timestamp fields when you create and update the model. If your model uses SoftDeletes traite, will also be processed field, `deleted_by`.





