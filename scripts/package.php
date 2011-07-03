#!/usr/bin/env php
<?php

// Fix warinigs
// pirum add target_dir Pirum-1.0.0.tgz
// scp Skulk-0.1.5.tgz pear.zircote.com:/export/sites/pear.zircote.com/
error_reporting(error_reporting() ^ E_DEPRECATED);
if (version_compare(PHP_VERSION, '5.3.2') >= 0) {
    error_reporting(error_reporting() ^ E_DEPRECATED);
}
date_default_timezone_set('America/Chicago');

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

/**
 * Recursively populated $GLOBALS['files']
 *
 * @param string $path The path to glob through.
 *
 * @return void
 * @uses   $GLOBALS['files']
 */
function readDirectory($path)
{
    foreach (glob($path . '/*') as $file) {
        if (!is_dir($file)) {
            $GLOBALS['files'][] = $file;
        } else {
            readDirectory($file);
        }
    }
}

$outsideDir = realpath(dirname(dirname(__FILE__)));

$version = file_get_contents($outsideDir . '/VERSION.txt');

$api_version     = $version;
$api_state       = 'alpha';

$release_version = $version;
$release_state   = 'alpha';
$release_notes   = "This is an alpha release, see README.markdown for examples.";

$summary     = "Skulk - A Zend Framework Client for Prowl-API";

$description = "Prowl is the Growl client for iOS. Push to your iPhone, iPod
touch, or iPad your notifications from a Mac or Windows computer, or from a
multitude of apps and services. Easily integrate the Prowl API into your
applications.

More information and documentation on homepage: http://www.prowlapp.com/";

$package = new PEAR_PackageFileManager2();

$package->setOptions(
    array(
        'filelistgenerator'       => 'file',
        'outputdirectory'         => dirname(dirname(__FILE__)),
        'simpleoutput'            => true,
        'baseinstalldir'          => '/',
        'packagedirectory'        => $outsideDir,
        'dir_roles'               => array(
            'benchmarks'          => 'doc',
            'examples'            => 'doc',
            'library'             => 'php',
            'library/Skulk'       => 'php',
            'tests'               => 'test',
        ),
        'exceptions'              => array(
            'CHANGELOG'           => 'doc',
            'README.markdown'     => 'doc',
            'VERSION.txt'         => 'doc',
        ),
        'ignore'                  => array(
            'build/*',
            'package.xml',
            'build.xml',
            'scripts/package.php',
            '.git',
            '.gitignore',
            'tests/phpunit.xml',
            'tests/build*',
            '.project',
            '.buildpath',
            '.settings',
            '*.tgz'
        )
    )
);

$package->setPackage('Skulk');
$package->setSummary($summary);
$package->setDescription($description);
$package->setChannel('pear.zircote.com');
$package->setPackageType('php');
$package->setLicense(
    'Apache 2.0',
    'http://www.apache.org/licenses/LICENSE-2.0'
);

$package->setNotes($release_notes);
$package->setReleaseVersion($release_version);
$package->setReleaseStability($release_state);
$package->setAPIVersion($api_version);
$package->setAPIStability($api_state);
/**
 * Dependencies
 */
$package->addPackageDepWithChannel(
    'optional', 'zf', 'pear.zfcampus.org', '1.11.5', false, true
);

$maintainers = array(
    array(
        'name'  => 'Robert Allen',
        'user'  => 'zircote',
        'email' => 'zircote@zircote.com',
        'role'  => 'lead',
    )
);

foreach ($maintainers as $_m) {
    $package->addMaintainer(
        $_m['role'],
        $_m['user'],
        $_m['name'],
        $_m['email']
    );
}

$files = array(); // classes and tests
readDirectory($outsideDir . '/library');
readDirectory($outsideDir . '/tests');

$base = $outsideDir . '/';

foreach ($files as $file) {

    $file = str_replace($base, '', $file);

    $package->addReplacement(
        $file,
        'package-info',
        '@name@',
        'name'
    );

    $package->addReplacement(
        $file,
        'package-info',
        '@package_version@',
        'version'
    );
}

$files = array(); // reset global
readDirectory($outsideDir . '/library');

foreach ($files as $file) {
    $file = str_replace($base, '', $file);
    $package->addInstallAs($file, str_replace('library/', '', $file));
}


$package->setPhpDep('5.2.1');

$package->setPearInstallerDep('1.7.0');
$package->generateContents();
$package->addRelease();

if (   isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')
) {
    $package->writePackageFile();
} else {
    $package->debugPackageFile();
}