<?php
class CurrentDirection {
    public const EAST = 'East';
    public const NORTH = 'North';
    public const SOUTH = 'South';
    public const WEST = 'West';

    protected $action;
    protected $currentDirection = self::NORTH;
    protected $positionX = 0;
    protected $positionY = 0;
    protected $movementFormat;

    public function __construct(array $movementFormat)
    {
        $this->movementFormat = $movementFormat;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getCurrentDirection(): string
    {
        return $this->currentDirection;
    }

    public function getMovementFormat(): array
    {
        return $this->movementFormat;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    public function setCurrentDirection(string $direction): void
    {
        $this->currentDirection = $direction;
    }

    public function setAction($action): void
    {
        $this->action = $action;
    }

    public function setPositionX(int $position): void
    {
        $this->positionX += $position;
    }

    public function setPositionY(int $position): void
    {
        $this->positionY += $position;
    }

    public function getResult(): string
    {
        foreach ($this->getMovementFormat() as $action) {
            $this->setAction($action);
            $this->calculateDirection();
            $this->calculatePosition();
        }

        return sprintf(
            'X: %d Y: %d Direction: %s',
            $this->getPositionX(),
            $this->getPositionY(),
            $this->getCurrentDirection(),
        );
    }

    private function calculateDirection(): void
    {
        if (Movement::LEFT_MOVEMENT === $this->getAction()) {
            switch ($this->getCurrentDirection()) {
                case self::NORTH:
                    $this->setCurrentDirection(self::WEST);
                    break;
                case self::WEST:
                    $this->setCurrentDirection(self::SOUTH);
                    break;
                case self::SOUTH:
                    $this->setCurrentDirection(self::EAST);
                    break;
                case self::EAST:
                    $this->setCurrentDirection(self::NORTH);
                    break;
            }
        } elseif (Movement::RIGHT_MOVEMENT === $this->getAction()) {
            switch ($this->getCurrentDirection()) {
                case self::NORTH:
                    $this->setCurrentDirection(self::EAST);
                    break;
                case self::WEST:
                    $this->setCurrentDirection(self::NORTH);
                    break;
                case self::SOUTH:
                    $this->setCurrentDirection(self::WEST);
                    break;
                case self::EAST:
                    $this->setCurrentDirection(self::SOUTH);
                    break;
            }
        }
    }

    private function calculatePosition(): void
    {
        if (is_numeric($this->getAction())) {
            switch ($this->getCurrentDirection()) {
                case self::NORTH:
                    $this->setPositionY(abs($this->getAction()));
                    break;
                case self::WEST:
                    $this->setPositionX(- abs($this->getAction()));
                    break;
                case self::SOUTH:
                    $this->setPositionY(- abs($this->getAction()));
                    break;
                case self::EAST:
                    $this->setPositionX(abs($this->getAction()));
                    break;
            }
        }
    }
}

class Movement {
    public const LEFT_MOVEMENT = 'L';
    public const RIGHT_MOVEMENT = 'R';
    public const WALK_MOVEMENT = 'W';

    protected $allowedMovement = [self::LEFT_MOVEMENT, self::RIGHT_MOVEMENT, self::WALK_MOVEMENT];
    protected $movement;
    protected $runningNumber;

    public function __construct(string $movement)
    {
        $this->movement = $movement;
    }

    public function getAllowedMovement(): array
    {
        return $this->allowedMovement;
    }

    public function getMovement(): string
    {
        return $this->movement;
    }

    public function getRunningNumber(): ?string
    {
        return $this->runningNumber;
    }

    public function setRunningNumber(string $value = null): void
    {
        if (null === $value) {
            $this->runningNumber = null;

            return;
        }

        $this->runningNumber .= $value;
    }

    public function toArray(): array
    {
        $arrayFormat = array();
        $countString = strlen($this->getMovement());

        for ($index = 0; $index < $countString; $index++)
        {
            $char = substr($this->getMovement(), $index, 1);

            if (is_numeric($char)) {
                $this->setRunningNumber($char);

                if ($index + 1 === $countString) {
                    $arrayFormat[$index] =  $this->getRunningNumber();
                }

                continue;
            } elseif (in_array($char, $this->getAllowedMovement()) && null !== $this->getRunningNumber()) {
                $arrayFormat[$index - 1] =  $this->getRunningNumber();
                $arrayFormat[$index] =  $char;
                $this->setRunningNumber();

                continue;
            }

            $arrayFormat[$index] =  $char;
        }

        return array_values($arrayFormat);
    }
}

$movementFormat = New Movement($argv[1]);
$currentDirection = New CurrentDirection($movementFormat->toArray());

echo $currentDirection->getResult();