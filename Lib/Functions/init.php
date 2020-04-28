<?php

/**
 * Get a nonce input.
 *
 * @param $name
 *
 * @return string
 */
function twork_nonce($name)
{
    return '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce($name) . '">';
}

/**
 * Verify the nonce input.
 *
 * @param $name
 *
 * @return bool|int
 */
function twork_nonce_verify($name)
{
    return wp_verify_nonce($name, $name);
}
