<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case PENDING_CUSTOMER_RESPONSE = 'pending_customer_response';
    case PENDING_EMPLOYEE_RESPONSE = 'pending_employee_response';
    case SOLVED = 'solved';
    case ESCALATED = 'escalated';
    case ON_HOLD = 'on_hold';
    case CANCELLED = 'cancelled';
    case REOPENED_BY_EMPLOYEE = 'reopened_by_employee';
    case REOPENED_BY_CUSTOMER = 'reopened_by_customer';

}