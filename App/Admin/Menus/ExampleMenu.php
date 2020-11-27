<?php

namespace Twork\App\Admin\Menus;

use Twork\Admin\Menu as AbstractMenu;

class ExampleMenu extends AbstractMenu
{
    protected $menuTitle = 'Example';

    /**
     * Display for the menu item page.
     */
    public function view(): void
    {
        ?>
        <h1>Example</h1>
        <form method="POST" action="">
            <input type="hidden" name="twork_example" value="<?= wp_create_nonce('twork_example') ?>">
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
            wp_verify_nonce($_POST['twork_example'], 'twork_example');
            // Do some stuff
        }
    }

    public function submenus()
    {
        $this->addSubmenu('A Submenu', [$this, 'submenuView']);
    }

    public function submenuView()
    {
        echo '<h1>Example Submenu</h1>';
    }

    public function enqueue()
    {
        // wp_enqueue_script('example', TWORK_JS_URL . '/example.min.js', ['jquery'], TWORK_VERSION, true);
    }
}
