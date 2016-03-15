<?php
namespace ToyToyToy\Model;
use \Illuminate\Database\Eloquent;

class User extends Eloquent\Model
{
    protected $fillable = ['name', 'email'];
}
