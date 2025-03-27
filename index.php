<?php
// index.php

include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userRole = $_SESSION['role'];
$roleDisplay = ($userRole == 'admin') ? 'Admin' : 'User';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        /* Ensure the card has a minimum height to prevent content shifting */
        .card-content {
            min-height: 400px; /* Adjust this value based on your needs */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        /* Ensure the pagination stays at the bottom */
        .pagination-container {
            margin-top: auto;
        }
    </style>
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

    <div class="container mt-4">
        <div class="card p-4">
            <div class="card-content">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">THÔNG TIN NHÂN VIÊN</h2>
                        <div>
                            Xin chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?> (<?php echo $roleDisplay; ?>)
                        </div>
                    </div>

                    <!-- <?php if ($userRole == 'admin'): ?>
                        <a href="add_employee.php" class="btn btn-primary mb-3"><i class="bi bi-person-plus me-1"></i>Thêm Nhân Viên</a>
                    <?php endif; ?> -->

                    <?php
                    $employeesPerPage = 5;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $employeesPerPage;

                    $totalQuery = "SELECT COUNT(*) as total FROM NHANVIEN";
                    $totalResult = $conn->query($totalQuery);
                    $totalRow = $totalResult->fetch_assoc();
                    $totalEmployees = $totalRow['total'];
                    $totalPages = ceil($totalEmployees / $employeesPerPage);

                    $sql = "SELECT NHANVIEN.Ma_NV, NHANVIEN.Ten_NV, NHANVIEN.Phai, NHANVIEN.Noi_Sinh, PHONGBAN.Ten_Phong, NHANVIEN.Luong 
                            FROM NHANVIEN 
                            JOIN PHONGBAN ON NHANVIEN.Ma_Phong = PHONGBAN.Ma_Phong 
                            ORDER BY NHANVIEN.Ma_NV 
                            LIMIT $employeesPerPage OFFSET $offset";
                    $result = $conn->query($sql);
                    ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Mã Nhân Viên</th>
                                    <th>Tên Nhân Viên</th>
                                    <th>Phái</th>
                                    <th>Nơi Sinh</th>
                                    <th>Tên Phòng</th>
                                    <th>Lương</th>
                                    <?php if ($userRole == 'admin'): ?>
                                        <th>Hành Động</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $genderImage = ($row['Phai'] == 'NU') ? 'images/nu.png' : 'images/nam.png';
                                        $genderText = ($row['Phai'] == 'NU') ? 'Nữ' : 'Nam';
                                        echo "<tr>";
                                        echo "<td>" . $row['Ma_NV'] . "</td>";
                                        echo "<td>" . $row['Ten_NV'] . "</td>";
                                        echo "<td><img src='$genderImage' alt='$genderText' title='$genderText' class='img-fluid' style='width: 30px; height: 30px; margin-right: 5px;'> $genderText</td>";
                                        echo "<td>" . $row['Noi_Sinh'] . "</td>";
                                        echo "<td>" . $row['Ten_Phong'] . "</td>";
                                        echo "<td>" . $row['Luong'] . "</td>";
                                        if ($userRole == 'admin') {
                                            echo "<td>";
                                            echo "<a href='edit_employee.php?id=" . $row['Ma_NV'] . "' class='btn btn-sm btn-success me-1'><i class='bi bi-pencil'></i> Sửa</a>";
                                            echo "<a href='delete_employee.php?id=" . $row['Ma_NV'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa nhân viên này?\");'><i class='bi bi-trash'></i> Xóa</a>";
                                            echo "</td>";
                                        }
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='" . ($userRole == 'admin' ? 7 : 6) . "' class='text-center'>Không có dữ liệu nhân viên.</td></tr>";
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pagination-container">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mt-3">
                            <?php
                            if ($page > 1) {
                                echo "<li class='page-item'><a class='page-link' href='index.php?page=" . ($page - 1) . "'>« Trước</a></li>";
                            }

                            for ($i = 1; $i <= $totalPages; $i++) {
                                $activeClass = ($i == $page) ? "active" : "";
                                echo "<li class='page-item $activeClass'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
                            }

                            if ($page < $totalPages) {
                                echo "<li class='page-item'><a class='page-link' href='index.php?page=" . ($page + 1) . "'>Sau »</a></li>";
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5">
        <div class="container">
            <p class="mb-0">© 2025 Quản Lý Nhân Sự. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>