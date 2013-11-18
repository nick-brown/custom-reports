@extends('layouts.master')

Array 1: <% $first_count = count($first) %> 
<br> 
Array 2: <% $second_count = count($second) %>
<br>
<?php $largest = max($first_count, $second_count); ?>
<table>
    <tr>
        <th></th>
        <th>First Array</th>
        <th>Second Array</th>
        <th>Combined</th>
    </tr>
        
    @for($x = 0; $x < $largest; $x++)
        <?php $color = ($x % 2) == 0 ? 'CCC' : 'FFF'; ?>
        <tr style="background-color:#<% $color %>">
            <td><% $x + 1 %>.</td>
            <td><pre><?php @print_r($first[$x]); ?></pre></td>
            <td><pre><?php @print_r($second[$x]); ?></pre></td>
            <td><pre><?php @print_r($combined[$x]); ?></pre></td>
        </tr>
    @endfor
</table>


