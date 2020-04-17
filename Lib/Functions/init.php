<?php

function t_nonce($name)
{
    return '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce($name) . '">';
}

function t_nonce_verify($name)
{
    return wp_verify_nonce($name, $name);
}
