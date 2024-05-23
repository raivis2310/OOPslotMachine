<?php

class Player
{
    private int $coins;

    public function __construct($coins)
    {
        $this->coins = $coins;
    }

    public function getCoins(): int
    {
        return $this->coins;
    }

    public function addCoins($money)
    {
        $this->coins += $money;
    }

    public function minusCoins($cash)
    {
        $this->coins -= $cash;
    }
}

class Game
{
    private array $board;
    private array $elements;
    private array $coordinates;
    private Player $player;

    public function __construct(Player $player, $rows = 3, $columns = 5)
    {
        $this->player = $player;
        $this->elements = ["@", "#", "$", "&", "*", "A", "K"];
        $this->coordinates = [
            [[0, 0], [0, 1], [0, 2], [0, 3], [0, 4]],
            [[1, 0], [1, 1], [1, 2], [1, 3], [1, 4]],
            [[2, 0], [2, 1], [2, 2], [2, 3], [2, 4]],
            [[0, 0], [1, 1], [2, 2], [1, 3], [0, 4]],
            [[2, 0], [1, 1], [0, 2], [1, 3], [0, 4]]
        ];
        $this->board = [];
        $this->createBoard($rows, $columns);
    }

    private function createBoard($rows, $columns)
    {
        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $columns; $column++) {
                $this->board[$row][$column] = $this->elements[array_rand($this->elements)];
            }
        }
    }

    public function showBoard()
    {
        foreach ($this->board as $row) {
            foreach ($row as $symbol) {
                echo "$symbol" . "  ";
            }
            echo PHP_EOL;
        }
    }

    public function checkCombinations($stake)
    {
        $matchFound = false;
        foreach ($this->coordinates as $index => $coordinate) {
            $firstElement = $coordinate[0];
            $symbol = $this->board[$firstElement[0]][$firstElement[1]];
            $match = true;
            foreach ($coordinate as $c) {
                [$row, $column] = $c;
                if ($this->board[$row][$column] !== $symbol) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                echo "Match found for combination $index with symbol $symbol" . PHP_EOL;
                $this->player->addCoins($stake * 5);
                $matchFound = true;
            }
        }

        if (!$matchFound) {
            echo "You lose!" . PHP_EOL;
            $this->player->minusCoins($stake);
        }
    }

    public function play()
    {
        while ($this->player->getCoins() > 0) {
            $this->showBoard();
            $stake = (int)readline("Enter your stake: ");

            if ($stake > $this->player->getCoins()) {
                echo "You dont have enough coins." . PHP_EOL;
                continue;
            }

            $this->checkCombinations($stake);
            echo "Your coin balance is: " . $this->player->getCoins() . PHP_EOL;

            if ($this->player->getCoins() <= 0) {
                echo "You lose all coins." . PHP_EOL;
                break;
            }

            $continue = strtolower(readline("Do you want to play again? (yes/no): "));
            if ($continue !== 'yes' && $continue !== 'y') {
                break;
            }
        }
    }
}

$howManyCoins = (int)readline("How many coins do you want to add?: ");
$player = new Player($howManyCoins);
$game = new Game($player);

$game->play();

