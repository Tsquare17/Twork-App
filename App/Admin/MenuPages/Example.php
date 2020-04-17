<?php

namespace Twork\App\Admin\MenuPages;

use Twork\Admin\Dashboard\MenuPage;

class Example extends MenuPage
{
    /**
     * @var string The title of the menu in the dashboard sidebar.
     */
    protected $menuTitle = 'Example';

    /**
     * Display for the menu item page.
     */
    public function view(): void
    {
        ?>
        <h1>Example</h1>
        <form method="POST" action="">
            <?= t_nonce('twork_example') ?>
            <input type="text" name="t_example">
            <button type="submit" class="button action">Submit</button>
        </form>
        <?php
    }

    /**
     * Actions to run on on admin_init.
     */
    public function actions()
    {
        if (isset($_POST['t_example'])) {
            t_nonce_verify('twork_example');
            // Do some stuff
        }
    }
}
