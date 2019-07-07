<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentDescription = array(
    "NAME" => GetMessage("NAME"),
    "DESCRIPTION" => GetMessage("DESCRIPTION"),
    "PATH" => array(
        "ID" => GetMessage("CHILD_NAME"),
        "CHILD" => array(
            "ID" => GetMessage("ID"),
            "NAME" => GetMessage("CHILD_NAME")
        )
    ),
);
?>