<?php

namespace Obullo\Jwt\Grants;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
interface GrantInterface {
    
    public function getGrantKey();
    public function getPayload();
}