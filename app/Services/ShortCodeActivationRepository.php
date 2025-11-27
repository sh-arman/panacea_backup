<?php

namespace Panacea\Services;

use Cartalyst\Sentinel\Activations\IlluminateActivationRepository;
use Illuminate\Support\Str;

class ShortCodeActivationRepository extends IlluminateActivationRepository
{
    protected function generateActivationCode()
    {
        return strtoupper(Str::random(4));
    }
}
