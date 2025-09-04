<?php
declare(strict_types=1);

namespace Core;

use PDO;

class Model
{
    public function __construct(protected ?PDO $db = null)
    {
        $this->db ??= Database::getInstance();
    }
}
