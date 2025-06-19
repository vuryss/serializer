<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

enum ShipmentActivityType: string
{
    case TYPE_01 = '01';
    case TYPE_02 = '02';
    case TYPE_03 = '03';
    case TYPE_04 = '04';
    case TYPE_05 = '05';
    case TYPE_06 = '06';
    case TYPE_07 = '07';
    case TYPE_08 = '08';
    case TYPE_09 = '09';
    case TYPE_10 = '10';
    case TYPE_11 = '11';
    case TYPE_12 = '12';
    case TYPE_13 = '13';
    case TYPE_14 = '14';
    case TYPE_15 = '15';
    case TYPE_16 = '16';
    case TYPE_18 = '18';
    case TYPE_19 = '19';
    case TYPE_20 = '20';
    case TYPE_21 = '21';
    case TYPE_22 = '22';
    case TYPE_29 = '29';
    case TYPE_30 = '30';
    case TYPE_31 = '31';
    case TYPE_32 = '32';
    case TYPE_33 = '33';
    case TYPE_34 = '34';
    case TYPE_35 = '35';
    case TYPE_36 = '36';
    case TYPE_38 = '38';

    case ACTIVITY_A = 'activity_a';
    case ACTIVITY_B = 'activity_b';
    case ACTIVITY_C = 'activity_c';
    case ACTIVITY_D = 'activity_d';
    case ACTIVITY_E = 'activity_e';
    case ACTIVITY_F = 'activity_f';
    case ACTIVITY_G = 'activity_g';

    case CATEGORY_X_TYPE_1 = 'CAT_X_1';
    case CATEGORY_X_TYPE_2 = 'CAT_X_2';
    case CATEGORY_X_TYPE_3 = 'CAT_X_3';
    case CATEGORY_X_TYPE_4 = 'CAT_X_4';
    case CATEGORY_X_TYPE_50 = 'CAT_X_50';
    case CATEGORY_X_TYPE_51 = 'CAT_X_51';
    case CATEGORY_X_TYPE_52 = 'CAT_X_52';
    case CATEGORY_X_TYPE_53 = 'CAT_X_53';
    case CATEGORY_X_TYPE_54 = 'CAT_X_54';
    case CATEGORY_X_TYPE_55 = 'CAT_X_55';
    case CATEGORY_X_TYPE_56 = 'CAT_X_56';
    case CATEGORY_X_TYPE_57 = 'CAT_X_57';
    case CATEGORY_X_TYPE_58 = 'CAT_X_58';
    case CATEGORY_X_TYPE_59 = 'CAT_X_59';
    case CATEGORY_X_TYPE_71 = 'CAT_X_71';
    case CATEGORY_X_TYPE_91 = 'CAT_X_91';

    case CATEGORY_Y_TYPE_50 = 'CAT_Y_50';

    case ACTION_CHG = 'chng';
    case ACTION_TRA = 'tra';
    case ACTION_PLC = 'plc';
    case ACTION_REP = 'rep';
    case ACTION_RET = 'ret';
    case ACTION_MOV = 'mov';
    case ACTION_REM = 'rem';
    case ACTION_CHNGIT = 'chngit';
    case ACTION_PLCIT = 'plcit';
    case ACTION_REPIT = 'repit';
    case ACTION_RETIT = 'retit';
    case ACTION_LIMIT = 'limit';
    case ACTION_REMIT = 'remit';
}
