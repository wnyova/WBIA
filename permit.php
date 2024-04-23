<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <br>
    <br>
    <title>Submit Permit/Sick Request</title>
</head>
<body>
    <h2>Submit Permit/Sick Request</h2>
    <form action="submit_request.php" method="post">
        <label for="izin_sakit_type">Type:</label>
        <select name="izin_sakit_type" id="izin_sakit_type">
            <option value="Izin">Permit</option>
            <option value="Sakit">Sick</option>
        </select><br>
        <label for="request_date">Date:</label><br>
        <input type="date" name="request_date" id="request_date"><br>
        <label for="request_comment">Comment:</label><br>
        <textarea name="request_comment" id="request_comment" cols="30" rows="5"></textarea><br>
        <input type="submit" value="Submit Request">
    </form>
</body>
</html>
