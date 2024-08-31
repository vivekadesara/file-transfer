<?php
include 'db.php';
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $uploadDir = 'uploads/';
    $filePath = $uploadDir . basename($fileName);

    if (move_uploaded_file($fileTmpName, $filePath)) {
        // Generate a 6-digit PIN code
        $pinCode = random_int(100000, 999999);

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO files (name, path, pin_code) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fileName, $filePath, $pinCode);
        $stmt->execute();

        // Store the PIN code in the session
        $_SESSION['pin_code'] = $pinCode;

        echo "File uploaded successfully!";
    } else {
        echo "File upload failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }
        h1 {
            color: #fff;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-in-out;
            z-index: 2;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            animation: slideIn 1s ease-in-out;
            z-index: 2;
        }
        .form-label, .form-control, .btn {
            margin-bottom: 15px;
        }
        .btn {
            background-color: #007BFF;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }
            h1 {
                font-size: 1.8rem;
                
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
    </style>
</head>
<body>

    <!-- Background Video -->
    <video class="background-video" autoplay loop muted>
        <source src="file.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Overlay -->
    <div class="overlay"></div>

    <h1>File Management</h1>

    <div class="form-container">
        <!-- File Download Form -->
        <form action="download.php" method="post">
            <div class="mb-3">
                <label for="pin" class="form-label">Enter PIN Code:</label>
                <input type="text" id="pin" name="pin_code" class="form-control" 
                       value="<?php echo isset($_SESSION['pin_code']) ? $_SESSION['pin_code'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Download File</button>
        </form>
    </div>

    <div class="form-container mt-4">
        <!-- File Upload Form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file" class="form-label">Upload a File:</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
