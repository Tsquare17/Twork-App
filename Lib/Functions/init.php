<?php

/**
 * Get a nonce input.
 *
 * @param $name
 *
 * @return string
 */
function twork_nonce_input($name)
{
    return '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce($name) . '">';
}
