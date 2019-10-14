<?php

use Alfred\Workflows\Workflow;

require 'vendor/autoload.php';

$workflow = new Workflow;

// add variables
$root_url =  getenv('wiki_url');
$url = $root_url . "/api.php?action=query&list=allcategories&format=json&aclimit=500";
$json = file_get_contents( $url );
$obj = json_decode( $json, TRUE );
$cats = [];
foreach ( $obj['query']['allcategories'] as $item ) {
	$cats[] = $item['*'];
}
$query = $argv[1];
foreach ( $cats as $cat ) {
	$cat_url = $root_url . "?title=Category:" . $cat . "&action=edit&redlink=1";
	$workflow->result()
		->uid( $cat )
		->title( $cat )
		->subtitle( '' )
		->quicklookurl( $cat_url )
		->type( 'default' )
		->arg( $cat_url )
		->valid( TRUE );
}

if ( !empty( $query ) ) {
	// Default is searching in title:
	$workflow->filterResults( $query );
}

echo $workflow->output();