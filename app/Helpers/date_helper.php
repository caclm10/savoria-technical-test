<?php

use CodeIgniter\I18n\Time;

function idDateFormat(string $dateStr): string
{
    return Time::parse($dateStr)->toLocalizedString("dd MMMM yyyy");
}

function calculateAge(string $dateStr): int
{
    return Time::parse($dateStr)->difference(Time::now())->getYears();
}

function toHTMLDate(string $dateStr): string
{
    return Time::parse($dateStr)->toDateString();
}
