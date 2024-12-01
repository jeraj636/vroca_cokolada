<?php

function zaK($tab)
{
    return base64_encode(serialize($tab));
}


function odK($tab)
{
    return unserialize(base64_decode($tab));
}
