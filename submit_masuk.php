<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Hari Ini</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            text-align: center;
            padding: 20px;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .container img {
            width: 100px;
        }

        .back-button {
            background-color: #4CAF50;
            /* Warna hijau sama dengan border */
            color: white;
            /* Warna tulisan putih */
            padding: 10px 20px;
            /* Padding lebih besar */
            border: none;
            /* Tanpa border */
            border-radius: 5px;
            /* Border radius kecil */
            cursor: pointer;
            /* Kursor menunjukkan tombol bisa diklik */
            margin-top: 20px;
            /* Jarak atas dari tombol */
        }

        .back-button:hover {
            background-color: #45a049;
            /* Warna hijau lebih gelap saat hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="checklist.png" alt="">
        <h1>Absensi Sukses</h1>
        <button class="back-button" onclick="goBack()">Back to Previous Page</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
