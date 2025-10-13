<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="utf-8">
    <title><?= $error_title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
            padding-top: 100px;
        }
        .error-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 40px;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.2s;
        }
        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h2><?= $error_title ?></h2>
        <p><?= $error_message ?></p>
        <a href="<?= site_url('territory_controller/display_frontpage') ?>" class="button">
            Palaa etusivulle
        </a>
    </div>
</body>
</html>
