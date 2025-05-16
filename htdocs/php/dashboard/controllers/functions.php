<?php

function calculatePercentageAndRound($category_amount, $current_income)
{
    return round((($category_amount / $current_income) * 100), 2);
}