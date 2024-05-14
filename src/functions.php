<?php


function phore_array(array $data) : \Phore\Arr\PhoreArray
{
    return new \Phore\Arr\PhoreArray($data);
}

function phore_assoc(array|object $data) : \Phore\Arr\PhoreArray
{
    return new \Phore\Arr\PhoreArray($data);
}

function phore_string(string $data) : \Phore\Arr\PhoreString
{
    return new \Phore\Arr\PhoreString($data);
}
