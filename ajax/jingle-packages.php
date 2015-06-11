<?php

/**
 * manages jingle packages and their contents
 * 
 */

$jsonrpc = new JSONRPC();

switch($jsonrpc->get_method()) {
	case 'info':
		if(!isset($jsonrpc->params['id'])) $jsonrpc->error('invalid package ID');

		$package = JinglePackages::get_by_id($jsonrpc->params['id']);
		if($package == null) $jsonrpc->error('invalid package');

		$jingles = array();
		foreach($package->get_jingles() as $jingle) {
			$jingles[] = array(
				'id' => $jingle->get_id(),
				'title' => $jingle->get_title()
			);
		}

		$jsonrpc->output(array(
			'package' => array(
				'id' => $package->get_id(),
				'name' => $package->get_name(),
				'description' => $package->get_description()
			),
			'jingles' => $jingles
		));

		break;
	case 'delete_from':
		if(!isset($jsonrpc->params['package_id']) || !isset($jsonrpc->params['jingle_id'])) $jsonrpc->error('invalid jingle or package');

		$package = JinglePackages::get_by_id($jsonrpc->params['package_id']);
		$jingle = Jingles::get_by_id($jsonrpc->params['jingle_id']);

		$result = $package->delete_jingle($jingle);

		if($result == true) $jsonrpc->output('ok');
		else $jsonrpc->output('error');

		break;
	case 'search':
		if(!isset($jsonrpc->params['q'])) $jsonrpc->error('invalid search query');

		$results = Search::jingles($jsonrpc->params['q']);
		$jingles = array();
		foreach($results['results'] as $result) $jingles[] = array('id' => $result, 'label' =>  Jingles::get_by_id($result)->get_title());

		$jsonrpc->output($jingles);

		break;
}
