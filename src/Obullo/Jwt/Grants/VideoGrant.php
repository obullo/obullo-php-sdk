<?php

namespace Obullo\Jwt\Grants;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class VideoGrant implements GrantInterface
{
    public function setRoomId(int $roomId)
    {
        $this->roomId = $roomId;
    }

    public function getRoomId() : int
    {
        return $this->roomId;
    }

    public function getGrantKey() : string
    {
        return 'video';
    }

    public function getPayload() : array
    {
        return ['roomId' => $this->getRoomId()];
    }
}