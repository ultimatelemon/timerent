<?php
namespace App\Utils;

class Permissions extends BitField
{
    public static array $flags = [
        'ADMINISTRATOR' => 1 << 0, // 1
        'VIEW_UNITS' => 1 << 1, // 2
        'MANAGE_UNITS' => 1 << 2, // 4
        'VIEW_TEMPLATES' => 1 << 3, // 8
        'MANAGE_TEMPLATES' => 1 << 4, // 16
        'VIEW_AGENDA' => 1 << 5, // 32
        'MANAGE_AGENDA' => 1 << 6, // 64
        'VIEW_PRODUCTS' => 1 << 7, // 128
        'MANAGE_PRODUCTS' => 1 << 8, // 256
        'VIEW_RESERVATIONS' => 1 << 9, // 512
        'MANAGE_RESERVATIONS' => 1 << 10, // 1024
        'VIEW_SETTINGS' => 1 << 11, // 2048
        'MANAGE_SETTINGS' => 1 << 12, // 4096
        'VIEW_REPORTS' => 1 << 13, // 8192
        'MANAGE_REPORTS' => 1 << 14, // 16383
        'VIEW_INVOICES' => 1 << 15, // 32768
        'MANAGE_INVOICES' => 1 << 16, // 65536,
        'VIEW_EMPLOYEES' => 1 << 17, // 131072
        'MANAGE_EMPLOYEES' => 1 << 18, // 262144
        'VIEW_MEMBERS' => 1 << 19, // 524288
        'MANAGE_MEMBERS' => 1 << 20, // 1048576
        'VIEW_ROLES' => 1 << 21, // 2097152
        'MANAGE_ROLES' => 1 << 22, // 4194304
        'VIEW_GROUPS' => 1 << 23, // 8388608
        'MANAGE_GROUPS' => 1 << 24, // 16777216
//        'VIEW_PLANS' => 1 << 3, // 8
//        'MANAGE_PLANS' => 1 << 4, // 16
//        'VIEW_TICKETS' => 1 << 7, // 128
//        'MANAGE_TICKETS' => 1 << 8, // 256
//        'VIEW_COUPONS' => 1 << 9, // 512
//        'MANAGE_COUPONS' => 1 << 10, // 1024
    ];

    public function __construct(int $bits)
    {
        parent::__construct($bits);
    }

    /**|
     * Check if permission flag equals bit-field
     *
     * @param string $permission
     * @return bool
     */
    public function has(string $permission): bool
    {
        return $this->hasBit(self::$flags[$permission]);
    }

    /**
     * Check available permissions with bit-field
     *
     * @return array
     */
    public function available(): array
    {
        $available = [];
        foreach (self::$flags as $key => $flag)
        {
            if ($this->hasBit($flag))
                $available[] = $key;
        }

        return $available;
    }

    public static function create(array $permissions): int
    {
        $value = 0;
        foreach ($permissions as $permission)
        {
            $value += (self::$flags[$permission]);
        }

        return $value;
    }

}
