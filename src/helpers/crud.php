<?php

function editButton($url, $key)
{
    return HTML::link(modelURL($url, $key), 'Edit', ['class' => 'edit']);
}

function deleteButton($url, $key)
{
    $action = modelURL($url, $key);
    $csrf = Form::csrfToken();

    return "
        <form method=\"POST\" action=\"$action\">
            <input type=\"hidden\" name=\"csrfToken\" value=\"$csrf\">
            <button type=\"submit\" name=\"_method\" value=\"DELETE\" class=\"delete\"
                    onclick=\"return confirm('Are you sure?')\">Delete
            </button>
        </form>
    ";
}

function actionButtons($url, $key)
{
    $action = modelURL($url, $key);
    $csrf = Form::csrfToken();
    $editButton = editButton($url, $key);
//    $editButton = "<button type=\"submit\" formmethod=\"get\" formaction=\"$action\">Edit</button>";

    return "
        $editButton
        <form method=\"POST\" action=\"$action\" class=\"delete\">
            <input type=\"hidden\" name=\"csrfToken\" value=\"$csrf\">
            <button type=\"submit\" name=\"_method\" value=\"DELETE\" class=\"delete\"
                    onclick=\"return confirm('Are you sure?')\">Delete
            </button>
        </form>
    ";
}

function modelURL($url, $key)
{
    $params = [];

    foreach ($key as $k => $v) {
        $params[] = urlencode($k) . '=' . urlencode($v);
    }

    return $url . '?' . implode('&', $params);
}
