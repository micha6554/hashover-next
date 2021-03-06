<?php namespace HashOver;

// Copyright (C) 2018 Jacob Barkdull
// This file is part of HashOver.
//
// HashOver is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// HashOver is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with HashOver.  If not, see <http://www.gnu.org/licenses/>.


// Do some standard HashOver setup work
require (realpath ('../backend/standard-setup.php'));

// Autoload class files
spl_autoload_register (function ($uri) {
	$uri = str_replace ('\\', '/', strtolower ($uri));
	$class_name = basename ($uri);

	if (!@include (realpath ('../backend/classes/' . $class_name . '.php'))) {
		echo '"' . $class_name . '.php" file could not be included!';
		exit;
	}
});

try {
	// Instantiate HashOver class
	$hashover = new \HashOver ();
	$hashover->initiate ();
	$hashover->finalize ();

	// Template data
	$template = array (
		'title'			=> $hashover->locale->text['admin'],
		'moderation'		=> $hashover->locale->text['moderation'],
		'block-ip-addresses'	=> $hashover->locale->text['block-ip-addresses'],
		'filter-url-queries'	=> $hashover->locale->text['filter-url-queries'],
		'check-for-updates'	=> $hashover->locale->text['check-for-updates'],
		'documentation'		=> $hashover->locale->text['documentation'],
		'settings'		=> $hashover->locale->text['settings']
	);

	// Load and parse HTML template
	echo $hashover->templater->parseTemplate ('admin.html', $template);

} catch (\Exception $error) {
	$misc = new Misc ('php');
	$message = $error->getMessage ();
	$misc->displayError ($message);
}
