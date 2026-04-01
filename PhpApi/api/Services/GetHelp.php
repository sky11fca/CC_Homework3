<?php

namespace Services;

class GetHelp
{
    public function getHelp() : void
    {
        $response = [
            "status" => true,
            "possible_methods" => [
                "GET /api/help" => "Display this message",
                "GET /api/items" => "Displays all items in the database",
                "GET /api/items/{id}" => "Display information of an item given ID",
                "POST /api/items" => "Creates a new item resource",
                "POST /api/purchase" => "Creates a purchase resource",
                "PUT /api/items/{id}" => "Modifies an entire item resource (if it doesn't exist, it creates it)",
                "PUT /api/items/{id}/rotten" => "Changes the quality of the product to rotten",
                "DELETE /api/items/{id}" => "Deletes a Resource",
                "DELETE /api/items" => "Deletes all item resources that are rotten"
            ]
        ];
        http_response_code(200);
        echo(json_encode($response));
    }
}