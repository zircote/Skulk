<?php
/**
 * @license Copyright 2010 Robert Allen
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function SkulkTest_Autoloader ($class)
{
    $class = ltrim($class, '\\');
    if (! preg_match('#^(Skulk(Test)?|PHPUnit)(\\\\|_)#', $class)) {
        return false;
    }
    // $segments = explode('\\', $class); // preg_split('#\\\\|_#', $class);//
    $segments = preg_split('#[\\\\_]#', $class); // preg_split('#\\\\|_#', $class);//
    $ns = array_shift($segments);
    switch ($ns) {
        case 'Skulk':
            $file = dirname(__DIR__) . '/library/Skulk/';
            break;
        case 'SkulkTest':
            $file = __DIR__ . '/Skulk/';
            break;
        default:
            $file = false;
            break;
    }
    if ($file) {
        $file .= implode('/', $segments) . '.php';
        if (file_exists($file)) {
            return include_once $file;
        }
    }
    $segments = explode('_', $class);
    $ns = array_shift($segments);
    switch ($ns) {
        case 'PHPUnit':
            return include_once str_replace('_', DIRECTORY_SEPARATOR, $class) .
             '.php';
        case 'Skulk':
            $file = dirname(__DIR__) . '/library/Skulk/';
            break;
        default:
            return false;
    }
    $file .= implode('/', $segments) . '.php';
    if (file_exists($file)) {
        return include_once $file;
    }
    return false;
}
spl_autoload_register('SkulkTest_Autoloader', true, true);

