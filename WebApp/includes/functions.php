<?php
function extractIntAddress($adresse) {
    $morceaux = explode(' ', $adresse);
    if (is_numeric($morceaux[0])) {return $morceaux[0];} else {return "?";}}

function extractStrAddress($adresse) {
    $morceaux = explode(' ', $adresse);

    if (is_numeric($morceaux[0])) {array_shift($morceaux);
    } return implode(' ', $morceaux);}

function extractDate($datetime) {
    $timestamp = strtotime($datetime);
    setlocale(LC_TIME, 'fr_FR.utf8');

    return date('d', $timestamp).' '.strftime('%B', $timestamp);}
function extractHour($datetime) {
    $timestamp = strtotime($datetime);
        return date('H:i', $timestamp);}
        
function removeSpace($str){
    return trim($str);
}
function titleCase($str) {
    return ucwords(strtolower(removeSpace($str)));
}
function upperCase($str) {
    return removeSpace(strtoupper($str));
}
?>