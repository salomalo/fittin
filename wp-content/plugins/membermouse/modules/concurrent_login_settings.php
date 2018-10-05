<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */ 

if(isset($_POST[MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS_INCLUDE_ADMINS]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS_INCLUDE_ADMINS,"1");
}

if(isset($_POST[MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS]))
{
	$val = intval($_POST[MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS]);
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS,$val);
	if(!isset($_POST[MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS_INCLUDE_ADMINS]))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS_INCLUDE_ADMINS,"0");
	}	
}

$useAdminsInConcurrentLoginLimit = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS_INCLUDE_ADMINS);
$totalConcurrentLogins = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS);
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 
<div class="mm-wrap">
    <p class="mm-header-text"><?php echo _mmt("Concurrent Login Limit"); ?></p>
   
	<div style="margin-top:10px;">
		<p><?php echo _mmt("You can limit how many concurrent locations a member can login from."); ?></p>
		
		 <div style="margin-top:10px;">
			<?php echo sprintf(_mmt("Members can have %s concurrent logins."),"<input type='text' style='width: 50px;' name='". MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS."' value='".$totalConcurrentLogins."' />");?> <?php echo MM_Utils::getInfoIcon("If you set this value to '0' you will disable the concurrent login limitation.", ""); ?>
			<br /><br />
			<input name="<?php echo MM_OptionUtils::$OPTION_KEY_SIMULTANEOUS_LOGINS_INCLUDE_ADMINS; ?>" type="checkbox" <?php echo (($useAdminsInConcurrentLoginLimit=="1")?"checked":""); ?> /> Include administrators in this limitation.
			
		</div>
	</div>
</div>