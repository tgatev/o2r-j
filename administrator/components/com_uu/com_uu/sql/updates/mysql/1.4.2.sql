INSERT IGNORE INTO `#__uu_configuration`
(`key`, `value`) VALUES
('activate_with_admin_activation_mail_subject','Registration approval required for account of {hi_name} at {sitename}');

INSERT IGNORE INTO `#__uu_configuration`
(`key`, `value`) VALUES
('activate_with_admin_activation_mail_body','<p>Hello Administator,<br /><br />A new user has registered at {siteurl}<br />The user has verified his email address and requests that you approve his account.<br />This email contains their details:<br /><br />  Name :  {hi_name} <br />  email:  {email} <br /> Username:  {username} <br /><br />You can activate the user by clicking on the link below:<br /> {activate_link}.</p>');
