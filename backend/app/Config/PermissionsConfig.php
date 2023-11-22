<?php

namespace App\Config;
class PermissionsConfig {
    public const ROLES = [
        self::CLIENT_ROLE,
        self::SERVICE_EMPLOYEE_ROLE,
        self::SERVICE_ADMIN_ROLE,
        self::SYSTEM_ADMIN,
    ];

    public const CLIENT_PERMISSIONS = [
        ...self::CARS_PERMISSIONS,
        self::CAN_LIST_APPOINTMENTS,
        self::CAN_VIEW_APPOINTMENT,
        self::CAN_CREATE_APPOINTMENT,
        self::CAN_LIST_SERVICES,
        self::CAN_VIEW_SERVICE,
        self::CAN_LIST_RECORDS,
        self::CAN_VIEW_RECORD,
    ];
    public const SERVICE_EMPLOYEE_PERMISSIONS = [
        ...self::CARS_PERMISSIONS,
        ...self::APPOINTMENTS_PERMISSIONS,
        self::CAN_LIST_SERVICES,
        self::CAN_VIEW_SERVICE,
        ...self::RECORDS_PERMISSIONS,
    ];
    public const SERVICE_ADMIN_PERMISSIONS = [];
    public const SYSTEM_ADMIN_PERMISSIONS = [
        ...self::USERS_PERMISSIONS,
        ...self::CARS_PERMISSIONS,
        ...self::APPOINTMENTS_PERMISSIONS,
        ...self::SERVICES_PERMISSIONS,
        ...self::RECORDS_PERMISSIONS,
    ];

    public const USERS_PERMISSIONS = [
        self::CAN_LIST_USERS,
        self::CAN_VIEW_USER,
        self::CAN_CREATE_USER,
        self::CAN_EDIT_USER,
        self::CAN_DELETE_USER,
    ];
    public const CARS_PERMISSIONS = [
        self::CAN_LIST_CARS,
        self::CAN_VIEW_CAR,
        self::CAN_CREATE_CAR,
        self::CAN_EDIT_CAR,
        self::CAN_DELETE_CAR,
    ];
    public const APPOINTMENTS_PERMISSIONS = [
        self::CAN_LIST_APPOINTMENTS,
        self::CAN_VIEW_APPOINTMENT,
        self::CAN_CREATE_APPOINTMENT,
        self::CAN_EDIT_APPOINTMENT,
        self::CAN_DELETE_APPOINTMENT,
    ];
    public const SERVICES_PERMISSIONS = [
        self::CAN_LIST_SERVICES,
        self::CAN_VIEW_SERVICE,
        self::CAN_CREATE_SERVICE,
        self::CAN_EDIT_SERVICE,
        self::CAN_DELETE_SERVICE,
    ];

    public const RECORDS_PERMISSIONS = [
        self::CAN_LIST_RECORDS,
        self::CAN_VIEW_RECORD,
        self::CAN_CREATE_RECORD,
        self::CAN_EDIT_RECORD,
        self::CAN_DELETE_RECORD,
    ];

    public const CLIENT_ROLE = 'client';
    public const SERVICE_EMPLOYEE_ROLE = 'service_employee';
    public const SERVICE_ADMIN_ROLE = 'service_admin';
    public const SYSTEM_ADMIN = 'system_admin';

    public const CAN_LIST_USERS = 'can_list_users';
    public const CAN_VIEW_USER = 'can_view_user';
    public const CAN_CREATE_USER = 'can_create_user';
    public const CAN_EDIT_USER = 'can_edit_user';
    public const CAN_DELETE_USER = 'can_delete_user';

    public const CAN_LIST_CARS = 'can_list_cars';
    public const CAN_VIEW_CAR = 'can_view_car';
    public const CAN_CREATE_CAR = 'can_create_car';
    public const CAN_EDIT_CAR = 'can_edit_car';
    public const CAN_DELETE_CAR = 'can_delete_car';

    public const CAN_LIST_APPOINTMENTS = 'can_list_appointments';
    public const CAN_VIEW_APPOINTMENT = 'can_view_appointment';
    public const CAN_CREATE_APPOINTMENT = 'can_create_appointment';
    public const CAN_EDIT_APPOINTMENT = 'can_edit_appointment';
    public const CAN_DELETE_APPOINTMENT = 'can_delete_appointment';

    public const CAN_LIST_SERVICES = 'can_list_services';
    public const CAN_VIEW_SERVICE = 'can_view_service';
    public const CAN_CREATE_SERVICE = 'can_create_service';
    public const CAN_EDIT_SERVICE = 'can_edit_service';
    public const CAN_DELETE_SERVICE = 'can_delete_service';

    public const CAN_LIST_RECORDS = 'can_list_records';
    public const CAN_VIEW_RECORD = 'can_view_record';
    public const CAN_CREATE_RECORD = 'can_create_record';
    public const CAN_EDIT_RECORD = 'can_edit_record';
    public const CAN_DELETE_RECORD = 'can_delete_record';
}
