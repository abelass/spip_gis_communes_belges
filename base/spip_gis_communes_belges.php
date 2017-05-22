<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function gis_cb_importer_points() {
	$json = json_decode(file_get_contents(__DIR__ . '/../data/complete_data.json'), TRUE);

	$set = array();
	foreach($json AS $values) {
		$nom_fr = $values['Name']['fr'];
		$nom_nl = isset($values['Name']['nl']) ? $values['Name']['nl'] : $nom_fr;
		$polygon = array('type' => 'Feature','geometry' => $values['POLYGON']);
		$wkt = json_to_wkt(json_encode($polygon));
		$wkt = sql_getfetsel("GeomFromText('$wkt')");

		$set[] = array(
			'titre' => '<multi>[fr]' . $nom_fr. '[nl]' . $nom_nl. '</multi>',
			'lat' => $values['lat'],
			'lon' => $values['lng'],
			'geo' => $wkt,
			'zoom' => 11,
			'type' => 'Multipolygon',
			'pays' => '<multi>[fr]Belgique[nl]België</multi>',
			'code_pays' => 'BE',
			'ville' => '<multi>[fr]' . $nom_fr. '[nl]' . $nom_nl. '</multi>',
			'code_postal' => $values['zip'],
			);
	}
	sql_insertq_multi('spip_gis', $set);
}

