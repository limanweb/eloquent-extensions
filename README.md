# eloquent

Extensions for Laravel Eloquent\Model and other classes

* Trait **HasUserstamps** for filling userstamp fields `created_at`, `updated_at`, `deleted_at` by authorized user ID

## Installation

Run:

```bash
composer require "limanweb/eloquent-extension"
```
## Package contents

* /**Models**
  * /**Concerns**
    * **HasUsertimestamps.php** - trait for userstamps filling in model

## Usage

### HasUserstamps

Add into create or update table migration fields for userstamps  `created_at`, `updated_at` and `deleted_at`.
For examle modify CreateUsersTable migration.

```php
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            ...
            // add userstamps fields
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            // if SoftDeletes trait will be used in model then add deleted_by field
            // $table->bigInteger('deleted_by')->nullable();
              
        });
    }

    ...

}
```

In the model you must:

1. declare using of trait Limanweb\EloquentExt\Models\Concerns\HasUserstamps
2. use HasUserstamps trait in the model
3. enable userstamps by define public property `$userstamps` with `true` value 

```php
...

use Limanweb\EloquentExt\Models\Concerns\HasUserstamps;  // (1) declare

class User extends Authenticatable
{
    use Notifiable;
    use HasUserstamps;          // (2) use trait in the model
    
    public $userstamps = true;  // (3) enable userstamps

    ...
}

```

The `created_by` and `updated_by` fields in your model will now be populated in the same way as the timestamp fields when you create and update the model. If your model uses SoftDeletes traite, will also be processed field, `deleted_by`.





