<?php

function dd($data, $die = true) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($die) die;
}

const FLASH = 'FLASH_MESSAGES';

const FLASH_ERROR = 'error';
const FLASH_WARNING = 'warning';
const FLASH_INFO = 'info';
const FLASH_SUCCESS = 'success';

/**
 * Create a flash message
 *
 * @param string $name
 * @param string $message
 * @param string $type
 * @return void
 */
function create_flash_message($name,  $message,  $type)
{
    // remove existing message with the name
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }
    // add the message to the session
    $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
}


/**
 * Format a flash message
 *
 * @param array $flash_message
 * @return string
 */
function format_flash_message($flash_message)
{
    return sprintf('<div class="alert alert-%s" style="margin-top:24px"><span class="closebtn" onclick="this.parentElement.style.display=\'none\'";">&times;</span> %s</div>',
        $flash_message['type'] == "error" ? "danger" : $flash_message['type'],
        $flash_message['message']
    );
}

/**
 * Display a flash message
 *
 * @param string $name
 * @return void
 */
function display_flash_message($name)
{
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }

    // get message from the session
    $flash_message = $_SESSION[FLASH][$name];

    // delete the flash message
    unset($_SESSION[FLASH][$name]);

    // display the flash message
    echo format_flash_message($flash_message);
}

/**
 * Display all flash messages
 *
 * @return void
 */
function display_all_flash_messages()
{
    if (!isset($_SESSION[FLASH])) {
        return;
    }

    // get flash messages
    $flash_messages = $_SESSION[FLASH];

    // remove all the flash messages
    unset($_SESSION[FLASH]);

    // show all flash messages
    foreach ($flash_messages as $flash_message) {
        echo format_flash_message($flash_message);
    }
}

/**
 * Flash a message
 *
 * @param string $name
 * @param string $message
 * @param string $type (error, warning, info, success)
 * @return void
 */
function flash($name = '', $message = '', $type = '')
{
    if ($name !== '' && $message !== '' && $type !== '') {
        // create a flash message
        create_flash_message($name, $message, $type);
    } elseif ($name !== '' && $message === '' && $type === '') {
        // display a flash message
        display_flash_message($name);
    } elseif ($name === '' && $message === '' && $type === '') {
        // display all flash message
        display_all_flash_messages();
    }
}

function renderPaginationLinks($count, $pageSize)
{
    $currentPage = !empty($_GET['page']) ? $_GET['page'] : 1;
    $totalPage = ceil($count / $pageSize);
    
    $pagePrev = $currentPage - 1;
    $prevPageParams = http_build_query(array_merge($_GET, ['page' => $pagePrev]));

    $pageNext = $currentPage + 1;
    $nextPageParams = http_build_query(array_merge($_GET, ['page' => $pageNext]));

    $head = "
    <nav aria-label='...'>
        <ul class='pagination'>
            <li class='page-item ". ($currentPage == 1 ? 'disabled' : '') ."'>
                <a class='page-link' href='?$prevPageParams'>Previous</a>
            </li>
    ";
    $body = "";
    $tail = "
            <li class='page-item ". ($currentPage == $totalPage ? 'disabled' : '') ."'>
                <a class='page-link' href='?$nextPageParams'>Next</a>
        </li>
        </ul>
    </nav>
    ";

    for ($i=1; $i <= $totalPage; $i++) {
        $newParams = array_merge($_GET, ['page' => $i]);
        $newParams = http_build_query($newParams);
        // dd($newParams);
        $active = $i == $currentPage ? 'active' : '';
        $href = $i == $currentPage ? '#' : "?$newParams";
        $body .= "<li class='page-item $active'><a class='page-link' href='$href'>$i</a></li>";
    }
    return $head . $body . $tail;
}