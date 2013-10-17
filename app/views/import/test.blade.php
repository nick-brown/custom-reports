@extends('layouts.master')

<div style="float:left" width:600px;>
        @foreach($first as $k => $entry)
        <?php $color = ($k % 2) == 0 ? 'CCC' : 'FFF'; ?>
            <pre style="background-color:#{{ $color }}"> {{ print_r($entry); }} </pre>
        @endforeach
    </pre>
</div>
<div style="float:left" width:600px;>
    <pre>
        @foreach($second as $k => $entry)
            {{ print_r($entry); }}
        @endforeach
    </pre>
</div>
