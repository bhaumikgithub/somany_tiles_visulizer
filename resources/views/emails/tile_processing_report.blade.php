<!DOCTYPE html>
<html>
<head>
    <title>Tile Processing Report</title>
</head>
<body>
<h2>Tile Processing Summary</h2>

<p><strong>Total Inserted:</strong> {{ count($insertedRecords) }}</p>
@if(count($insertedRecords))
    <ul>
        @foreach($insertedRecords as $record)
            <li>{{ $record['name'] }} (SKU: {{ $record['sku'] }}, Surface: {{ $record['surface'] }})</li>
        @endforeach
    </ul>
@endif

<p><strong>Total Updated:</strong> {{ count($updatedRecords) }}</p>
@if(count($updatedRecords))
    <ul>
        @foreach($updatedRecords as $record)
            <li>{{ $record['name'] }} (SKU: {{ $record['sku'] }}, Surface: {{ $record['surface'] }})</li>
        @endforeach
    </ul>
@endif

<p><strong>Total Deleted:</strong> {{ count($deletedRecords) }}</p>
@if(count($deletedRecords))
    <ul>
        @foreach($deletedRecords as $record)
            <li>{{ $record['name'] }} (SKU: {{ $record['sku'] }})</li>
        @endforeach
    </ul>
@endif

<p>Process completed successfully.</p>
</body>
</html>
