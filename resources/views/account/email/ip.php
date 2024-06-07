<?php view::extend('template/email') ?>

<?php view::block('title', 'New login at The Moscow Times'); ?>


<?php view::start('body'); ?>

<h1>New login at The Moscow Times </h1>

IP address: <?php view::text($ip) ?>
<br /><br />
Time: <?php view::text(date('Y-m-d H:i:s')) ?>
<br /><br />
If you recently signed in and recognize the IP address, you may disregard this email.
<br /><br />
If you did not recently sign in, you should immediately change your password. Passwords should be unique and not used for any other sites or services.
<br />
<br />
<br />
If you have any questions regarding your account or having technical issues, please contact <a href="mailto:development@themoscowtimes.com">development@themoscowtimes.com</a>
<br />
<br />
The Moscow Times Team
<?php view::end(); ?>