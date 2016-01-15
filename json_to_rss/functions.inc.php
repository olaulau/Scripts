<?php

require_once('config.inc.php');

// base code found here : https://openclassrooms.com/courses/domxml-flux-rss-de-news

function &init_news_rss(&$xml_file) {
	global $conf;
	$root = $xml_file->createElement ( "rss" );
	$root->setAttribute ( "version", "2.0" );
	$root = $xml_file->appendChild ( $root );
	
	$channel = $xml_file->createElement ( "channel" );
	$channel = $root->appendChild ( $channel );
	
	$desc = $xml_file->createElement ( "description" );
	$desc = $channel->appendChild ( $desc );
	$text_desc = $xml_file->createTextNode ( $conf['description'] );
	$text_desc = $desc->appendChild ( $text_desc );
	
	$link = $xml_file->createElement ( "link" );
	$link = $channel->appendChild ( $link );
	$text_link = $xml_file->createTextNode ( $conf['link'] );
	$text_link = $link->appendChild ( $text_link );
	
	$title = $xml_file->createElement ( "title" );
	$title = $channel->appendChild ( $title );
	$text_title = $xml_file->createTextNode ( $conf['title'] );
	$text_title = $title->appendChild ( $text_title );
	
	return $channel;
}


function add_news_node(&$parent, $root, $id, $pseudo, $titre, $contenu, $date, $url) {
	global $conf;
	$item = $parent->createElement ( "item" );
	$item = $root->appendChild ( $item );
	
	$title = $parent->createElement ( "title" );
	$title = $item->appendChild ( $title );
	$text_title = $parent->createTextNode ( $titre );
	$text_title = $title->appendChild ( $text_title );
	
	$link = $parent->createElement ( "link" );
	$link = $item->appendChild ( $link );
	$text_link = $parent->createTextNode ( $url );
	$text_link = $link->appendChild ( $text_link );
	
	$desc = $parent->createElement ( "description" );
	$desc = $item->appendChild ( $desc );
	$text_desc = $parent->createTextNode ( $contenu );
	$text_desc = $desc->appendChild ( $text_desc );
	
// 	$com = $parent->createElement ( "comments" );
// 	$com = $item->appendChild ( $com );
// 	$text_com = $parent->createTextNode ( "http://www.bougiemind.info/news-11-" . $id . ".html" );
// 	$text_com = $com->appendChild ( $text_com );
	
	$author = $parent->createElement ( "author" );
	$author = $item->appendChild ( $author );
	$text_author = $parent->createTextNode ( $pseudo );
	$text_author = $author->appendChild ( $text_author );
	
	$pubdate = $parent->createElement ( "pubDate" );
	$pubdate = $item->appendChild ( $pubdate );
	$text_date = $parent->createTextNode ( $date );
	$text_date = $pubdate->appendChild ( $text_date );
	
// 	$guid = $parent->createElement ( "guid" );
// 	$guid = $item->appendChild ( $guid );
// 	$text_guid = $parent->createTextNode ( "http://www.bougiemind.info/rss_news" . $id . ".html" );
// 	$text_guid = $guid->appendChild ( $text_guid );
	
	$src = $parent->createElement ( "source" );
	$src = $item->appendChild ( $src );
	$text_src = $parent->createTextNode ( $conf['link'] );
	$text_src = $src->appendChild ( $text_src );
}


function rebuild_rss($courses) {
	global $conf;
	//create XML
	$xml_file = new DOMDocument ( "1.0" );
	// init XML file for RSS
	$channel = init_news_rss ( $xml_file );
	
	// loop the results to extract courses
	foreach ($courses as $course) {
// 		echo "<pre>"; print_r($course); echo "</pre>";
// 		echo "<br/> \n";
		$course_url = 'https://www.france-universite-numerique-mooc.fr/courses/' . $course['key'] . '/about';
// 		echo $course_url;
// 		echo "<br/> \n";
		$date = strtotime($course['start_date']);
		add_news_node ( $xml_file, $channel, $course['id'], $course['universities'][0]['name'], $course['title'], $course['short_description'], date ( "d/m/Y H:i", $date ), $course_url );
	}
	
	// get XML and output it
// 	$xml_file->save ( "news_FR_flux.xml" );
	$xml_output = $xml_file->saveXML();
	echo $xml_output;
}

