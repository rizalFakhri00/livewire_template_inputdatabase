<?php

namespace App\Livewire;

use Livewire\Component;

class MergeGame extends Component
{

    public array $items = [
        1 => [
            'id' => 1,
            'name' => 'Goblin Assassin',
            'rarity' => 'R',
            'base_price' => 2,
            'base_attack' => 18,
            'base_hp' => 60,
        ],
        2 => [
            'id' => 2,
            'name' => 'Goblin Spear',
            'rarity' => 'R',
            'base_price' => 2,
            'base_attack' => 14,
            'base_hp' => 70,
        ],
        3 => [
            'id' => 3,
            'name' => 'Barbarian',
            'rarity' => 'R',
            'base_price' => 2,
            'base_attack' => 16,
            'base_hp' => 100,
        ],
        4 => [
            'id' => 4,
            'name' => 'Valkyrie',
            'rarity' => 'SR',
            'base_price' => 3,
            'base_attack' => 28,
            'base_hp' => 220,
        ],
        5 => [
            'id' => 5,
            'name' => 'Executioner',
            'rarity' => 'SR',
            'base_price' => 3,
            'base_attack' => 32,
            'base_hp' => 180,
        ],
        6 => [
            'id' => 6,
            'name' => 'Ghost Royale',
            'rarity' => 'SR',
            'base_price' => 3,
            'base_attack' => 30,
            'base_hp' => 160,
        ],
        7 => [
            'id' => 7,
            'name' => 'Archer Queen',
            'rarity' => 'SSR',
            'base_price' => 5,
            'base_attack' => 55,
            'base_hp' => 320,
        ],
        8 => [
            'id' => 8,
            'name' => 'Knight Royale',
            'rarity' => 'SSR',
            'base_price' => 5,
            'base_attack' => 45,
            'base_hp' => 420,
        ],
        9 => [
            'id' => 9,
            'name' => 'Witch',
            'rarity' => 'SSR',
            'base_price' => 5,
            'base_attack' => 40,
            'base_hp' => 300,
        ],
    ];

    // public $firstRoll = $this->rollRandomItem();

    
    public $inventory = [
        [
            'item_id' => 1,
            'star' => 1,
            'amount' => 1,
        ],
    ];

    public $myGold = 100;
    

    public array $lastRolls = [];

    function findInventoryIndex($itemId, $star)
    {
        foreach ($this->inventory as $index => $row) {
            if ($row['item_id'] === $itemId && $row['star'] === $star) {
                return $index;
            }
        }
        return null;
    }

    function getSellPrice(array $item, int $star): int
    {
        if($star == 1){
            return $item["base_price"] - 1;
        }
        return ($item['base_price'] * pow(2,$star - 1)) - 1;
    }

    private function buyHero(array $item)
    {
        $this->myGold -= $item['base_price'];
    }
    

    function addItem($itemId, $star = 1)
    {
        $index = $this->findInventoryIndex($itemId, $star);

        if ($index !== null) {
            $this->inventory[$index]['amount']++;
            $this->autoFuseItem();
        } else {
            $this->inventory[] = [
                'item_id' => $itemId,
                'star' => $star,
                'amount' => 1,
            ];
        }
    }

    public function scaleStat(int $base, int $star): int
    {
        return (int) round($base * (1 + ($star - 1) * 0.25));
    }

    public function fuseItem(int $itemId, int $star)
    {
        foreach ($this->inventory as $index => $inv) {
            if ($inv['item_id'] === $itemId && $inv['star'] === $star) {

                if ($inv['amount'] < 2 || $star >= 4) {
                    return;
                }

                // kurangi 2
                $this->inventory[$index]['amount'] -= 2;

                // hapus kalau 0
                if ($this->inventory[$index]['amount'] === 0) {
                    unset($this->inventory[$index]);
                    $this->inventory = array_values($this->inventory);
                }

                // tambah star + 1
                $this->addItem($itemId, $star + 1);
                $this->myGold += 1;

                break;
            }
        }
    }

    public function autoFuseItem(){
        foreach($this->inventory as $inv){
            $foundedItem = $this->findInventoryIndex($inv["item_id"], $inv["star"]);
            // dd($inv["star"]);

            if($foundedItem !== null){
                $this->fuseItem($inv["item_id"],$inv["star"]);
            }
        }
    }


    public function sellItem(int $itemId, int $star)
    {
        foreach ($this->inventory as $i => $inv) {
            if ($inv['item_id'] === $itemId && $inv['star'] === $star) {

                $price = $this->getSellPrice($this->items[$itemId], $star);

                $this->myGold += $price;

                $this->inventory[$i]['amount']--;

                if ($this->inventory[$i]['amount'] === 0) {
                    unset($this->inventory[$i]);
                    $this->inventory = array_values($this->inventory);
                }

                break;
            }
        }
    }


    private function rollRandomItem()
    {
        $itemIds = array_keys($this->items);

        $randomItemId = $itemIds[array_rand($itemIds)];

        // return [
        //     'item_id' => $randomItemId,
        //     // 'star' => 1, // default dari gacha
        // ];
        return $randomItemId;
    }

    public function rollGachaFourTimes(): void
    {
        $this->lastRolls = [];

        for ($i = 0; $i < 4; $i++) {
            $roll = $this->rollRandomItem();

            // $this->addItem($roll['item_id'], $roll['star']);

            $this->lastRolls[] = [
                "item_id" => $roll
            ];
        }
        $this->myGold -= 2;
    }

    public function confirmRoll(int $itemId,$star = 1): void
    {
        // 1. beli dan Tambah item ke inventory
        $this->buyHero($this->items[$itemId]);
        $this->addItem($itemId);

        // $ifAutoFuse = $this->findInventoryIndex($itemId,$star);

        // if($ifAutoFuse !== null){
        //     $this->fuseItem($itemId,$star);
        // }

        // $this->autoFuseItem();

        // 2. Roll gacha 4x lagi
        $this->rollGachaFourTimes();
    }

    public function isWinner(): bool
    {
        $ssrCount = 0;

        foreach ($this->inventory as $roll) {
            $itemId = $roll['item_id'];
            $itemStar = $roll['star'];

            if ($this->items[$itemId]['rarity'] === 'SSR' && $itemStar == 4) {
                $ssrCount++;
            }

            if ($ssrCount >= 2) {
                return true;
            }
        }

        return false;
    }



    public function render()
    {
        return view('livewire.merge-game');
    }
}
