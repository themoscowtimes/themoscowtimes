<?php view::extend('template/email') ?>

<?php view::block('title', 'Welcome to The Moscow Times'); ?>

<?php view::start('body'); ?>

<h1>Welcome to The Moscow Times</h1>
Your account at the Moscow Times is almost ready!<br />
To confirm this e-mail address actually belongs to you, you need to click the link below.<br />
You will be taken to the Moscow Times website, where we will confirm your account.
<br />
<br />
<a href="<?php view::attr($url) ?>">Confirm your account</a>
<br />
<br />
<br />
If you have any questions regarding your account or having technical issues, please contact <a href="mailto:development@themoscowtimes.com">development@themoscowtimes.com</a>
<br />
<br />
The Moscow Times Team
<?php view::end(); ?>