<div>
    @php
        $totalAttack = 0;
        $totalHp = 0;

        foreach ($inventory as $inv) {
            $base = $items[$inv['item_id']]['base_attack'];
            $baseHp = $items[$inv['item_id']]['base_hp'];
            $attack = $this->scaleStat($base,$inv["star"]);
            $totalAttack += $attack * $inv['amount'];

            $hitPoint = $this->scaleStat($baseHp,$inv["star"]);
            $totalHp += $hitPoint * $inv['amount'];
        }
    @endphp

    <div>My Gold : {{ $myGold }}</div>
    <div>Total Attack : {{ (int)$totalAttack }}</div>
    <div>Total Hp : {{ (int)$totalHp }}</div>
    <hr>
    @if ($this->isWinner())
        <div class="winner">
            🎉 WINNER 🎉
        </div>
    @endif
    @foreach ($inventory as $inv)
        @php
            $item = $items[$inv['item_id']];
            $sellPrice = $this->getSellPrice($item,$inv["star"]);
            $hitpoint = $this->scaleStat($item["base_hp"],$inv["star"]);
            $attack = $this->scaleStat($item["base_attack"],$inv["star"]);
        @endphp

        <div>
            <strong>{{ $item['name'] }} | Attack : {{ $attack }}, HP : {{ $hitpoint }}</strong>
            @for ($i = 0; $i < $inv['star']; $i++)
                ⭐
            @endfor

            <div>x{{ $inv['amount'] }}</div>

            @if ($inv['amount'] >= 2)
                <button wire:click="fuseItem({{ $inv['item_id'] }}, {{ $inv['star'] }})">
                    Fusion
                </button>
            @endif
            <button wire:click="sellItem({{ $inv['item_id'] }}, {{ $inv['star'] }})">
                Sell Price : {{ $sellPrice }}
            </button>
        </div>
    @endforeach

    <hr>
    <button wire:click="addItem(4,2)">
        Add Item
    </button>
    <button wire:click="rollGachaFourTimes()">
        ROLLS BABY
    </button>

    <hr>
    @if ($lastRolls)
        <h4>Result</h4>
        @foreach ($lastRolls as $roll)
            <button
                wire:click="confirmRoll({{ $roll['item_id'] }})"
                @disabled($myGold < $items[$roll['item_id']]['base_price'] || count($inventory) > 4)

            >
                {{ $items[$roll['item_id']]['name'] }}
                ({{ $items[$roll['item_id']]['base_price'] }})
            </button>
        @endforeach
    @endif


</div>
