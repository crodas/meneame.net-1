<?

// This script is to add the statuses values of previous links for multisite support

include('../config.php');

$links = $db->object_iterator("SELECT  link_id as id, link_status as status, link_karma as karma, link_category as category,  UNIX_TIMESTAMP(link_date) as date,  UNIX_TIMESTAMP(link_sent_date) as sent_date, UNIX_TIMESTAMP(link_published_date) as published_date FROM links order by link_id desc", "Link");
if ($links) {
	$c = 0;
	$db->transaction();
	foreach($links as $link) {
		if ($c % 1000 == 0) {
			echo "$link->id, $link->category\n";
			$db->commit();
			usleep(100000);
			$db->transaction();
		}
		SitesMgr::deploy($link);
		$c++;
	}
	$db->commit();
}