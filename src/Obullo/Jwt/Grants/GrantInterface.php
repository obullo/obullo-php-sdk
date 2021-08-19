<?php

namespace Obullo\Jwt\Grants;

interface GrantInterface {
    
    public function getGrantKey();
    public function getPayload();
}