<!DOCTYPE html>
<html lang="en">
<?php
$quiz = @$_GET['quiz'];
$code = @$_GET['success'];
$image = @$_GET['image'];
?>

<head>
    <meta charset="UTF-8">
    <?php include_once("./includes/header.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask Question</title>
</head>

<body>
    <div>
        <br />
        <div class="mt-20 mb-10 max-w-[1280px] m-auto h-[600px] p-10">
            <div class="max-w-[650px] m-auto bg-white rounded-xl shadow-xl p-10">
                <?php
                if ($limitUse <= 0) {
                    echo "<meta http-equiv=\"refresh\" content=\"0; url=plans.php\">";
                    exit();
                }
                $sql = "SELECT * FROM `active_plans` WHERE `userID`='$global_id' AND `expired`='1'";
                $query = mysqli_query($conn, $sql);
                $expiredCount = mysqli_num_rows($query);
                $sql2 = "SELECT * FROM `active_plans` WHERE `userID`='$global_id'";
                $query2 = mysqli_query($conn, $sql2);
                $nonExpiredCount = mysqli_num_rows($query2);
                $cal = $nonExpiredCount - $expiredCount;
                if ($cal == "0") {
                    echo "<meta http-equiv=\"refresh\" content=\"0; url=plans.php\">";
                    exit();
                }
                ?>
                <?php
                $date = date("Y-m-d");
                if (isset($_POST['ask'])) {
                    $question = @$_POST['question'];
                    $categoryID = @$_POST['categoryID'];
                    $media = @$_POST['media'];
                    $sql = "INSERT INTO `questions`(`id`, `userID`, `question`, `media`, `categoryID`, `dateTime`) VALUES (null,'$global_id','$question','$media','$categoryID','$date')";
                    $query = mysqli_query($conn, $sql);

                    $lessLimitUse = $limitUse - 1;
                    $sql2 = "UPDATE `active_plans` SET `limitUse`='$lessLimitUse' WHERE `userID`='$global_id' AND `expired`='0'";
                    $query2 = mysqli_query($conn, $sql2);

                    echo "<meta http-equiv=\"refresh\" content=\"0; url=dashboard.php\">";
                }
                if ($code == "1") {
                ?>
                    <center>
                        <img src='./core/img/tick.gif' class="max-w-[300px]">
                        <h1 class="font-bold text-orange-500 text-3xl">Submitted!</h1>
                        <h1 class="font-light font-xl mt-2 mb-6">Will be resolved soon!!</h1>
                        <a href='index.php' class="transition hover:bg-black py-2 px-4 text-white bg-orange-400 rounded-md">Back To Home</a>
                    </center>
                <?php
                } else {
                ?>
                    <?php
                    if (isset($_SESSION['username'])) {
                    ?>
                        <form id="questionForm" action="send.php" method="POST">
                            <label for="question" class="font-semibold text-xl">Question
                                <img src="./uploads/<?= $image ?>" class="mt-2 w-full rounded-lg">
                                <input type="hidden" name="media" value="<?= $image ?>">
                                <textarea name="question" class="w-full border-[1px] p-2 text-sm h-[100px] resize-none rounded-xl mt-5" required="required"><?= $quiz ?></textarea>
                            </label>
                            <label class="mt-4 block text-sm">Now Choose Subject?</label>
                            <select id="categoryID" name="categoryID" class="w-full mt-2 py-2 px-4 border-[1px] rounded-xl">
                                <option value="">--Select a Subject--</option>
                                <?php
                                $sql = "SELECT * FROM `config_subject`";
                                $query = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($query)) {
                                    $id = $row['id'];
                                    $value = $row['value'];
                                ?>
                                    <option value="<?= $id ?>"><?= $value ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <input type="submit" name="ask" value="Get Answer!" class="mt-5 w-full p-2 bg-orange-400 text-white font-semibold rounded-xl text-sm" />
                        </form>
                        <script>
                            document.getElementById('questionForm').addEventListener('submit', function (event) {
                                const categoryID = document.getElementById('categoryID').value;
                                if (categoryID === "") {
                                    event.preventDefault(); // Prevent form submission
                                    alert("Please select a subject before submitting the form.");
                                }
                            });
                        </script>
                    <?php
                    } else {
                    ?>
                        <center>
                            <img src='./core/img/login.gif' class="max-w-[300px]">
                            <h1 class="font-bold text-orange-500 text-3xl">Opps!</h1>
                            <h1 class="font-light font-xl">You must be logged in!</h1>
                        </center>
                    <?php
                    }
                }
                    ?>
            </div>
        </div>
    </div>
</body>
<?php include_once("./includes/footer.php"); ?>

</html>
