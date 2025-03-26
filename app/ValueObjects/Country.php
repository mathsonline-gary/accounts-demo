<?php

namespace App\ValueObjects;

class Country
{
    protected const COUNTRIES = [
        'AF' => [
            'name' => 'Afghanistan',
            'code_alpha_three' => 'AFG',
            'code_alpha_two' => 'AF',
        ],
        'AL' => [
            'name' => 'Albania',
            'code_alpha_three' => 'ALB',
            'code_alpha_two' => 'AL',
        ],
        'DZ' => [
            'name' => 'Algeria',
            'code_alpha_three' => 'DZA',
            'code_alpha_two' => 'DZ',
        ],
        'AS' => [
            'name' => 'American Samoa',
            'code_alpha_three' => 'ASM',
            'code_alpha_two' => 'AS',
        ],
        'AD' => [
            'name' => 'Andorra',
            'code_alpha_three' => 'AND',
            'code_alpha_two' => 'AD',
        ],
        'AO' => [
            'name' => 'Angola',
            'code_alpha_three' => 'AGO',
            'code_alpha_two' => 'AO',
        ],
        'AI' => [
            'name' => 'Anguilla',
            'code_alpha_three' => 'AIA',
            'code_alpha_two' => 'AI',
        ],
        'AQ' => [
            'name' => 'Antarctica',
            'code_alpha_three' => 'ATA',
            'code_alpha_two' => 'AQ',
        ],
        'AG' => [
            'name' => 'Antigua and Barbuda',
            'code_alpha_three' => 'ATG',
            'code_alpha_two' => 'AG',
        ],
        'AR' => [
            'name' => 'Argentina',
            'code_alpha_three' => 'ARG',
            'code_alpha_two' => 'AR',
        ],
        'AM' => [
            'name' => 'Armenia',
            'code_alpha_three' => 'ARM',
            'code_alpha_two' => 'AM',
        ],
        'AW' => [
            'name' => 'Aruba',
            'code_alpha_three' => 'ABW',
            'code_alpha_two' => 'AW',
        ],
        'AU' => [
            'name' => 'Australia',
            'code_alpha_three' => 'AUS',
            'code_alpha_two' => 'AU',
        ],
        'AT' => [
            'name' => 'Austria',
            'code_alpha_three' => 'AUT',
            'code_alpha_two' => 'AT',
        ],
        'AZ' => [
            'name' => 'Azerbaijan',
            'code_alpha_three' => 'AZE',
            'code_alpha_two' => 'AZ',
        ],
        'BS' => [
            'name' => 'Bahamas',
            'code_alpha_three' => 'BHS',
            'code_alpha_two' => 'BS',
        ],
        'BH' => [
            'name' => 'Bahrain',
            'code_alpha_three' => 'BHR',
            'code_alpha_two' => 'BH',
        ],
        'BD' => [
            'name' => 'Bangladesh',
            'code_alpha_three' => 'BGD',
            'code_alpha_two' => 'BD',
        ],
        'BB' => [
            'name' => 'Barbados',
            'code_alpha_three' => 'BRB',
            'code_alpha_two' => 'BB',
        ],
        'BY' => [
            'name' => 'Belarus',
            'code_alpha_three' => 'BLR',
            'code_alpha_two' => 'BY',
        ],
        'BE' => [
            'name' => 'Belgium',
            'code_alpha_three' => 'BEL',
            'code_alpha_two' => 'BE',
        ],
        'BZ' => [
            'name' => 'Belize',
            'code_alpha_three' => 'BLZ',
            'code_alpha_two' => 'BZ',
        ],
        'BJ' => [
            'name' => 'Benin',
            'code_alpha_three' => 'BEN',
            'code_alpha_two' => 'BJ',
        ],
        'BM' => [
            'name' => 'Bermuda',
            'code_alpha_three' => 'BMU',
            'code_alpha_two' => 'BM',
        ],
        'BT' => [
            'name' => 'Bhutan',
            'code_alpha_three' => 'BTN',
            'code_alpha_two' => 'BT',
        ],
        'BO' => [
            'name' => 'Bolivia',
            'code_alpha_three' => 'BOL',
            'code_alpha_two' => 'BO',
        ],
        'BA' => [
            'name' => 'Bosnia and Herzegovina',
            'code_alpha_three' => 'BIH',
            'code_alpha_two' => 'BA',
        ],
        'BW' => [
            'name' => 'Botswana',
            'code_alpha_three' => 'BWA',
            'code_alpha_two' => 'BW',
        ],
        'BV' => [
            'name' => 'Bouvet Island',
            'code_alpha_three' => 'BVT',
            'code_alpha_two' => 'BV',
        ],
        'BR' => [
            'name' => 'Brazil',
            'code_alpha_three' => 'BRA',
            'code_alpha_two' => 'BR',
        ],
        'IO' => [
            'name' => 'British Indian Ocean Territory',
            'code_alpha_three' => 'IOT',
            'code_alpha_two' => 'IO',
        ],
        'VG' => [
            'name' => 'British Virgin Islands',
            'code_alpha_three' => 'VGB',
            'code_alpha_two' => 'VG',
        ],
        'BN' => [
            'name' => 'Brunei',
            'code_alpha_three' => 'BRN',
            'code_alpha_two' => 'BN',
        ],
        'BG' => [
            'name' => 'Bulgaria',
            'code_alpha_three' => 'BGR',
            'code_alpha_two' => 'BG',
        ],
        'BF' => [
            'name' => 'Burkina Faso',
            'code_alpha_three' => 'BFA',
            'code_alpha_two' => 'BF',
        ],
        'BI' => [
            'name' => 'Burundi',
            'code_alpha_three' => 'BDI',
            'code_alpha_two' => 'BI',
        ],
        'KH' => [
            'name' => 'Cambodia',
            'code_alpha_three' => 'KHM',
            'code_alpha_two' => 'KH',
        ],
        'CM' => [
            'name' => 'Cameroon',
            'code_alpha_three' => 'CMR',
            'code_alpha_two' => 'CM',
        ],
        'CA' => [
            'name' => 'Canada',
            'code_alpha_three' => 'CAN',
            'code_alpha_two' => 'CA',
        ],
        'CV' => [
            'name' => 'Cape Verde',
            'code_alpha_three' => 'CPV',
            'code_alpha_two' => 'CV',
        ],
        'BQ' => [
            'name' => 'Caribbean Netherlands',
            'code_alpha_three' => 'BES',
            'code_alpha_two' => 'BQ',
        ],
        'KY' => [
            'name' => 'Cayman Islands',
            'code_alpha_three' => 'CYM',
            'code_alpha_two' => 'KY',
        ],
        'CF' => [
            'name' => 'Central African Republic',
            'code_alpha_three' => 'CAF',
            'code_alpha_two' => 'CF',
        ],
        'TD' => [
            'name' => 'Chad',
            'code_alpha_three' => 'TCD',
            'code_alpha_two' => 'TD',
        ],
        'CL' => [
            'name' => 'Chile',
            'code_alpha_three' => 'CHL',
            'code_alpha_two' => 'CL',
        ],
        'CN' => [
            'name' => 'China',
            'code_alpha_three' => 'CHN',
            'code_alpha_two' => 'CN',
        ],
        'CX' => [
            'name' => 'Christmas Island',
            'code_alpha_three' => 'CXR',
            'code_alpha_two' => 'CX',
        ],
        'CC' => [
            'name' => 'Cocos (Keeling) Islands',
            'code_alpha_three' => 'CCK',
            'code_alpha_two' => 'CC',
        ],
        'CO' => [
            'name' => 'Colombia',
            'code_alpha_three' => 'COL',
            'code_alpha_two' => 'CO',
        ],
        'KM' => [
            'name' => 'Comoros',
            'code_alpha_three' => 'COM',
            'code_alpha_two' => 'KM',
        ],
        'CK' => [
            'name' => 'Cook Islands',
            'code_alpha_three' => 'COK',
            'code_alpha_two' => 'CK',
        ],
        'CR' => [
            'name' => 'Costa Rica',
            'code_alpha_three' => 'CRI',
            'code_alpha_two' => 'CR',
        ],
        'HR' => [
            'name' => 'Croatia',
            'code_alpha_three' => 'HRV',
            'code_alpha_two' => 'HR',
        ],
        'CU' => [
            'name' => 'Cuba',
            'code_alpha_three' => 'CUB',
            'code_alpha_two' => 'CU',
        ],
        'CW' => [
            'name' => 'Curaçao',
            'code_alpha_three' => 'CUW',
            'code_alpha_two' => 'CW',
        ],
        'CY' => [
            'name' => 'Cyprus',
            'code_alpha_three' => 'CYP',
            'code_alpha_two' => 'CY',
        ],
        'CZ' => [
            'name' => 'Czechia',
            'code_alpha_three' => 'CZE',
            'code_alpha_two' => 'CZ',
        ],
        'CD' => [
            'name' => 'DR Congo',
            'code_alpha_three' => 'COD',
            'code_alpha_two' => 'CD',
        ],
        'DK' => [
            'name' => 'Denmark',
            'code_alpha_three' => 'DNK',
            'code_alpha_two' => 'DK',
        ],
        'DJ' => [
            'name' => 'Djibouti',
            'code_alpha_three' => 'DJI',
            'code_alpha_two' => 'DJ',
        ],
        'DM' => [
            'name' => 'Dominica',
            'code_alpha_three' => 'DMA',
            'code_alpha_two' => 'DM',
        ],
        'DO' => [
            'name' => 'Dominican Republic',
            'code_alpha_three' => 'DOM',
            'code_alpha_two' => 'DO',
        ],
        'EC' => [
            'name' => 'Ecuador',
            'code_alpha_three' => 'ECU',
            'code_alpha_two' => 'EC',
        ],
        'EG' => [
            'name' => 'Egypt',
            'code_alpha_three' => 'EGY',
            'code_alpha_two' => 'EG',
        ],
        'SV' => [
            'name' => 'El Salvador',
            'code_alpha_three' => 'SLV',
            'code_alpha_two' => 'SV',
        ],
        'GQ' => [
            'name' => 'Equatorial Guinea',
            'code_alpha_three' => 'GNQ',
            'code_alpha_two' => 'GQ',
        ],
        'ER' => [
            'name' => 'Eritrea',
            'code_alpha_three' => 'ERI',
            'code_alpha_two' => 'ER',
        ],
        'EE' => [
            'name' => 'Estonia',
            'code_alpha_three' => 'EST',
            'code_alpha_two' => 'EE',
        ],
        'SZ' => [
            'name' => 'Eswatini',
            'code_alpha_three' => 'SWZ',
            'code_alpha_two' => 'SZ',
        ],
        'ET' => [
            'name' => 'Ethiopia',
            'code_alpha_three' => 'ETH',
            'code_alpha_two' => 'ET',
        ],
        'FK' => [
            'name' => 'Falkland Islands',
            'code_alpha_three' => 'FLK',
            'code_alpha_two' => 'FK',
        ],
        'FO' => [
            'name' => 'Faroe Islands',
            'code_alpha_three' => 'FRO',
            'code_alpha_two' => 'FO',
        ],
        'FJ' => [
            'name' => 'Fiji',
            'code_alpha_three' => 'FJI',
            'code_alpha_two' => 'FJ',
        ],
        'FI' => [
            'name' => 'Finland',
            'code_alpha_three' => 'FIN',
            'code_alpha_two' => 'FI',
        ],
        'FR' => [
            'name' => 'France',
            'code_alpha_three' => 'FRA',
            'code_alpha_two' => 'FR',
        ],
        'GF' => [
            'name' => 'French Guiana',
            'code_alpha_three' => 'GUF',
            'code_alpha_two' => 'GF',
        ],
        'PF' => [
            'name' => 'French Polynesia',
            'code_alpha_three' => 'PYF',
            'code_alpha_two' => 'PF',
        ],
        'TF' => [
            'name' => 'French Southern and Antarctic Lands',
            'code_alpha_three' => 'ATF',
            'code_alpha_two' => 'TF',
        ],
        'GA' => [
            'name' => 'Gabon',
            'code_alpha_three' => 'GAB',
            'code_alpha_two' => 'GA',
        ],
        'GM' => [
            'name' => 'Gambia',
            'code_alpha_three' => 'GMB',
            'code_alpha_two' => 'GM',
        ],
        'GE' => [
            'name' => 'Georgia',
            'code_alpha_three' => 'GEO',
            'code_alpha_two' => 'GE',
        ],
        'DE' => [
            'name' => 'Germany',
            'code_alpha_three' => 'DEU',
            'code_alpha_two' => 'DE',
        ],
        'GH' => [
            'name' => 'Ghana',
            'code_alpha_three' => 'GHA',
            'code_alpha_two' => 'GH',
        ],
        'GI' => [
            'name' => 'Gibraltar',
            'code_alpha_three' => 'GIB',
            'code_alpha_two' => 'GI',
        ],
        'GR' => [
            'name' => 'Greece',
            'code_alpha_three' => 'GRC',
            'code_alpha_two' => 'GR',
        ],
        'GL' => [
            'name' => 'Greenland',
            'code_alpha_three' => 'GRL',
            'code_alpha_two' => 'GL',
        ],
        'GD' => [
            'name' => 'Grenada',
            'code_alpha_three' => 'GRD',
            'code_alpha_two' => 'GD',
        ],
        'GP' => [
            'name' => 'Guadeloupe',
            'code_alpha_three' => 'GLP',
            'code_alpha_two' => 'GP',
        ],
        'GU' => [
            'name' => 'Guam',
            'code_alpha_three' => 'GUM',
            'code_alpha_two' => 'GU',
        ],
        'GT' => [
            'name' => 'Guatemala',
            'code_alpha_three' => 'GTM',
            'code_alpha_two' => 'GT',
        ],
        'GG' => [
            'name' => 'Guernsey',
            'code_alpha_three' => 'GGY',
            'code_alpha_two' => 'GG',
        ],
        'GN' => [
            'name' => 'Guinea',
            'code_alpha_three' => 'GIN',
            'code_alpha_two' => 'GN',
        ],
        'GW' => [
            'name' => 'Guinea-Bissau',
            'code_alpha_three' => 'GNB',
            'code_alpha_two' => 'GW',
        ],
        'GY' => [
            'name' => 'Guyana',
            'code_alpha_three' => 'GUY',
            'code_alpha_two' => 'GY',
        ],
        'HT' => [
            'name' => 'Haiti',
            'code_alpha_three' => 'HTI',
            'code_alpha_two' => 'HT',
        ],
        'HM' => [
            'name' => 'Heard Island and McDonald Islands',
            'code_alpha_three' => 'HMD',
            'code_alpha_two' => 'HM',
        ],
        'HN' => [
            'name' => 'Honduras',
            'code_alpha_three' => 'HND',
            'code_alpha_two' => 'HN',
        ],
        'HK' => [
            'name' => 'Hong Kong',
            'code_alpha_three' => 'HKG',
            'code_alpha_two' => 'HK',
        ],
        'HU' => [
            'name' => 'Hungary',
            'code_alpha_three' => 'HUN',
            'code_alpha_two' => 'HU',
        ],
        'IS' => [
            'name' => 'Iceland',
            'code_alpha_three' => 'ISL',
            'code_alpha_two' => 'IS',
        ],
        'IN' => [
            'name' => 'India',
            'code_alpha_three' => 'IND',
            'code_alpha_two' => 'IN',
        ],
        'ID' => [
            'name' => 'Indonesia',
            'code_alpha_three' => 'IDN',
            'code_alpha_two' => 'ID',
        ],
        'IR' => [
            'name' => 'Iran',
            'code_alpha_three' => 'IRN',
            'code_alpha_two' => 'IR',
        ],
        'IQ' => [
            'name' => 'Iraq',
            'code_alpha_three' => 'IRQ',
            'code_alpha_two' => 'IQ',
        ],
        'IE' => [
            'name' => 'Ireland',
            'code_alpha_three' => 'IRL',
            'code_alpha_two' => 'IE',
        ],
        'IM' => [
            'name' => 'Isle of Man',
            'code_alpha_three' => 'IMN',
            'code_alpha_two' => 'IM',
        ],
        'IL' => [
            'name' => 'Israel',
            'code_alpha_three' => 'ISR',
            'code_alpha_two' => 'IL',
        ],
        'IT' => [
            'name' => 'Italy',
            'code_alpha_three' => 'ITA',
            'code_alpha_two' => 'IT',
        ],
        'CI' => [
            'name' => 'Ivory Coast',
            'code_alpha_three' => 'CIV',
            'code_alpha_two' => 'CI',
        ],
        'JM' => [
            'name' => 'Jamaica',
            'code_alpha_three' => 'JAM',
            'code_alpha_two' => 'JM',
        ],
        'JP' => [
            'name' => 'Japan',
            'code_alpha_three' => 'JPN',
            'code_alpha_two' => 'JP',
        ],
        'JE' => [
            'name' => 'Jersey',
            'code_alpha_three' => 'JEY',
            'code_alpha_two' => 'JE',
        ],
        'JO' => [
            'name' => 'Jordan',
            'code_alpha_three' => 'JOR',
            'code_alpha_two' => 'JO',
        ],
        'KZ' => [
            'name' => 'Kazakhstan',
            'code_alpha_three' => 'KAZ',
            'code_alpha_two' => 'KZ',
        ],
        'KE' => [
            'name' => 'Kenya',
            'code_alpha_three' => 'KEN',
            'code_alpha_two' => 'KE',
        ],
        'KI' => [
            'name' => 'Kiribati',
            'code_alpha_three' => 'KIR',
            'code_alpha_two' => 'KI',
        ],
        'XK' => [
            'name' => 'Kosovo',
            'code_alpha_three' => 'UNK',
            'code_alpha_two' => 'XK',
        ],
        'KW' => [
            'name' => 'Kuwait',
            'code_alpha_three' => 'KWT',
            'code_alpha_two' => 'KW',
        ],
        'KG' => [
            'name' => 'Kyrgyzstan',
            'code_alpha_three' => 'KGZ',
            'code_alpha_two' => 'KG',
        ],
        'LA' => [
            'name' => 'Laos',
            'code_alpha_three' => 'LAO',
            'code_alpha_two' => 'LA',
        ],
        'LV' => [
            'name' => 'Latvia',
            'code_alpha_three' => 'LVA',
            'code_alpha_two' => 'LV',
        ],
        'LB' => [
            'name' => 'Lebanon',
            'code_alpha_three' => 'LBN',
            'code_alpha_two' => 'LB',
        ],
        'LS' => [
            'name' => 'Lesotho',
            'code_alpha_three' => 'LSO',
            'code_alpha_two' => 'LS',
        ],
        'LR' => [
            'name' => 'Liberia',
            'code_alpha_three' => 'LBR',
            'code_alpha_two' => 'LR',
        ],
        'LY' => [
            'name' => 'Libya',
            'code_alpha_three' => 'LBY',
            'code_alpha_two' => 'LY',
        ],
        'LI' => [
            'name' => 'Liechtenstein',
            'code_alpha_three' => 'LIE',
            'code_alpha_two' => 'LI',
        ],
        'LT' => [
            'name' => 'Lithuania',
            'code_alpha_three' => 'LTU',
            'code_alpha_two' => 'LT',
        ],
        'LU' => [
            'name' => 'Luxembourg',
            'code_alpha_three' => 'LUX',
            'code_alpha_two' => 'LU',
        ],
        'MO' => [
            'name' => 'Macau',
            'code_alpha_three' => 'MAC',
            'code_alpha_two' => 'MO',
        ],
        'MG' => [
            'name' => 'Madagascar',
            'code_alpha_three' => 'MDG',
            'code_alpha_two' => 'MG',
        ],
        'MW' => [
            'name' => 'Malawi',
            'code_alpha_three' => 'MWI',
            'code_alpha_two' => 'MW',
        ],
        'MY' => [
            'name' => 'Malaysia',
            'code_alpha_three' => 'MYS',
            'code_alpha_two' => 'MY',
        ],
        'MV' => [
            'name' => 'Maldives',
            'code_alpha_three' => 'MDV',
            'code_alpha_two' => 'MV',
        ],
        'ML' => [
            'name' => 'Mali',
            'code_alpha_three' => 'MLI',
            'code_alpha_two' => 'ML',
        ],
        'MT' => [
            'name' => 'Malta',
            'code_alpha_three' => 'MLT',
            'code_alpha_two' => 'MT',
        ],
        'MH' => [
            'name' => 'Marshall Islands',
            'code_alpha_three' => 'MHL',
            'code_alpha_two' => 'MH',
        ],
        'MQ' => [
            'name' => 'Martinique',
            'code_alpha_three' => 'MTQ',
            'code_alpha_two' => 'MQ',
        ],
        'MR' => [
            'name' => 'Mauritania',
            'code_alpha_three' => 'MRT',
            'code_alpha_two' => 'MR',
        ],
        'MU' => [
            'name' => 'Mauritius',
            'code_alpha_three' => 'MUS',
            'code_alpha_two' => 'MU',
        ],
        'YT' => [
            'name' => 'Mayotte',
            'code_alpha_three' => 'MYT',
            'code_alpha_two' => 'YT',
        ],
        'MX' => [
            'name' => 'Mexico',
            'code_alpha_three' => 'MEX',
            'code_alpha_two' => 'MX',
        ],
        'FM' => [
            'name' => 'Micronesia',
            'code_alpha_three' => 'FSM',
            'code_alpha_two' => 'FM',
        ],
        'MD' => [
            'name' => 'Moldova',
            'code_alpha_three' => 'MDA',
            'code_alpha_two' => 'MD',
        ],
        'MC' => [
            'name' => 'Monaco',
            'code_alpha_three' => 'MCO',
            'code_alpha_two' => 'MC',
        ],
        'MN' => [
            'name' => 'Mongolia',
            'code_alpha_three' => 'MNG',
            'code_alpha_two' => 'MN',
        ],
        'ME' => [
            'name' => 'Montenegro',
            'code_alpha_three' => 'MNE',
            'code_alpha_two' => 'ME',
        ],
        'MS' => [
            'name' => 'Montserrat',
            'code_alpha_three' => 'MSR',
            'code_alpha_two' => 'MS',
        ],
        'MA' => [
            'name' => 'Morocco',
            'code_alpha_three' => 'MAR',
            'code_alpha_two' => 'MA',
        ],
        'MZ' => [
            'name' => 'Mozambique',
            'code_alpha_three' => 'MOZ',
            'code_alpha_two' => 'MZ',
        ],
        'MM' => [
            'name' => 'Myanmar',
            'code_alpha_three' => 'MMR',
            'code_alpha_two' => 'MM',
        ],
        'NA' => [
            'name' => 'Namibia',
            'code_alpha_three' => 'NAM',
            'code_alpha_two' => 'NA',
        ],
        'NR' => [
            'name' => 'Nauru',
            'code_alpha_three' => 'NRU',
            'code_alpha_two' => 'NR',
        ],
        'NP' => [
            'name' => 'Nepal',
            'code_alpha_three' => 'NPL',
            'code_alpha_two' => 'NP',
        ],
        'NL' => [
            'name' => 'Netherlands',
            'code_alpha_three' => 'NLD',
            'code_alpha_two' => 'NL',
        ],
        'NC' => [
            'name' => 'New Caledonia',
            'code_alpha_three' => 'NCL',
            'code_alpha_two' => 'NC',
        ],
        'NZ' => [
            'name' => 'New Zealand',
            'code_alpha_three' => 'NZL',
            'code_alpha_two' => 'NZ',
        ],
        'NI' => [
            'name' => 'Nicaragua',
            'code_alpha_three' => 'NIC',
            'code_alpha_two' => 'NI',
        ],
        'NE' => [
            'name' => 'Niger',
            'code_alpha_three' => 'NER',
            'code_alpha_two' => 'NE',
        ],
        'NG' => [
            'name' => 'Nigeria',
            'code_alpha_three' => 'NGA',
            'code_alpha_two' => 'NG',
        ],
        'NU' => [
            'name' => 'Niue',
            'code_alpha_three' => 'NIU',
            'code_alpha_two' => 'NU',
        ],
        'NF' => [
            'name' => 'Norfolk Island',
            'code_alpha_three' => 'NFK',
            'code_alpha_two' => 'NF',
        ],
        'KP' => [
            'name' => 'North Korea',
            'code_alpha_three' => 'PRK',
            'code_alpha_two' => 'KP',
        ],
        'MK' => [
            'name' => 'North Macedonia',
            'code_alpha_three' => 'MKD',
            'code_alpha_two' => 'MK',
        ],
        'MP' => [
            'name' => 'Northern Mariana Islands',
            'code_alpha_three' => 'MNP',
            'code_alpha_two' => 'MP',
        ],
        'NO' => [
            'name' => 'Norway',
            'code_alpha_three' => 'NOR',
            'code_alpha_two' => 'NO',
        ],
        'OM' => [
            'name' => 'Oman',
            'code_alpha_three' => 'OMN',
            'code_alpha_two' => 'OM',
        ],
        'PK' => [
            'name' => 'Pakistan',
            'code_alpha_three' => 'PAK',
            'code_alpha_two' => 'PK',
        ],
        'PW' => [
            'name' => 'Palau',
            'code_alpha_three' => 'PLW',
            'code_alpha_two' => 'PW',
        ],
        'PS' => [
            'name' => 'Palestine',
            'code_alpha_three' => 'PSE',
            'code_alpha_two' => 'PS',
        ],
        'PA' => [
            'name' => 'Panama',
            'code_alpha_three' => 'PAN',
            'code_alpha_two' => 'PA',
        ],
        'PG' => [
            'name' => 'Papua New Guinea',
            'code_alpha_three' => 'PNG',
            'code_alpha_two' => 'PG',
        ],
        'PY' => [
            'name' => 'Paraguay',
            'code_alpha_three' => 'PRY',
            'code_alpha_two' => 'PY',
        ],
        'PE' => [
            'name' => 'Peru',
            'code_alpha_three' => 'PER',
            'code_alpha_two' => 'PE',
        ],
        'PH' => [
            'name' => 'Philippines',
            'code_alpha_three' => 'PHL',
            'code_alpha_two' => 'PH',
        ],
        'PN' => [
            'name' => 'Pitcairn Islands',
            'code_alpha_three' => 'PCN',
            'code_alpha_two' => 'PN',
        ],
        'PL' => [
            'name' => 'Poland',
            'code_alpha_three' => 'POL',
            'code_alpha_two' => 'PL',
        ],
        'PT' => [
            'name' => 'Portugal',
            'code_alpha_three' => 'PRT',
            'code_alpha_two' => 'PT',
        ],
        'PR' => [
            'name' => 'Puerto Rico',
            'code_alpha_three' => 'PRI',
            'code_alpha_two' => 'PR',
        ],
        'QA' => [
            'name' => 'Qatar',
            'code_alpha_three' => 'QAT',
            'code_alpha_two' => 'QA',
        ],
        'CG' => [
            'name' => 'Republic of the Congo',
            'code_alpha_three' => 'COG',
            'code_alpha_two' => 'CG',
        ],
        'RO' => [
            'name' => 'Romania',
            'code_alpha_three' => 'ROU',
            'code_alpha_two' => 'RO',
        ],
        'RU' => [
            'name' => 'Russia',
            'code_alpha_three' => 'RUS',
            'code_alpha_two' => 'RU',
        ],
        'RW' => [
            'name' => 'Rwanda',
            'code_alpha_three' => 'RWA',
            'code_alpha_two' => 'RW',
        ],
        'RE' => [
            'name' => 'Réunion',
            'code_alpha_three' => 'REU',
            'code_alpha_two' => 'RE',
        ],
        'BL' => [
            'name' => 'Saint Barthélemy',
            'code_alpha_three' => 'BLM',
            'code_alpha_two' => 'BL',
        ],
        'SH' => [
            'name' => 'Saint Helena, Ascension and Tristan da Cunha',
            'code_alpha_three' => 'SHN',
            'code_alpha_two' => 'SH',
        ],
        'KN' => [
            'name' => 'Saint Kitts and Nevis',
            'code_alpha_three' => 'KNA',
            'code_alpha_two' => 'KN',
        ],
        'LC' => [
            'name' => 'Saint Lucia',
            'code_alpha_three' => 'LCA',
            'code_alpha_two' => 'LC',
        ],
        'MF' => [
            'name' => 'Saint Martin',
            'code_alpha_three' => 'MAF',
            'code_alpha_two' => 'MF',
        ],
        'PM' => [
            'name' => 'Saint Pierre and Miquelon',
            'code_alpha_three' => 'SPM',
            'code_alpha_two' => 'PM',
        ],
        'VC' => [
            'name' => 'Saint Vincent and the Grenadines',
            'code_alpha_three' => 'VCT',
            'code_alpha_two' => 'VC',
        ],
        'WS' => [
            'name' => 'Samoa',
            'code_alpha_three' => 'WSM',
            'code_alpha_two' => 'WS',
        ],
        'SM' => [
            'name' => 'San Marino',
            'code_alpha_three' => 'SMR',
            'code_alpha_two' => 'SM',
        ],
        'SA' => [
            'name' => 'Saudi Arabia',
            'code_alpha_three' => 'SAU',
            'code_alpha_two' => 'SA',
        ],
        'SN' => [
            'name' => 'Senegal',
            'code_alpha_three' => 'SEN',
            'code_alpha_two' => 'SN',
        ],
        'RS' => [
            'name' => 'Serbia',
            'code_alpha_three' => 'SRB',
            'code_alpha_two' => 'RS',
        ],
        'SC' => [
            'name' => 'Seychelles',
            'code_alpha_three' => 'SYC',
            'code_alpha_two' => 'SC',
        ],
        'SL' => [
            'name' => 'Sierra Leone',
            'code_alpha_three' => 'SLE',
            'code_alpha_two' => 'SL',
        ],
        'SG' => [
            'name' => 'Singapore',
            'code_alpha_three' => 'SGP',
            'code_alpha_two' => 'SG',
        ],
        'SX' => [
            'name' => 'Sint Maarten',
            'code_alpha_three' => 'SXM',
            'code_alpha_two' => 'SX',
        ],
        'SK' => [
            'name' => 'Slovakia',
            'code_alpha_three' => 'SVK',
            'code_alpha_two' => 'SK',
        ],
        'SI' => [
            'name' => 'Slovenia',
            'code_alpha_three' => 'SVN',
            'code_alpha_two' => 'SI',
        ],
        'SB' => [
            'name' => 'Solomon Islands',
            'code_alpha_three' => 'SLB',
            'code_alpha_two' => 'SB',
        ],
        'SO' => [
            'name' => 'Somalia',
            'code_alpha_three' => 'SOM',
            'code_alpha_two' => 'SO',
        ],
        'ZA' => [
            'name' => 'South Africa',
            'code_alpha_three' => 'ZAF',
            'code_alpha_two' => 'ZA',
        ],
        'GS' => [
            'name' => 'South Georgia',
            'code_alpha_three' => 'SGS',
            'code_alpha_two' => 'GS',
        ],
        'KR' => [
            'name' => 'South Korea',
            'code_alpha_three' => 'KOR',
            'code_alpha_two' => 'KR',
        ],
        'SS' => [
            'name' => 'South Sudan',
            'code_alpha_three' => 'SSD',
            'code_alpha_two' => 'SS',
        ],
        'ES' => [
            'name' => 'Spain',
            'code_alpha_three' => 'ESP',
            'code_alpha_two' => 'ES',
        ],
        'LK' => [
            'name' => 'Sri Lanka',
            'code_alpha_three' => 'LKA',
            'code_alpha_two' => 'LK',
        ],
        'SD' => [
            'name' => 'Sudan',
            'code_alpha_three' => 'SDN',
            'code_alpha_two' => 'SD',
        ],
        'SR' => [
            'name' => 'Suriname',
            'code_alpha_three' => 'SUR',
            'code_alpha_two' => 'SR',
        ],
        'SJ' => [
            'name' => 'Svalbard and Jan Mayen',
            'code_alpha_three' => 'SJM',
            'code_alpha_two' => 'SJ',
        ],
        'SE' => [
            'name' => 'Sweden',
            'code_alpha_three' => 'SWE',
            'code_alpha_two' => 'SE',
        ],
        'CH' => [
            'name' => 'Switzerland',
            'code_alpha_three' => 'CHE',
            'code_alpha_two' => 'CH',
        ],
        'SY' => [
            'name' => 'Syria',
            'code_alpha_three' => 'SYR',
            'code_alpha_two' => 'SY',
        ],
        'ST' => [
            'name' => 'São Tomé and Príncipe',
            'code_alpha_three' => 'STP',
            'code_alpha_two' => 'ST',
        ],
        'TW' => [
            'name' => 'Taiwan',
            'code_alpha_three' => 'TWN',
            'code_alpha_two' => 'TW',
        ],
        'TJ' => [
            'name' => 'Tajikistan',
            'code_alpha_three' => 'TJK',
            'code_alpha_two' => 'TJ',
        ],
        'TZ' => [
            'name' => 'Tanzania',
            'code_alpha_three' => 'TZA',
            'code_alpha_two' => 'TZ',
        ],
        'TH' => [
            'name' => 'Thailand',
            'code_alpha_three' => 'THA',
            'code_alpha_two' => 'TH',
        ],
        'TL' => [
            'name' => 'Timor-Leste',
            'code_alpha_three' => 'TLS',
            'code_alpha_two' => 'TL',
        ],
        'TG' => [
            'name' => 'Togo',
            'code_alpha_three' => 'TGO',
            'code_alpha_two' => 'TG',
        ],
        'TK' => [
            'name' => 'Tokelau',
            'code_alpha_three' => 'TKL',
            'code_alpha_two' => 'TK',
        ],
        'TO' => [
            'name' => 'Tonga',
            'code_alpha_three' => 'TON',
            'code_alpha_two' => 'TO',
        ],
        'TT' => [
            'name' => 'Trinidad and Tobago',
            'code_alpha_three' => 'TTO',
            'code_alpha_two' => 'TT',
        ],
        'TN' => [
            'name' => 'Tunisia',
            'code_alpha_three' => 'TUN',
            'code_alpha_two' => 'TN',
        ],
        'TR' => [
            'name' => 'Turkey',
            'code_alpha_three' => 'TUR',
            'code_alpha_two' => 'TR',
        ],
        'TM' => [
            'name' => 'Turkmenistan',
            'code_alpha_three' => 'TKM',
            'code_alpha_two' => 'TM',
        ],
        'TC' => [
            'name' => 'Turks and Caicos Islands',
            'code_alpha_three' => 'TCA',
            'code_alpha_two' => 'TC',
        ],
        'TV' => [
            'name' => 'Tuvalu',
            'code_alpha_three' => 'TUV',
            'code_alpha_two' => 'TV',
        ],
        'UG' => [
            'name' => 'Uganda',
            'code_alpha_three' => 'UGA',
            'code_alpha_two' => 'UG',
        ],
        'UA' => [
            'name' => 'Ukraine',
            'code_alpha_three' => 'UKR',
            'code_alpha_two' => 'UA',
        ],
        'AE' => [
            'name' => 'United Arab Emirates',
            'code_alpha_three' => 'ARE',
            'code_alpha_two' => 'AE',
        ],
        'GB' => [
            'name' => 'United Kingdom',
            'code_alpha_three' => 'GBR',
            'code_alpha_two' => 'GB',
        ],
        'US' => [
            'name' => 'United States',
            'code_alpha_three' => 'USA',
            'code_alpha_two' => 'US',
        ],
        'UM' => [
            'name' => 'United States Minor Outlying Islands',
            'code_alpha_three' => 'UMI',
            'code_alpha_two' => 'UM',
        ],
        'VI' => [
            'name' => 'United States Virgin Islands',
            'code_alpha_three' => 'VIR',
            'code_alpha_two' => 'VI',
        ],
        'UY' => [
            'name' => 'Uruguay',
            'code_alpha_three' => 'URY',
            'code_alpha_two' => 'UY',
        ],
        'UZ' => [
            'name' => 'Uzbekistan',
            'code_alpha_three' => 'UZB',
            'code_alpha_two' => 'UZ',
        ],
        'VU' => [
            'name' => 'Vanuatu',
            'code_alpha_three' => 'VUT',
            'code_alpha_two' => 'VU',
        ],
        'VA' => [
            'name' => 'Vatican City',
            'code_alpha_three' => 'VAT',
            'code_alpha_two' => 'VA',
        ],
        'VE' => [
            'name' => 'Venezuela',
            'code_alpha_three' => 'VEN',
            'code_alpha_two' => 'VE',
        ],
        'VN' => [
            'name' => 'Vietnam',
            'code_alpha_three' => 'VNM',
            'code_alpha_two' => 'VN',
        ],
        'WF' => [
            'name' => 'Wallis and Futuna',
            'code_alpha_three' => 'WLF',
            'code_alpha_two' => 'WF',
        ],
        'EH' => [
            'name' => 'Western Sahara',
            'code_alpha_three' => 'ESH',
            'code_alpha_two' => 'EH',
        ],
        'YE' => [
            'name' => 'Yemen',
            'code_alpha_three' => 'YEM',
            'code_alpha_two' => 'YE',
        ],
        'ZM' => [
            'name' => 'Zambia',
            'code_alpha_three' => 'ZMB',
            'code_alpha_two' => 'ZM',
        ],
        'ZW' => [
            'name' => 'Zimbabwe',
            'code_alpha_three' => 'ZWE',
            'code_alpha_two' => 'ZW',
        ],
        'AX' => [
            'name' => 'Åland Islands',
            'code_alpha_three' => 'ALA',
            'code_alpha_two' => 'AX',
        ],
    ];

