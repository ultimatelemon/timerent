<?php

namespace App\Enums;

enum TicketTypes: string
{
    case SOFTWARE_ISSUE = 'software_issue';
    case QUESTION = 'question';
    case FEEDBACK = 'feedback';
    case CRITICAL = 'critical';

}