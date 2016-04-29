<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'LMS ');
?>
<!DOCTYPE html>


<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $cakeDescription ?>:
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('cake.generic');
        echo $this->Html->css('menu');
        echo $this->Html->css('bootstrap.css');
        echo $this->Html->css('bootstrap-responsive.css');
        echo $this->Html->script('jquery-1.10.2.min.js');
        echo $this->Html->script('bootstrap-transition');
        echo $this->Html->script('bootstrap-alert');
        echo $this->Html->script('bootstrap-modal');
        echo $this->Html->script('bootstrap-scrollspy');
        echo $this->Html->script('bootstrap-tab');
        echo $this->Html->script('bootstrap-tooltip');
        echo $this->Html->script('bootstrap-popover');
        echo $this->Html->script('bootstrap-button');
        echo $this->Html->script('bootstrap-collapse');
        echo $this->Html->script('bootstrap-carousel');
        echo $this->Html->script('bootstrap-typeahead');
        echo $this->Html->script('bootstrap');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
        
        <script>
            var base_url = '<?php echo Router::url('/', true); ?>';
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="content">
                <?php echo $this->Session->flash(); ?>

                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">
            </div>
        </div>
    </body>
</html>