    /**
     * @var string The country code in ISO 3166-1 alpha-2 format.
     */
    public string $codeAlphaTwo;

    /**
     * @var string The country code in ISO 3166-1 alpha-3 format.
     */
    public string $codeAlphaThree;

    /**
     * @var string The name of the country.
     */
    public string $name;

    public function __construct(string $codeAlphaTwo, string $codeAlphaThree, string $name)
    {
        $this->codeAlphaTwo = $codeAlphaTwo;
        $this->codeAlphaThree = $codeAlphaThree;
        $this->name = $name;
    }

    /**
     * @param  string  $country  The country's information. It can be the country's name, ISO 3166-1 alpha-2 code, or ISO 3166-1 alpha-3 code.
     * @return Country|null The country object if the country is found, otherwise null.
     */
    public static function newCountry(string $country): ?Country
    {
        if (empty($country)) {
            return null;
        }

        // Filter by alpha-2 country code.
        if (strlen($country) === 2 && ctype_upper($country)) {
            if (! array_key_exists($country, self::COUNTRIES)) {
                return null;
            }

            $countryInfo = self::COUNTRIES[$country];

            return new Country($country, $countryInfo['code_alpha_three'], $countryInfo['name']);
        }

        // Filter by alpha-3 country code.
        if (strlen($country) === 3 && ctype_upper($country)) {
            foreach (self::COUNTRIES as $countryCode => $countryInfo) {
                if ($countryInfo['code_alpha_three'] === $country) {
                    return new Country($countryCode, $country, $countryInfo['name']);
                }
            }

            return null;
        }

        // Filter by country name.
        foreach (self::COUNTRIES as $countryCode => $countryInfo) {
            if (strtolower($countryInfo['name']) === strtolower($country)) {
                return new Country($countryCode, $countryInfo['code_alpha_three'], $countryInfo['name']);
            }
        }

        return null;
    }

    /**
     * Get the list of countries.
     */
    public static function getList(): array
    {
        return self::COUNTRIES;
    }
}
