<?php view::extend('template/email') ?>

<?php view::block('title', 'Reset password at the Moscow Times'); ?>

<?php view::start('body'); ?>

<h1>Reset your password for The Moscow Times account</h1>
You have requested a new password for the Moscow Times personal account.
<br />
<br />
If you didn't initiate the process, please ignore this email. Please <a href="<?php view::attr($url) ?>">click here to reset your password</a>.
<br />
<br />
If you have any questions regarding your account or having technical issues, please contact <a href="mailto:development@themoscowtimes.com">development@themoscowtimes.com</a>
<br />
<br />
The Moscow Times Team

<?php view::end(); ?>