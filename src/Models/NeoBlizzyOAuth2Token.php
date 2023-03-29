<?php

namespace KasperFM\NeoBlizzy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NeoBlizzyOAuth2Token extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'neoblizzy_oauth2_tokens';

    protected $fillable = [
        'user_id',
        'game',
        'region',
        'realm',
        'profile_id',
        'token',
        'expires_at'
    ];
}
