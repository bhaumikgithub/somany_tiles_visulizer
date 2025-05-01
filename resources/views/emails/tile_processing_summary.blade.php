<!DOCTYPE html>
<html>
<head>
    <title>Tile Processing Summary</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Tile Processing Report Summary</h2>

        @if( $insertedCount > 0  )
            <h3>Inserted Tiles : {{$insertedCount}}</h3>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td style="font-weight: bold;width: 15%;color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;">SKUs</td>
                        <td style="border-color: #d6e9c6;">{{$insertedSkus}}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>No new tiles were inserted.</p>
        @endif

        @if( $updatedCount > 0  )
            <h3>Updated Tiles : {{$updatedCount}}</h3>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td style="font-weight: bold;width: 15%;color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;">SKUs</td>
                        <td style="border-color: #d6e9c6;">{{$updatedSkus}}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>No tiles were updated.</p>
        @endif

        @if( $deletedCount > 0 ) 
            <h3>Deleted Tiles : {{$deletedCount}}</h3>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td style="background-color: #f2dede;font-weight: bold;color: #a94442;width: 15%;border-color: #ebccd1;">SKUs</td>
                        <td style="border-color: #ebccd1;">{{$deletedSkus}}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>No tiles were deleted.</p>
        @endif

        @if( $skippedCount > 0  )
            <h3>Skipped / Error Tiles : {{$skippedCount}}</h3>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td style="background-color: #f2dede;font-weight: bold;color: #a94442;width: 15%;border-color: #ebccd1;">SKUs</td>
                        <td style="border-color: #ebccd1;">{{$skippedSkus}}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>No tiles were skipped or detecting errors.</p>
        @endif
    </div>
</body>
</html>