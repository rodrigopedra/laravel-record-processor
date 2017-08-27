<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use Illuminate\Database\Eloquent\Model;

class UserEloquentModel extends Model
{
    public $timestamps = false;

    protected $table      = 'users';
    protected $primaryKey = 'rowid';

    protected $fillable = [
        'rowid',
        'name',
        'email',
    ];

    protected $hidden = [ 'rowid' ];
}
