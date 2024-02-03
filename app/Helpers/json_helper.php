<?php

function toJSON(mixed $value): string
{
    return htmlspecialchars(json_encode($value), ENT_QUOTES);
}
