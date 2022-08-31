<?php
namespace Inc\Base;


class app
{
    static function debug($variable)
    { ?>
        <pre>
            <?= print_r($variable); ?>
        </pre> <?php
    }

}