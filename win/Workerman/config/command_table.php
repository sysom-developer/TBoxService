<?php

/**
 * 命令表
 */


return [
    'POSITION_DATA' => require_once __DIR__ . '/command/POSITION_DATA.php',
    'VEHICLE_DATA' => require_once __DIR__ . '/command/VEHICLE_DATA.php',
    'NEW_DATA_MINING_INFO'=>require_once __DIR__ . '/command/NEW_DATA_MINING_INFO.php',
    'Truck_Information' => require_once __DIR__ . '/command/Truck_Information.php',
    'Backup_Truck_Information' => require_once __DIR__ . '/command/Backup_Truck_Information.php',
    'Event_Report' => require_once __DIR__ . '/command/Event_Report.php',
    'GSM_Location' => require_once __DIR__ . '/command/GSM_Location.php',
    'Device_Information' => require_once __DIR__ . '/command/Device_Information.php',
    'Sleep_Voltage_Record' => require_once __DIR__ . '/command/Sleep_Voltage_Record.php',
    'Truck_Acceleration_Information' => require_once __DIR__ . '/command/Truck_Acceleration_Information.php',

];