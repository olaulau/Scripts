<?php

// base code found here : https://openclassrooms.com/courses/domxml-flux-rss-de-news

function &init_news_rss(&$xml_file) {
	$root = $xml_file->createElement ( "rss" ); // création de l'élément
	$root->setAttribute ( "version", "2.0" ); // on lui ajoute un attribut
	$root = $xml_file->appendChild ( $root ); // on l'insère dans le nœud parent (ici root qui est "rss")
	
	$channel = $xml_file->createElement ( "channel" );
	$channel = $root->appendChild ( $channel );
	
	$desc = $xml_file->createElement ( "description" );
	$desc = $channel->appendChild ( $desc );
	$text_desc = $xml_file->createTextNode ( "L'excellence de l'enseignement supérieur pour des cours en ligne, gratuits et ouverts à tous" ); // on insère du texte entre les balises <description></description>
	$text_desc = $desc->appendChild ( $text_desc );
	
	$link = $xml_file->createElement ( "link" );
	$link = $channel->appendChild ( $link );
	$text_link = $xml_file->createTextNode ( "https://www.france-universite-numerique-mooc.fr/cours/" );
	$text_link = $link->appendChild ( $text_link );
	
	$title = $xml_file->createElement ( "title" );
	$title = $channel->appendChild ( $title );
	$text_title = $xml_file->createTextNode ( "FUN - Les cours" );
	$text_title = $title->appendChild ( $text_title );
	
	return $channel;
}


function add_news_node(&$parent, $root, $id, $pseudo, $titre, $contenu, $date, $url) {
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
	$text_src = $parent->createTextNode ( "https://www.france-universite-numerique-mooc.fr/cours/" );
	$text_src = $src->appendChild ( $text_src );
}


function rebuild_rss($courses) {
	// on crée le fichier XML
	$xml_file = new DOMDocument ( "1.0" );
	
	// on initialise le fichier XML pour le flux RSS
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
	
	
	
	
	// on ajoute chaque news au fichier RSS
	/*
	while ( $news = mysql_fetch_assoc ( $nws ) ) {
		add_news_node ( $xml_file, $channel, $news ["nws_id"], $news ["nws_pseudo"], $news ["nws_titre"], $news ["nws_contenu"], date ( "d/m/Y H:i", $news ["nws_date"] ) );
	}
	*/
// 	add_news_node ( $xml_file, $channel, "nws_id", "nws_pseudo", "nws_titre", "nws_contenu", date ( "d/m/Y H:i" ) );
	
	// on écrit le fichier
// 	$xml_file->save ( "news_FR_flux.xml" );
	$xml_output = $xml_file->saveXML();
	echo $xml_output;
}

