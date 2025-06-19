<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

enum JobGroupType: string
{
    case TYPE_CLIENT = 'CLIENT';
    case TYPE_HUB = 'HUB';
    case TYPE_OFFICE = 'OFFICE';
    case TYPE_FACILITY = 'FACILITY';
    case TYPE_DECISION = 'DECISION';
    case TYPE_PAUSE = 'PAUSE';
    case TYPE_END = 'END';
    case TYPE_MEMO = 'MEMO';
    case TYPE_ROUTE = 'ROUTE';
    case TYPE_PERSONNEL = 'PERSONNEL';
    case TYPE_ASSET = 'ASSET';
    case TYPE_INSPECTION = 'INSPECTION';

    case CATEGORY_A_STAGE_1 = 'CATEGORY_A_STAGE_1';
    case CATEGORY_A_STAGE_2 = 'CATEGORY_A_STAGE_2';
    case CATEGORY_A_STAGE_3 = 'CATEGORY_A_STAGE_3';

    case FACILITY_TEMPLATE = 'FACILITY-TEMPLATE';
}
