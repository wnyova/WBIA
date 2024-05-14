<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kirim Permohonan Izin/Sakit</title>
</head>
<body>
    <h2>Kirim Permohonan Izin/Sakit</h2>
    <form action="submit_request.php" method="post">
        <label for="izin_sakit_type">Tipe:</label>
        <select name="izin_sakit_type" id="izin_sakit_type">
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
        </select><br>
        <label for="request_date">Tanggal:</label><br>
        <input type="date" name="request_date" id="request_date"><br>
        <label for="request_comment">Keterangan:</label><br>
        <textarea name="request_comment" id="request_comment" cols="30" rows="5"></textarea><br>
        <label for="request_proof">Bukti <i>(cantumkan link Google Drive di bawah inii):</i></label><br>
        <input type="text" name="request_proof" id="request_proof"><br> 
        <input type="submit" value="Kirim Permohonan">
    </form>
</body>
</html>