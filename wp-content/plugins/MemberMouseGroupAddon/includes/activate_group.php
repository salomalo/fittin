<?php
global $wpdb;
if(!isset($wpdb)):
	require_once('../../../../wp-config.php');
    require_once('../../../../wp-includes/wp-db.php');
endif;
include_once(ABSPATH."wp-content/plugins/membermouse/includes/mm-constants.php");
include_once(ABSPATH."wp-content/plugins/membermouse/includes/init.php");

if(count($_POST) > 0):
	foreach($_POST as $key => $value):
		$$key = $value;
	endforeach;
	
	$groupSql	= "UPDATE ".$wpdb -> prefix."group_sets SET group_status = '1', modifiedDate = now() WHERE id = '".$id."'";
	$groupQuery	= $wpdb -> query($groupSql);
	if($groupQuery):
		$leaderSql		= "SELECT group_leader FROM ".$wpdb -> prefix."group_sets WHERE id = '".$id."'";
		$leaderResult	= $wpdb -> get_row($leaderSql);
		$group_leader	= $leaderResult -> group_leader;
		if(!empty($group_leader)):
			$leader		= new MM_User($group_leader);
			$lStatus	= MM_AccessControlEngine::changeMembershipStatus($leader, MM_Status::$ACTIVE);
		endif;
		$memberSql		= "SELECT member_id FROM ".$wpdb -> prefix."group_sets_members WHERE group_id = '".$id."'";
		$memberResults	= $wpdb -> get_results($memberSql);
		$memberCount	= count($memberResults);
		if($memberCount > 0):
			foreach($memberResults as $memberResult):
				$member_id	= $memberResult -> member_id;
				if(!empty($member_id)):
					$member 		= new MM_User($member_id);
					$memberStatus	= MM_AccessControlEngine::changeMembershipStatus($member, MM_Status::$ACTIVE);
				endif;
			endforeach;
		endif;
		$return["success"]	= "yes";
	else:
		$return["success"]	= "no";
	endif;
	echo json_encode($return);	
endif;
?>	