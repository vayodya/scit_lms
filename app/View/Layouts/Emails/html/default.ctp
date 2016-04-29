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
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<?php 
		$content = $this->fetch('content');
		$content = explode('\n', $content);
		foreach ($content as $value) {
			echo '<p> ' . $value . '<p>';
		}
	?>	 
        <?php echo '</br></br>'; ?>
	<p>All Rights Reserved&nbsp;&nbsp;<font color = "blue">Softcodeit&nbsp;&nbsp;</font><a href="http://lms.softcodeit.net/">Leave Management System</a></p>
</body>
</html>