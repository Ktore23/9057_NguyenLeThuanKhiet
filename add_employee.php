<?php
// add_employee.php

include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_nv = $_POST['ma_nv'];
    $ten_nv = $_POST['ten_nv'];
    $phai = $_POST['phai'];
    $noi_sinh = $_POST['noi_sinh'];
    $ma_phong = $_POST['ma_phong'];
    $luong = $_POST['luong'];

    $sql = "INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $ma_nv, $ten_nv, $phai, $noi_sinh, $ma_phong, $luong);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Lỗi khi thêm nhân viên: " . $conn->error;
    }

    $stmt->close();
}

$departments = $conn->query("SELECT * FROM PHONGBAN");
$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-person-lines-fill me-2"></i>Quản Lý Nhân Sự</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-list-ul me-1"></i>Danh Sách Nhân Viên</a>
                    </li>
                    <?php if ($userRole == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_employee.php"><i class="bi bi-person-plus me-1"></i>Thêm Nhân Viên</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Đăng Xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 500px;">
            <h2 class="text-center mb-4">Thêm Nhân Viên</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="ma_nv" class="form-label">Mã Nhân Viên:</label>
                    <input type="text" id="ma_nv" name="ma_nv" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="ten_nv" class="form-label">Tên Nhân Viên:</label>
                    <input type="text" id="ten_nv" name="ten_nv" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="phai" class="form-label">Phái:</label>
                    <select id="phai" name="phai" class="form-select" required>
                        <option value="NAM">Nam</option>
                        <option value="NU">Nữ</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="noi_sinh" class="form-label">Nơi Sinh:</label>
                    <input type="text" id="noi_sinh" name="noi_sinh" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="ma_phong" class="form-label">Phòng Ban:</label>
                    <select id="ma_phong" name="ma_phong" class="form-select" required>
                        <?php while ($dept = $departments->fetch_assoc()): ?>
                            <option value="<?php echo $dept['Ma_Phong']; ?>"><?php echo $dept['Ten_Phong']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="luong" class="form-label">Lương:</label>
                    <input type="number" id="luong" name="luong" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Thêm</button>
                    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Quay Lại</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="text-center mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Quản Lý Nhân Sự. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>