<!DOCTYPE html>
<html>
<head>
    <title>Tile Processing Summary</title>
</head>
<body>
    <h2>Tile Processing Report Summary</h2>

    <p><strong>Total Processed with Variants:</strong> {{ $insertedCount + $updatedCount + $skippedCount }}</p>
    <ul>
        <li><strong>Inserted:</strong> {{ $insertedCount }}</li>
        <li><strong>Updated:</strong> {{ $updatedCount }}</li>
        <li><strong>Deleted:</strong> {{ $deletedCount }}</li>
        <li><strong>Skipped:</strong> {{ $skippedCount }}</li>
    </ul>
</body>
</html>