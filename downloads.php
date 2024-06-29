<?php
include_once ("./includes/header.php");

$global_name = htmlspecialchars(@$global_full_name);
$global_email = htmlspecialchars(@$global_email);
$global_mobile = htmlspecialchars(@$global_mobile);

$search_g = @$_GET['q'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
?>
<?php
$temp = rand(0, 99999999);
rename($global_folder_name, $temp);
$sql = "UPDATE `download_folder` SET `folder`='$temp' WHERE `folder`='$global_folder_name'";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoubtHunt - Answer For Every Doubt</title>
    <link rel="stylesheet" href="path_to_your_css_file.css">
    <style>
        /* Additional styles for cart summary */
        .cart-summary {
            position: fixed;
            bottom: 0;
            right: 20px; /* Position on the right side */
            width: 300px;
            max-width: 350px; /* Adjusted max-width for desktop */
            max-height: 80vh; /* Set maximum height */
            overflow-y: auto; /* Enable vertical scroll if content exceeds max-height */
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1000;
            display: none; /* Hide by default */
        }
        .cart-summary h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            text-align: center; /* Center align heading */
        }
        .cart-summary p {
            margin: 5px 0;
        }
        .cart-summary .total {
            font-weight: bold;
            margin-top: 10px;
        }
        .cart-summary button {
            width: 100%;
            padding: 10px;
            background: #528FF0; /* Blue background color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Media queries for mobile devices */
        @media (max-width: 768px) {
            .cart-summary {
                display: block; /* Display cart summary on mobile devices */
                position: fixed;
                bottom: 0;
                left: 20;
                width: 100%; /* Take full width */
                max-height: 50vh; /* Adjusted maximum height for mobile */
            }
        }

        /* Display cart summary on larger screens */
        @media (min-width: 769px) {
            .cart-summary {
                display: block;
                position: fixed;
                top: 20%;
                right: 20px; /* Position on the right side */
                width: 300px;
                max-width: 350px; /* Adjusted max-width for desktop */
                max-height: 50vh; /* Adjusted maximum height for desktop */
            }
        }

        /* Styles for pagination */
        .pagination {
            display: flex;
            justify-content: flex-end; /* Move to the right */
            padding: 20px 0;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 20px;
            background: #f1f1f1;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background: #528FF0; /* Highlight color for the current page */
            color: white;
        }
        .pagination a:hover {
            background: #ddd;
        }
    </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php $new_folder_name = rand(0, 99999999); ?>
<script>
    $(document).ready(function () {
        let selectedItems = JSON.parse(localStorage.getItem('selectedItems')) || [];

        $('body').on('click', '.select_pdf', function () {
            let itemId = $(this).data('id');
            let itemName = $(this).data('name');
            let itemAmount = $(this).data('amount');
            let itemDownload = $(this).data('download');

            let item = { id: itemId, name: itemName, amount: itemAmount, download: itemDownload };

            if ($(this).is(':checked')) {
                selectedItems.push(item);
            } else {
                selectedItems = selectedItems.filter(i => i.id !== itemId);
            }

            localStorage.setItem('selectedItems', JSON.stringify(selectedItems));
            updateCartSummary();
        });

        function updateCartSummary() {
            let cartSummary = $('#cart_summary');
            cartSummary.empty();

            if (selectedItems.length === 0) {
                cartSummary.append('<p>No PDFs selected.</p>');
                return;
            }

            let totalAmount = selectedItems.reduce((sum, item) => sum + parseInt(item.amount), 0);

            selectedItems.forEach(item => {
                cartSummary.append('<p>' + item.name + ' - Rs. ' + item.amount + '</p>');
            });

            cartSummary.append('<p class="total"><strong>Total: Rs. ' + totalAmount + '</strong></p>');

            let buyButton = $('<button id="buy_selected">Buy Selected PDFs</button>');
            cartSummary.append(buyButton);
        }

        $('body').on('click', '#cart_summary #buy_selected', function (e) {
            e.preventDefault();

            if (selectedItems.length === 0) {
                alert('Please select at least one PDF.');
                return;
            }

            let totalAmount = selectedItems.reduce((sum, item) => sum + parseInt(item.amount), 0);
            let productNames = selectedItems.map(item => item.name).join(', ');

            let options = {
                "key": "rzp_live_vqPAKDMjCFqq3F", // Replace with your Razorpay key ID
                "amount": totalAmount * 100, // Amount in paise
                "name": productNames,
                "description": productNames,
                "image": "https://doubthunt.com/core/img/favicon.png",
                "prefill": {
                    "name": "<?php echo $global_name; ?>",
                    "email": "<?php echo $global_email; ?>",
                    "contact": "<?php echo $global_mobile; ?>"
                },
                "handler": function (response) {
                    $.ajax({
                        url: 'paymentD.php',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            totalAmount: totalAmount,
                            products: selectedItems,
                            packageFolder: "<?=$new_folder_name?>"
                        },
                        success: function (response) {
                            console.log("success", response);

                            selectedItems.forEach(item => {
                                var downloadLink = document.createElement('a');
                                downloadLink.href = './<?=$new_folder_name?>/' + item.download;
                                downloadLink.download = item.download;
                                document.body.appendChild(downloadLink);
                                downloadLink.click();
                                document.body.removeChild(downloadLink);
                            });

                            window.location.href = 'index.php';
                        },
                        error: function (xhr, status, error) {
                            console.error("Payment failed:", status, error);
                        }
                    });
                },
                "theme": {
                    "color": "#528FF0"
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
        });

        $('body').on('click', '.buy_now', function (e) {
            e.preventDefault();

            let totalAmount = $(this).data("amount");
            let productId = $(this).data("id");
            let packageName = $(this).data("name");
            let packageDownload = $(this).data("download");

            let options = {
                "key": "rzp_live_vqPAKDMjCFqq3F", // Replace with your Razorpay key ID
                "amount": totalAmount * 100, // Amount in paise
                "name": packageName,
                "description": packageName,
                "image": "https://doubthunt.com/core/img/favicon.png",
                "prefill": {
                    "name": "<?php echo $global_name; ?>",
                    "email": "<?php echo $global_email; ?>",
                    "contact": "<?php echo $global_mobile; ?>"
                },
                "handler": function (response) {
                    $.ajax({
                        url: 'paymentD.php',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            totalAmount: totalAmount,
                            product_id: productId,
                            packageFolder: "<?=$new_folder_name?>"
                        },
                        success: function (response) {
                            console.log("success", response);

                            var downloadLink = document.createElement('a');
                            downloadLink.href = './<?=$new_folder_name?>/' + packageDownload;
                            downloadLink.download = packageDownload;
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);

                            window.location.href = 'index.php';
                        },
                        error: function (xhr, status, error) {
                            console.error("Payment failed:", status, error);
                        }
                    });
                },
                "theme": {
                    "color": "#528FF0"
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
        });

        // Restore selections from local storage
        selectedItems.forEach(item => {
            $('input[data-id="' + item.id + '"]').prop('checked', true);
        });
        updateCartSummary();
    });
</script>
<div class="container">
    <div class="max-w-[800px] m-auto">
        <div class="w-full h-auto lg:h-screen">
            <br /> <div class="mt-[100px] p-4">
                </p>
                <form action="#" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Search Study Material"
                        class="transition w-full pl-12 p-4 rounded-xl shadow-none focus:shadow-xl border-[1px]" />
                    <i class="bi bi-search absolute left-5 top-5 text-zinc-600"></i>
                </form>
                <div class="cart-summary" id="cart_summary">
                    <h2 class="font-semibold text-xl text-center">Cart</h2>
                    <p>No PDFs selected.</p>
                </div>
                <?php
                if ($search_g == "") {
                    $sql = "SELECT * FROM `config_downloads` ORDER BY `id` DESC LIMIT $limit OFFSET $offset";
                } else {
                    $sql = "SELECT * FROM `config_downloads` WHERE `name` LIKE '%$search_g%' LIMIT $limit OFFSET $offset";
                }
                $query = mysqli_query($conn, $sql);
                while ($rows = mysqli_fetch_assoc($query)) {
                    $id = htmlspecialchars($rows['id']);
                    $name = htmlspecialchars($rows['name']);
                    $content = htmlspecialchars($rows['content']);
                    $amount = htmlspecialchars($rows['price']);
                    $value = htmlspecialchars($rows['value']);
                    ?>
                    <div class="w-full bg-white shadow-none hover:shadow-xl rounded-lg border-[1px] mb-4">
                        <div class="p-4 lg:items-center gap-4 justify-between flex flex-col lg:flex-row">
                            <div class="flex-[0.5]">
                                <img src="./core/img/extension.png" class="lg:w-full">
                            </div>
                            <div class="flex-[7]">
                                <h1 class="text-xl font-semibold leading-10"><?= $name ?></h1>
                                <p class="text-zinc-600 text-sm"><?= $content ?></p>
                            </div>
                            <div class="flex-[2] flex items-center">
                                <input type="checkbox" class="select_pdf mr-2" data-id="<?= $id ?>" data-name="<?= $name ?>" data-amount="<?= $amount ?>" data-download="<?= $value ?>"> Select
                                <button
                                    class="bg-orange-500 rounded-lg text-white font-semibold text-center w-full py-2 px-4 ml-2 buy_now"
                                    data-amount="<?= $amount ?>" data-id="<?= $id ?>" data-name="<?= $name ?>"
                                    data-download="<?= $value ?>" data-folder="<?= $new_folder_name ?>">
                                    Rs. <?= $amount ?> <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                
                // Pagination logic
                $sql_total = "SELECT COUNT(*) as total FROM `config_downloads`";
                $result_total = mysqli_query($conn, $sql_total);
                $total_rows = mysqli_fetch_assoc($result_total)['total'];
                $total_pages = ceil($total_rows / $limit);

                echo '<div class="pagination">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($i == $page) ? 'active' : '';
                    echo '<a href="?page=' . $i . '" class="' . $active_class . '">' . $i . '</a> ';
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=' . ($page + 1) . '">Next</a>';
                }
                echo '</div>';
                ?>
            </div>
            <footer class="footer">
                <?php include_once ("./includes/footer.php"); ?>
            </footer>
        </div>
    </div>
</div>
</body>
</html>
