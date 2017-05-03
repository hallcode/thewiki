<?php

return [

    /**
     * Set public access on the following items. True = public, false = logged-in users only.
     *
     * @TODO Remove this section after doing permissions.
     */

    "general" => [
        "view" => true,
        "edit" => false,
    ],

    "home_page" => [
        "view" => false,
        "edit" => true,
    ],

    "registration" => [
        "open" => true,
        "approval" => true
    ],

    /**
     *  Roles available.
     *
     *  List them in order of seniority, from lowest at the top to highest at the bottom.
     */

    "roles" => [

        "new",
        "auto-confirmed",
        "confirmed",
        "moderator",
        "administrator"

    ],

    /**
     * ## Access Control ##
     *
     * Set the minimum role required for each action, or an array of roles that can do the action.
     * To allow any user AND any logged out user, just set to true.
     *
     * To prevent any user from doing an action set the value to false.
     */

    "permissions" => [

        "user" => [
            // New users will be given the role "user" - if you wish to approve them before an action can be taken,
            // use the "approved" role in actions.
            // Do not set a role here, just true or false.
            "register" => true,

            // Set a user role or false to prevent management of roles.
            "manage" => "administrator",

            "delete" => false,
        ],

        "page" => [
            "view" => "confirmed",
            "create" => "confirmed",
            "edit" => "confirmed",
            "protect" => "moderator",
            "archive" => "moderator",
            "restore" => "moderator",
            "delete" => false
        ],

        "upload" => [
            "view" => "confirmed",
            "create" => "confirmed",
            "edit" => "confirmed",
            "protect" => "moderator",
            "archive" => "moderator",
            "restore" => "moderator",
            "delete" => false
        ],

    ],
];