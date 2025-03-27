<?php
// edit_employee.php

include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$ma_nv = $_GET['id'];

$sql = "SELECT * FROM NHANVIEN WHERE Ma_NV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ma_nv);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    header("Location: index.php");
    exit();
}

$departments = $conn->query("SELECT * FROM PHONGBAN");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_nv = $_POST['ten_nv'];
    $phai = $_POST['phai'];
    $noi_sinh = $_POST['noi_sinh'];
    $ma_phong = $_POST['ma_phong'];
    $luong = $_POST['luong'];

    $sql = "UPDATE NHANVIEN SET Ten_NV = ?, Phai = ?, Noi_Sinh = ?, Ma_Phong = ?, Luong = ? WHERE Ma_NV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $ten_nv, $phai, $noi_sinh, $ma_phong, $luong, $ma_nv);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Lỗi khi cập nhật nhân viên: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 500px;">
            <h2 class="text-center mb-4">Sửa Nhân Viên</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="ma_nv" class="form-label">Mã Nhân Viên:</label>
                    <input type="text" id="ma_nv" name="ma_nv" class="form-control" value="<?php echo $employee['Ma_NV']; ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="ten_nv" class="form-label">Tên Nhân Viên:</label>
                    <input type="text" id="ten_nv" name="ten_nv" class="form-control" value="<?php echo $employee['Ten_NV']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phai" class="form-label">Phái:</label>
                    <select id="phai" name="phai" class="form-select" required>
                        <option value="NAM" <?php if ($employee['Phai'] == 'NAM') echo 'selected'; ?>>Nam</option>
                        <option value="NU" <?php if ($employee['Phai'] == 'NU') echo 'selected'; ?>>Nữ</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="noi_sinh" class="form-label">Nơi Sinh:</label>
                    <input type="text" id="noi_sinh" name="noi_sinh" class="form-control" value="<?php echo $employee['Noi_Sinh']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="ma_phong" class="form-label">Phòng Ban:</label>
                    <select id="ma_phong" name="ma_phong" class="form-select" required>
                        <?php while ($dept = $departments->fetch_assoc()): ?>
                            <option value="<?php echo $dept['Ma_Phong']; ?>" <?php if ($dept['Ma_Phong'] == $employee['Ma_Phong']) echo 'selected'; ?>>
                                <?php echo $dept['Ten_Phong']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="luong" class="form-label">Lương:</label>
                    <input type="number" id="luong" name="luong" class="form-control" value="<?php echo $employee['Luong']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Cập Nhật</button>
                <a href="index.php" class="btn btn-secondary">Quay Lại</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>