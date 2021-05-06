<?php

function random256Hex()
{
    return bin2hex(random_bytes(32));
}
