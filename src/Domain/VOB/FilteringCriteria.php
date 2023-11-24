<?php

namespace App\Domain\VOB;

/**
 * These strings are used from API and CLI to set filtering type.
 */
enum FilteringCriteria: string
{
    case Equal = 'eq';
    case LesserEqual = 'le';
    case GreaterEqual = 'ge';
    case StartsWith = 'sw';
    case EndsWith = 'ew';
    case Contains = 'ct';
}

