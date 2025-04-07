<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tile Processing Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        #skippedRecordTable tr{
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tile Processing Report</h2>

    @if(count($insertedRecords) > 0)
        <h3>Inserted Tiles ({{ count($insertedRecords) }})</h3>
        <table>
            <thead>
            <tr>
                <th>Tile Name</th>
                <th>SKU</th>
                <th>Surface</th>
            </tr>
            </thead>
            <tbody>
            @foreach($insertedRecords as $tile)
                <tr>
                    <td>{{ $tile['name'] }}</td>
                    <td>{{ $tile['sku'] }}</td>
                    <td>{{ $tile['surface'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No new tiles were inserted.</p>
    @endif

    @if(count($updatedRecords) > 0)
        <h3>Updated Tiles ({{ count($updatedRecords) }})</h3>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Surface</th>
            </tr>
            </thead>
            <tbody>
            @foreach($updatedRecords as $tile)
                <tr>
                    <td>{{ $tile['name'] }}</td>
                    <td>{{ $tile['sku'] }}</td>
                    <td>{{ $tile['surface'] }}</td>
                    <td>{{ $tile['changedColumn'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No tiles were updated.</p>
    @endif

    @if(count($deletedRecords) > 0)
        <h3>Deleted Tiles ({{ count($deletedRecords) }})</h3>
        <table>
            <thead>
            <tr>
                <th>Tile Name</th>
                <th>SKU</th>
                <th>Surface</th>
            </tr>
            </thead>
            <tbody>
            @foreach($deletedRecords as $tile)
                <tr>
                    <td>{{ $tile['name'] }}</td>
                    <td>{{ $tile['sku'] }}</td>
                    <td>{{ $tile['surface'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No tiles were deleted.</p>
    @endif

    @if(count($skippedRecords) > 0)
        <h3>Skipped / Error Tiles ({{ count($skippedRecords) }})</h3>
        <table id="skippedRecordTable">
            <thead>
            <tr>
                <th>Tile Name</th>
                <th>SKU</th>
                <th>Date</th>
                <th>Reason</th>
            </tr>
            </thead>
            <tbody>
            @foreach($skippedRecords as $tile)
                <tr>
                    <td>{{ $tile['name'] }}</td>
                    <td>{{ $tile['sku'] }}</td>
                    <td>{{ $tile['date'] }}</td>
                    <td>{{ $tile['reason'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No tiles were Skipped or getting error .</p>
    @endif
</div>

</body>
</html>
