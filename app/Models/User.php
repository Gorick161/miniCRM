<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory; 
    use Notifiable, HasRoles;

    protected $fillable = ['name','email','password'];
    protected $hidden = ['password','remember_token'];

    public function ownedCompanies(): HasMany { return $this->hasMany(Company::class, 'owner_id'); }
    public function deals(): HasMany { return $this->hasMany(Deal::class, 'owner_id'); }
    public function assignedTasks(): HasMany { return $this->hasMany(Task::class, 'assigned_to'); }
}
