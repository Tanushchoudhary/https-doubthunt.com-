<?php
include_once("./includes/header.php");

// Example of retrieving user-specific variables
$global_name = $global_full_name;

if ($limitUse <= 0) {
    // Handle case when limitUse is zero or less, currently not defined in your snippet
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url=index.php\">";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoubtHunt - Answer For Every Doubt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function () {
            $('body').on('click', '.buy_now', function (e) {
                e.preventDefault();

                var totalAmount = $(this).data("amount");
                var productId = $(this).data("id");
                var packageName = $(this).data("name");
                var packageDays = $(this).data("days");
                var packageQty = $(this).data("qty");

                var options = {
                    "key": "rzp_live_vqPAKDMjCFqq3F", // Replace with your Razorpay key ID
                    "amount": totalAmount * 100, // Amount in paise
                    "name": packageName,
                    "description": packageName + " - " + packageDays + " days",
                    "image": "https://doubthunt.com/core/img/favicon.png",
                    "prefill": {
                        "name": "<?php echo htmlspecialchars($global_name); ?>",
                        "email": "<?php echo htmlspecialchars($global_email); ?>",
                        "contact": "<?php echo htmlspecialchars($global_mobile); ?>"
                    },
                    "handler": function (response) {
                        $.ajax({
                            url: 'payment.php',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                razorpay_payment_id: response.razorpay_payment_id,
                                totalAmount: totalAmount,
                                product_id: productId,
                                package_days: packageDays,
                                package_qty: packageQty
                            },
                            success: function (response) {
                                if (response.success) {
                                    window.location.href = 'index.php';
                                } else {
                                    window.location.href = 'plans.php?error=payment_failed';
                                }
                            },
                            error: function () {
                                window.location.href = 'plans.php?error=ajax_error';
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
        });
    </script>
</head>

<body>
    <div class="max-w-[1280px] m-auto">
        <div class="w-full h-auto lg:h-screen">
            <br />
            <div class="mt-[200px] p-4">
                <h1 class="font-semibold text-orange-500 text-5xl text-center">Plans</h1>
                <p class="font-semibold text-xl text-zinc-800 text-center mt-5 mb-10">
                    Our pricing plans are designed to be affordable, flexible, and tailored to your unique needs.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 lg:gap-10 mb-10">
                    <?php
                    $sql = "SELECT * FROM `config_plans`";
                    $query = mysqli_query($conn, $sql);
                    while ($rows = mysqli_fetch_assoc($query)) {
                        $name = htmlspecialchars($rows['package_name']);
                        $days = htmlspecialchars($rows['days']);
                        $qty = htmlspecialchars($rows['qty']);
                        $amount = htmlspecialchars($rows['amount']);
                        $id = htmlspecialchars($rows['id']); // Assuming there's an `id` column for each plan
                        ?>
                        <div class="p-10 bg-white rounded-lg w-full shadow-xl">
                            <h3 class="text-xl font-semibold"><?= $name ?></h3>
                            <h1 class="text-3xl mt-2 font-bold text-orange-500">
                                <span class="mr-2 text-lg text-zinc-700 font-regular">Rs.</span>
                                <?= $amount ?>
                                <span class="text-sm text-zinc-700 font-light">/ <?= $days ?> days</span>
                            </h1>
                            <hr class="mt-5 mb-5" />
                            <p class="text-center text-sm font-semibold">Resolve <?= $qty ?> Questions Per Day.</p>
                            <button
                                class="bg-orange-500 rounded-lg text-white font-semibold text-center w-full py-2 px-4 mt-5 buy_now"
                                data-amount="<?= $amount ?>" data-id="<?= $id ?>" data-name="<?= $name ?>"
                                data-days="<?= $days ?>" data-qty="<?= $qty ?>">
                                Buy Now!
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include_once("./includes/footer.php"); ?>
</body>

</html>
