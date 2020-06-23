<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
      0 => '9999999-dev',
    ),
    'reference' => '99ee5dc70f3518a316ac5e9c9c55b6327479b849',
    'name' => 'milliondollarscript/milliondollarscript',
  ),
  'versions' => 
  array (
    'components/jquery' => 
    array (
      'pretty_version' => '3.5.1',
      'version' => '3.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b33e8f0f9a1cb2ae390cf05d766a900b53d2125b',
    ),
    'components/jqueryui' => 
    array (
      'pretty_version' => '1.11.4',
      'version' => '1.11.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c34f8dbf3ba57b3784b93f26119f436c0e8288e1',
    ),
    'ezyang/htmlpurifier' => 
    array (
      'pretty_version' => 'v4.12.0',
      'version' => '4.12.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a617e55bc62a87eec73bd456d146d134ad716f03',
    ),
    'imagine/imagine' => 
    array (
      'pretty_version' => '1.2.3',
      'version' => '1.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cb2361e5bb4410b681462d8e4f912bc5dabf84ab',
    ),
    'jquery-form/form' => 
    array (
      'pretty_version' => 'v4.3.0',
      'version' => '4.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fcbd5773d19a75d4b2f336df7f49788a42b71fbc',
    ),
    'milliondollarscript/milliondollarscript' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '99ee5dc70f3518a316ac5e9c9c55b6327479b849',
    ),
    'phpmailer/phpmailer' => 
    array (
      'pretty_version' => 'v6.1.6',
      'version' => '6.1.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c2796cb1cb99d7717290b48c4e6f32cb6c60b7b3',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
