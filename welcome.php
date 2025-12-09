<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Contact Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: 'Lora', serif; 
            display: flex;
            flex-direction: column;
        }

        /* Background gif at the top of the page */
        .gif-background {
            margin-left: 500px;
            width: 550px;
            height: 300px; /* Adjust height of the GIF */
            background: url('assets/contacts.gif') center center / cover no-repeat;
        }

        /* The content after the GIF */
        .content {
            background-color: white;
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }

        .btn {
            margin: 10px;
            width: 220px;
            font-size: 1.2rem;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .btn-admin {
            background-color: #6a5acd; /* Soft purple */
            color: white;
            border: none;
        }

        .btn-user {
            background-color: #20b2aa; /* Light sea green */
            color: white;
            border: none;
        }

        .btn:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <!-- GIF at the top of the page -->
    <div class="gif-background"></div>

    <!-- Content section -->
    <div class="content">
        <h1>Welcome to Contact Management</h1>
        <a href="admin_login.php" class="btn btn-admin">Admin Login</a>
        <a href="login.php" class="btn btn-user">User Login</a>
    </div>

</body>
</html>