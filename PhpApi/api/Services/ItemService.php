<?php

namespace Services;

use Model\Enums\ItemQuality;
use Model\Item;
use Persistance\DbContext;
use PDO;
use Random\RandomException;
use Validators\ItemValidator;
use Validators\UpdateItemValidator;

class ItemService
{
    private PDO $dbContext;

    public function __construct(DbContext $dbContext)
    {
        $this->dbContext = $dbContext->connect();
    }
    public function getItemById(string $id): void
    {
        $stmt = $this->dbContext->prepare("SELECT * FROM items WHERE id = :id");
        $stmt->execute([
            ":id" => $id
        ]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if($item){
            http_response_code(200);
            echo json_encode($item);
        }
        else{
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "id" => $id,
                "status" => 404,
                "message" => "Item not found"
            ]);
        }
    }

    public function getItems(): void
    {
        $stmt = $this->dbContext->query("SELECT * FROM items ");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($items);
    }

    public function getPurchases(): void
    {
        $stmt = $this->dbContext->prepare("SELECT * FROM purchases");
        $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($purchases);
    }

    public function addItem($jsonData): void
    {
        try{
            $id = bin2hex(random_bytes(16));

            $result = ItemValidator::validate($jsonData);
            if(!$result){
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "status" => 400,
                    "message" => "Bad Request"
                ]);
            }

            $itemQuality = match ($jsonData["quality"]) {
                "VeryGood" => ItemQuality::VeryGood,
                "Good" => ItemQuality::Good,
                "Mediocre" => ItemQuality::Mediocre,
                "Bad" => ItemQuality::Bad,
                "Rotten" => ItemQuality::Rotten,
                default => ItemQuality::UNKNOWN,
            };

            $item = new Item($id, $jsonData['name'], $jsonData['quantity'], $itemQuality, $jsonData['price']);

            $stmt = $this->dbContext->prepare("INSERT INTO items (id, name, quantity, quality, price) VALUES (:id, :name, :quantity, :quality, :price)");
            $stmt->execute([
                ":id" => $item->getId(),
                ":name" => $item->getName(),
                ":quantity" => $item->getQuantity(),
                ":quality" => $item->getQuality()->name,
                ":price" => $item->getPrice(),
            ]);

            http_response_code(200);
            echo json_encode([
                "message" => "Item created",
                "item" => [
                    "id" => $item->getId(),
                    "name" => $item->getName(),
                    "quantity" => $item->getQuantity(),
                    "quality" => $item->getQuality()->name,
                    "price" => $item->getPrice()
                ]]);
        }
        catch (RandomException $ex){

            http_response_code(500);
            echo json_encode(["message" => $ex->getMessage()]);
        }
    }

    public function purchaseItem($itemId, $jsonData)
    {
        $stmt = $this->dbContext->prepare("SELECT * FROM items WHERE id = :id");
        $stmt->execute([
            ":id" => $itemId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row){
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "id" => $itemId,
                "status" => 404,
                "message" => "Item not found"
            ]);
        }
        else {
            $itemQuality = match ($row["quality"]) {
                "VeryGood" => ItemQuality::VeryGood,
                "Good" => ItemQuality::Good,
                "Mediocre" => ItemQuality::Mediocre,
                "Bad" => ItemQuality::Bad,
                "Rotten" => ItemQuality::Rotten,
                default => ItemQuality::UNKNOWN,
            };
            $item = new Item($row['id'], $row['name'], $row['quantity'], $itemQuality, $row['price']);

            $stmt = $this->dbContext->prepare("INSERT INTO purchases (id, item_id, price) VALUES (:id, :item_id, :price)");
            $stmt->execute([
                ":id" => bin2hex(random_bytes(16)),
                ":item_id" => $item->getId(),
                ":price" => $item->getPrice() * $jsonData['ammount'] ?? $item->getPrice()
            ]);

            // Modify the item table accordingly
            $stmt = $this->dbContext->prepare("UPDATE items SET name = :name, quantity = :quantity, quality = :quality, price = :price WHERE id = :id");
            $stmt->execute([
                ":id" => $item->getId(),
                ":name" => $item->getName(),
                ":quantity" => $item->getQuantity() - $jsonData['ammount'],
                ":quality" => $item->getQuality()->name,
                ":price" => $item->getPrice()
            ]);

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "successfully purchased item"
            ]);
        }
    }

    public function deleteItem($itemId): void
    {
        $stmt = $this->dbContext->prepare("DELETE FROM items WHERE id = :id");
        $stmt->execute([
            ":id" => $itemId
        ]);
        http_response_code(204);
        echo json_encode([]);
    }

    public function updateItem($itemId, $jsonData): void
    {
        $result = ItemValidator::validate($jsonData);
        if(!$result){
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "status" => 400,
                "message" => "Bad Request"
            ]);
        }

        //Checking if an item exists

        $stmt = $this->dbContext->prepare("SELECT * FROM items WHERE id = :id");
        $stmt->execute([
            ":id" => $itemId
        ]);

        if(!$stmt->fetch(PDO::FETCH_ASSOC)){

            $itemQuality = match ($jsonData["quality"]) {
                "VeryGood" => ItemQuality::VeryGood,
                "Good" => ItemQuality::Good,
                "Mediocre" => ItemQuality::Mediocre,
                "Bad" => ItemQuality::Bad,
                "Rotten" => ItemQuality::Rotten,
                default => ItemQuality::UNKNOWN,
            };

            //if the item doesn't exist, we create it
            $item = new Item($itemId, $jsonData['name'], $jsonData['quantity'], $itemQuality, $jsonData['price']);

            $stmt = $this->dbContext->prepare("INSERT INTO items (id, name, quantity, quality, price) VALUES (:id, :name, :quantity, :quality, :price)");
            $stmt->execute([
                ":id" => $item->getId(),
                ":name" => $item->getName(),
                ":quantity" => $item->getQuantity(),
                ":quality" => $item->getQuality()->name,
                ":price" => $item->getPrice(),
            ]);

            http_response_code(200);
            echo json_encode([
                "message" => "Item created",
                "item" => [
                    "id" => $item->getId(),
                    "name" => $item->getName(),
                    "quantity" => $item->getQuantity(),
                    "quality" => $item->getQuality()->name,
                    "price" => $item->getPrice()
                ]]);
        }
        else
        {
            $itemQuality = match ($jsonData["quality"]) {
                "VeryGood" => ItemQuality::VeryGood,
                "Good" => ItemQuality::Good,
                "Mediocre" => ItemQuality::Mediocre,
                "Bad" => ItemQuality::Bad,
                "Rotten" => ItemQuality::Rotten,
                default => ItemQuality::UNKNOWN,
            };

            $item = new Item($itemId, $jsonData['name'], $jsonData['quantity'], $itemQuality, $jsonData['price']);
            $stmt = $this->dbContext->prepare("UPDATE items SET name = :name, quantity = :quantity, quality = :quality, price = :price WHERE id = :id");
            $stmt->execute([
                ":id" => $item->getId(),
                ":name" => $item->getName(),
                ":quantity" => $item->getQuantity(),
                ":quality" => $item->getQuality()->name,
                ":price" => $item->getPrice()
            ]);

            http_response_code(204);
            echo json_encode([]);
        }
    }

    public function markedAsRotten($itemId): void{
        $stmt = $this->dbContext->prepare("SELECT * FROM items WHERE id = :id");
        $stmt->execute([
            ":id" => $itemId
        ]);

        if(!$stmt->fetch(PDO::FETCH_ASSOC)){
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "id" => $itemId,
                "status" => 404,
                "message" => "Item not found"
            ]);
        }
        else {
            $stmt = $this->dbContext->prepare("UPDATE items SET quality = :quality WHERE id = :id");
            $stmt->execute([
                ":id" => $itemId,
                ":quality" => ItemQuality::Rotten->name
            ]);

            http_response_code(204);
            echo json_encode([]);
        }
    }

    public function clearRotten(): void{
        $stmt = $this->dbContext->prepare("DELETE FROM items WHERE quality = :quality");
        $stmt->execute([
            ":quality" => ItemQuality::Rotten->name
        ]);
        http_response_code(204);
        echo json_encode([]);
    }
}