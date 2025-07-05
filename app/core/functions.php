<?php

use Models\Image;

defined('ROOTPATH') or exit('Access Denied!');
/** Check with php extensions are required **/
checkExtensions();
function checkExtensions(): void
{
    $requiredExtensions = [
        'gd',
        'pdo_mysql',
        'curl',
        'fileinfo',
        'intl',
        'exif',
        'mbstring',
    ];
    $notLoaded = [];
    foreach ($requiredExtensions as $extension) {
        if (!extension_loaded($extension)) {
            $notLoaded[] = $extension;
        }
    }
    if (!empty($notLoaded)) {
        die("Please load the folowing  extensions in your php.ini file:<br> " . implode("<br>", $notLoaded));
    }
}

function print_pre($var): void
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
}

function esc($string): string
{
    return htmlspecialchars($string);
}

function redirect($url): void
{
    header('Location: ' . DOCUMENT_ROOT . "/" . $url);
    die();
}

/** Load image if image not exists, load placeholder **/
function getImage(mixed $file = '', string $type = 'post'): string
{
    $file = $file ?? '';
    if (file_exists($file)) {
        return DOCUMENT_ROOT . "/" . $file;
    }

    if ($type === 'user') {
        return DOCUMENT_ROOT . "/assets/images/user.jpg";
    } else {
        return DOCUMENT_ROOT . "/assets/images/no_image.jpg";
    }
}

/** Returns pagination link **/
function getPaginationVars(): array
{
    $vars = [];
    $vars['page'] = $_GET['page'] ?? 1;
    $vars['page'] = (int)$vars['page'];
    $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
    $vars['next_page'] = $vars['page'] + 1;

    return $vars;
}
/** Saves or displays a saved message to the user **/
function message(mixed $msg = null, bool $clear = false)
{
    $ses = new core\Session();

    if (!empty($msg)) {
        $ses->set('message', $msg);
    } else
    if (!empty($ses->get('message'))) {
        $msg = $ses->get('message');
        if ($clear) {
            $ses->pop('message');
        }
        return $msg;
    }

    return false;
}
/** Return URL variables **/
function URL($key): mixed
{
    $URL = $_SERVER['REQUEST_URI'] ?? 'home';
    if ($_SERVER['REQUEST_URI'] === '/') {
        $URL = 'home';
    }
    $URL = explode('/', trim($URL, '/'));

    switch ($key) {
        case 'page':
        case 0:
            return $URL[0] ?? null;
            break;
        case 'section':
        case 'slug':
        case 1:
            return $URL[1] ?? null;
            break;
        case 'action':
        case 2:
            return $URL[2] ?? null;
            break;
        case 'id':
        case 3:
            return $URL[3] ?? null;
            break;
        default:
            return null;
            break;
    }
}
/** Displays input values after a page refresh **/
function oldChecked(string $key, mixed $value, mixed $default = ""): string
{
    if (isset($_POST[$key])) {
        if ($_POST[$key] === $value) {
            return ' checked ';
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $default === $value) {
            return ' checked ';
        }
    }

    return '';
}

function oldValue(string $key, mixed $default = "", string $mode = 'post'): mixed
{
    $post = ($mode === 'post') ? $_POST : $_GET;
    if (isset($post[$key])) {
        return $post[$key];
    }
    /*return $post[$key] ?? $default;*/
    return $default;
}

function oldSelect(string $key, mixed $value, mixed $default = "", string $mode = 'post'): mixed
{
    $post = ($mode === 'post') ? $_POST : $_GET;
    if (isset($post[$key])) {
        if ($post[$key] === $value) {
            return " selected ";
        }
    } else
    if ($default === $value) {
        return " selected ";
    }

    return "";
}
/** Returns a user readable date format **/
function get_date($date): string
{
    return date("jS M, Y", strtotime($date));
}
/** Converts image path from relative to absolute **/
function addRootToImages($contents)
{
    preg_match_all('/<img[^>]+>]/', $contents, $matches);
    if (is_array($matches) && count($matches) > 0) {
        foreach ($matches[0] as $match) {
            preg_match('/src="[^"]+/', $match, $matches2);
            if (!strstr($matches2[0], 'http')) {
                $contents = str_replace($matches2[0], 'src="' . DOCUMENT_ROOT .'/'. str_replace('src="', "", $matches2[0]) , $contents);
            }
        }
    }

    return $contents;
}
/** Converts image from text editor content to actual files **/
function removeImagesFromContent(string $content, $folder = "uploads/")
{
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
        file_put_contents($folder . "index.php", "Access Denied!");
    }

    //remove images from content
    preg_match_all('/<img[^>]+>/', $content, $matches);
    $newContent = $content;

    if (is_array($matches) && count($matches) > 0) {

        $imageClass = new Image();
        foreach ($matches[0] as $match) {
            if (strstr($match, "http")) {
                // ignore images with links already
                continue;
            }

            //get the src
            preg_match('/src="[^"]+/', $match, $matches2);

            //get th filename
            preg_match('/data-filename="[^\"]+/', $match, $matches3);

            if (strstr($matches2[0], 'data:')) {
                $parts = explode(",", $matches2[0]);
                $basename = $matches3[0] ?? 'basename.jpg';
                $basename = str_replace('data-filename="', "", $basename);

                $filename = $folder . 'img_' . sha1(rand(0, 9999999999)) . $basename;

                $newContent = str_replace($parts[0] . "," . $parts[1], 'src="' . $filename, $newContent);
                file_put_contents($filename, base64_decode($parts[1]));

                // Resize image
                $imageClass->resize($filename, 1000);
            }
        }
    }

    return$newContent;
}
/** Delete image from text editor content **/
function deleteImagesFromContent(string $content, string $contentNew = '')
{
    // Delete image from content
    if (empty($contentNew)) {
        preg_match_all('/<img[^>]+>/', $content, $matches);

        if (is_array($matches) && count($matches) > 0) {
            foreach ($matches[0] as $match) {
                preg_match('/src="[^"]+/', $match, $matches2);
                $matches2[0] = str_replace('src="', "", $matches2[0]);

                if (file_exists($matches2[0])) {
                    unlink($matches2[0]);
                }
            }
        }
    } else {
        // compare old to new and delete from old what inst in the new
        preg_match_all('/<img[^>]+>/', $content, $matches);
        preg_match_all('/<img[^>]+>/', $contentNew, $matchesNew);

        $oldImages = [];
        $newImages = [];

        /** Collect old images **/
        if (is_array($matches) && count($matches) > 0) {
            foreach ($matches[0] as $match) {
                preg_match('/src="[^"]+/', $match, $matches2);
                $matches2[0] = str_replace('src="', "", $matches2[0]);

                if (file_exists($matches2[0])) {
                    $oldImages[] = $matches2[0];
                }
            }
        }

        /** Collect new images **/
        if (is_array($matchesNew) && count($matchesNew) > 0) {
            foreach ($matchesNew[0] as $match) {
                preg_match('/src="[^"]+/', $match, $matches2);
                $matches2[0] = str_replace('src="', "", $matches2[0]);

                if (file_exists($matches2[0])) {
                    $newImages[] = $matches2[0];
                }
            }
        }

        /** Compare and delete all that don't appear in the new array */
        foreach ($oldImages as $img) {
            if (in_array($img, $newImages)) {
                if (file_exists($img)) {
                    unlink($img);
                }
            }
        }
    }
}
